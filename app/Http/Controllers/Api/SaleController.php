<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\SaleDetailController;
use App\Http\Controllers\Api\InvoiceSeriesController;
use App\Http\Controllers\BoletaFactura;
use App\Models\Sale;
use App\Models\InvoiceStatus;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\CompanyController;
use App\NumerosEnLetras;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function registrarVenta(Request $request)
    {
        $store = $this->store($request);

        if ($store['status'] === true) {
            return $this->enviarBoleta($store['saleId'], $request->tipoDocumento, $request->idCliente);
        }

        return $store;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $correlativo = new InvoiceSeriesController();
            $numeroCorrelativo = $correlativo->getAndIncreaseCorrelative('B001'); // TODO: Implementar logica

            $sale = new Sale();
            $sale->invoice_series_id = 1; // TODO: Actualizar envio desde el front
            $sale->fecha_emision = $request->fecha_emision;
            $sale->correlativo = $numeroCorrelativo;
            $sale->client_id = $request->idCliente;
            $sale->company_id = 1; //TODO
            $sale->currency_id = 1; //TODO
            $sale->tax_rate_id = 1; //TODO
            $sale->sale_status_id = 1; //TODO
            // $sale->user_id = Auth::id(); //TODO
            $sale->user_id = 1;
            $sale->save();

            $saleId = $sale->id;

            $saleDetails = new SaleDetailController();
            $saleDetails->registerSalesItems($request->items, $saleId);

            DB::commit();
            return ['status' => true, 'saleId' => $saleId];

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return [
                'status' => false,
                'message' => $errorMessage,
                'code' => $errorCode
            ];
        }

    }

    public function enviarBoleta(int $saleId, int $tipoDocumento, mixed $clientId)
    {
        try {

            $emisor = $this->getDataCompany();

            $cliente = $this->getDataClient($tipoDocumento, $clientId);

            $detalle = $this->getDataItems($saleId);

            $comprobante = $this->getComprobante($detalle, $saleId);

            $boletaFactura = new BoletaFactura();
            $status = $boletaFactura->CrearXMLFactura($emisor, $cliente, $comprobante, $detalle, 'boleta/');
            $this->updateStatusSale($status, $saleId);
            return json_encode([
                'status' => $status['estado'],
                'mensaje' => 'Exitoso',
                'ticket' => [
                    'emisor' => $emisor,
                    'cliente' => $cliente,
                    'detalle' => $detalle,
                    'comprobante' => $comprobante,
                    'idVenta' => $saleId
                ]
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return json_encode([
                'status' => false,
                'message' => $errorMessage,
                'code' => $errorCode
            ]);
        }
    }

    public function updateStatusSale(array $status, int $saleId)
    {
        $estado = new InvoiceStatus();
        $estado->estado_facturacion = $status['estado'];
        $estado->codigo_error = $status['codigo'];
        $estado->mensaje_sunat = $status['mensaje'];
        $estado->sale_id = $saleId;
        $estado->save();
    }

    public function getComprobante(array $detalle, int $idSale)
    {
        $invoiceSerie = new InvoiceSeriesController();
        $datosVoucher = $invoiceSerie->getVoucherData($idSale);
        $datosCalculados = $this->getDatosCalculados($detalle);
        $numeroLetras = new NumerosEnLetras();

        return array(
            'tipodoc'       => $datosVoucher->tipo_documento, //FACTURA: 01, BOLETA:03, NOTA CREDITO:07, ND: 08
            'serie'         => $datosVoucher->serie, //F:FACTURA, B:BOLETA
            'correlativo'   => $datosVoucher->correlativo,
            'fecha_emision' => $datosVoucher->fecha_emision,
            'moneda'        => $datosVoucher->moneda, //PEN: SOLES, USD: DOLARES
            'total_opgravadas'      => $datosCalculados['op_gravadas'], //
            'total_opexoneradas'    => $datosCalculados['op_exoneradas'], //
            'total_opinafectas'     => $datosCalculados['op_inafectas'], //
            'igv'                   => $datosCalculados['igv'],
            'total'                 => $datosCalculados['total'],
            'total_texto'           => $numeroLetras->numtoletras($datosCalculados['total'],'SOLES')
        );
    }

    public function getDatosCalculados(array $detalle)
    {
        $op_gravadas = 0;
        $op_inafectas = 0;
        $op_exoneradas = 0;
        $igv = 0;
        $total = 0;

        foreach ($detalle as $k => $v) {
            if($v['codigo_afectacion_alt']=='10'){
            $op_gravadas = $op_gravadas + $v['valor_total'];
            }

            if($v['codigo_afectacion_alt']=='20'){
            $op_exoneradas = $op_exoneradas + $v['valor_total'];
            }

            if($v['codigo_afectacion_alt']=='30'){
            $op_inafectas = $op_inafectas + $v['valor_total'];
            }

            $igv = $igv + $v['igv'];
            $total = $total + $v['importe_total'];
        }

        return [
            'op_gravadas' => $op_gravadas,
            'op_inafectas' => $op_inafectas,
            'op_exoneradas' => $op_exoneradas,
            'igv' => $igv,
            'total' => $total
        ];
    }

    public function getDataItems($saleId)
    {
        $saleDetails = new SaleDetailController();
        $items = $saleDetails->getDocumentDetail($saleId);
        $detalles = [];
        foreach ($items as $i => $item) {

            $precioUnitario = $item->fraccion == true ? $item->precio_unidad : $item->precio_caja;
            $igv = $item->codigo == 10 ? $item->valor_igv : 0;
            $valorUnitario = $precioUnitario - ($precioUnitario * $igv);
            $detalle = array(
                'item'              => ++$i,
                'codigo'            => $item->codigo_producto, //codigo del producto del sistema
                'descripcion'       => $item->descripcion,
                'cantidad'          => $item->cantidad,
                'valor_unitario'    => $valorUnitario,
                'precio_unitario'   => (int)$precioUnitario,
                'tipo_precio'       => $item->tipo_precio, //Este precio incluye IGV Catálogo No. 16: Códigos – Tipo de precio de venta unitario
                'igv'               => bcmul($igv, bcmul($precioUnitario, $item->cantidad, 2), 2),
                'porcentaje_igv'    => (int)($item->valor_igv * 100), //0 a 100
                'valor_total'       => bcmul($valorUnitario, $item->cantidad, 2),
                'importe_total'     => bcmul($precioUnitario, $item->cantidad, 2),
                'unidad'            => $item->unidad_medida, //Unidad de medida
                'codigo_afectacion_alt' => $item->codigo, //Catálogo No. 07: Códigos de tipo de afectación del IGV
                'codigo_afectacion' => $item->codigo_afectacion, //Catálogo No. 05: Códigos de tipos de tributos
                'nombre_afectacion' => $item->nombre_afectacion, //Catálogo No. 05: Códigos de tipos de tributos
                'tipo_afectacion'   => $item->tipo_afectacion //GRAVADAS Catálogo No. 05: Códigos de tipos de tributos
            );
            $detalles[] = $detalle;
        }
        return $detalles;
    }

    public function getDataClient(int $tipoDocumento, mixed $idCliente)
    {
        if ($tipoDocumento == 1) {
            return array(
                'tipodoc'       => '1', //6: RUC, 1: DNI
                'ruc'           => '00000000',
                'razon_social'  => 'CLIENTE VARIOS',
                'direccion'     => '',
                'pais'          => 'PE', //codigo de pais
            );
        }

        $cliente = new ClientController();
        $cliente = $cliente->searchById($idCliente);
        return array(
            'tipodoc'       => '1', //6: RUC, 1: DNI
            'ruc'           => $cliente->numero_documento,
            'razon_social'  => $cliente->nombre,
            'direccion'     => $cliente->direccion,
            'pais'          => 'PE', //codigo de pais
        );
    }

    public function getDataCompany()
    {
        $company = new CompanyController();
        $data = $company->getCompanyData();
        return array(
            'tipodoc'       => $data->tipo_documento,
            'ruc'           => $data->ruc,
            'razon_social'  => $data->razon_social,
            'nombre_comercial'  => $data->nombre_comercial,
            'direccion'     => $data->direccion,
            'pais'          => 'PE', //codigo de pais
            'departamento'  => $data->departamento,
            'provincia'     => $data->provincia,
            'distrito'      => $data->distrito,
            'ubigeo'        => $data->ubigeo,
            'usuario_sol'   => 'MODDATOS',//$data->usuario_secundario, //Es el usuario secundario de emision electronica
            'clave_sol'     => 'MODDATOS'//$data->clave_secundaria, //clave del usuario secundario de emisión
        );

    }

    public function getDatosVentaById(Request $request)
    {
        $saleId = $request->idventa;
        $detalles = $this->getDataItems($saleId);

        $comprobante = $this->getComprobante($detalles, $saleId);

        return array_merge($comprobante, ['detalles' => $detalles]);
    }

    public function getDatosVentaBySerie(Request $request)
    {
        $serie = $request->serie;
        $correlativo = $request->correlativo;

        $invoiceSerie = new InvoiceSeriesController();
        $idVenta = $invoiceSerie->getIdBoleta($serie, $correlativo);

        if ($idVenta === null) {
            return [
                'success' => false,
                'mensaje' => 'No existe un documento con la serie y numero ingresado'
            ];
        }

        $detalles = $this->getDataItems($idVenta);
        $comprobante = $this->getComprobante($detalles, $idVenta);

        return array_merge($comprobante, ['detalles' => $detalles]);
    }

}
