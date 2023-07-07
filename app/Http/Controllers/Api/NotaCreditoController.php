<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\InvoiceSeriesController;
use App\Http\Controllers\FacturacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaCreditoController extends Controller
{
    protected $saleController;
    protected $invoiceSerie;
    public function __construct(
        SaleController $saleController,
        InvoiceSeriesController $invoiceSerie
    )
    {
        $this->saleController = $saleController;
        $this->invoiceSerie = $invoiceSerie;
    }

    public function emitirNotaCredito(Request $request)
    {
        try {
            DB::beginTransaction();
            $salesId = $request->idVenta;
            $tipoDocumentoCliente = $request->tipoDocumentoCliente;
            $idCliente = $request->idCliente;

            $emisor = $this->saleController->getDataCompany();

            $cliente = $this->saleController->getDataClient($tipoDocumentoCliente, $idCliente);

            $detalle = $this->saleController->getDataItems($salesId);

            $comprobante = $this->getComprobante($request, $detalle, $salesId);
            $respuesta = $this->CrearXMLNotaCredito($emisor, $cliente, $comprobante, $detalle, 'nota_credito/');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return json_encode([
                'status' => false,
                'message' => $errorMessage,
                'code' => $errorCode
            ]);
        }
        
    }

    public function registrarNotaCredito()
    {

    }

    public function getComprobante(Request $request, array $detalle, int $salesId)
    {
        $comprobante = $this->saleController->getComprobante($detalle, $salesId);
        $comprobante['tipodoc_ref'] = $comprobante['tipodoc'];
        $comprobante['serie_ref'] = $comprobante['serie'];
        $comprobante['correlativo_ref'] = $comprobante['correlativo'];
        $comprobante['codmotivo'] = $request->cod_motivo;
        $comprobante['descripcion'] = $request->descripcion;

        $correlativo = $this->invoiceSerie->getAndIncreaseCorrelative($request->serie);
        $comprobante['tipodoc'] = '07';
        $comprobante['serie'] = $request->serie;
        $comprobante['correlativo'] = $correlativo;
        $comprobante['fecha_emision'] = $request->fecha_emision;

        return $comprobante;
    }

    function CrearXMLNotaCredito($emisor, $cliente, $comprobante, $detalle, $rutaDocumento)
    {
    //    $doc = new DOMDocument();
    //    $doc->formatOutput = FALSE;
    //    $doc->preserveWhiteSpace = TRUE;
    //    $doc->encoding = 'utf-8'; 
 
       


    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
       <ext:UBLExtensions>
          <ext:UBLExtension>
             <ext:ExtensionContent />
          </ext:UBLExtension>
       </ext:UBLExtensions>
       <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
       <cbc:CustomizationID>2.0</cbc:CustomizationID>
       <cbc:ID>'.$comprobante['serie'].'-'.$comprobante['correlativo'].'</cbc:ID>
       <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
       <cbc:IssueTime>00:00:01</cbc:IssueTime>
       <cbc:Note languageLocaleID="1000"><![CDATA['.$comprobante['total_texto'].']]></cbc:Note>
       <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
       <cac:DiscrepancyResponse>
          <cbc:ReferenceID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ReferenceID>
          <cbc:ResponseCode>'.$comprobante['codmotivo'].'</cbc:ResponseCode>
          <cbc:Description>'.$comprobante['descripcion'].'</cbc:Description>
       </cac:DiscrepancyResponse>
       <cac:BillingReference>
          <cac:InvoiceDocumentReference>
             <cbc:ID>'.$comprobante['serie_ref'].'-'.$comprobante['correlativo_ref'].'</cbc:ID>
             <cbc:DocumentTypeCode>'.$comprobante['tipodoc_ref'].'</cbc:DocumentTypeCode>
          </cac:InvoiceDocumentReference>
       </cac:BillingReference>
       <cac:Signature>
          <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
          <cbc:Note><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Note>
          <cac:SignatoryParty>
             <cac:PartyIdentification>
                <cbc:ID>'.$emisor['ruc'].'</cbc:ID>
             </cac:PartyIdentification>
             <cac:PartyName>
                <cbc:Name><![CDATA['.$emisor['razon_social'].']]></cbc:Name>
             </cac:PartyName>
          </cac:SignatoryParty>
          <cac:DigitalSignatureAttachment>
             <cac:ExternalReference>
                <cbc:URI>#SIGN-EMPRESA</cbc:URI>
             </cac:ExternalReference>
          </cac:DigitalSignatureAttachment>
       </cac:Signature>
       <cac:AccountingSupplierParty>
          <cac:Party>
             <cac:PartyIdentification>
                <cbc:ID schemeID="'.$emisor['tipodoc'].'">'.$emisor['ruc'].'</cbc:ID>
             </cac:PartyIdentification>
             <cac:PartyName>
                <cbc:Name><![CDATA['.$emisor['nombre_comercial'].']]></cbc:Name>
             </cac:PartyName>
             <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA['.$emisor['razon_social'].']]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                   <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                   <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                   <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                   <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                   <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                   <cbc:District>'.$emisor['distrito'].'</cbc:District>
                   <cac:AddressLine>
                      <cbc:Line><![CDATA['.$emisor['direccion'].']]></cbc:Line>
                   </cac:AddressLine>
                   <cac:Country>
                      <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                   </cac:Country>
                </cac:RegistrationAddress>
             </cac:PartyLegalEntity>
          </cac:Party>
       </cac:AccountingSupplierParty>
       <cac:AccountingCustomerParty>
          <cac:Party>
             <cac:PartyIdentification>
                <cbc:ID schemeID="'.$cliente['tipodoc'].'">'.$cliente['ruc'].'</cbc:ID>
             </cac:PartyIdentification>
             <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                   <cac:AddressLine>
                      <cbc:Line><![CDATA['.$cliente['direccion'].']]></cbc:Line>
                   </cac:AddressLine>
                   <cac:Country>
                      <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                   </cac:Country>
                </cac:RegistrationAddress>
             </cac:PartyLegalEntity>
          </cac:Party>
       </cac:AccountingCustomerParty>
       <cac:TaxTotal>
          <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
          <cac:TaxSubtotal>
             <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_opgravadas'].'</cbc:TaxableAmount>
             <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['igv'].'</cbc:TaxAmount>
             <cac:TaxCategory>
                <cac:TaxScheme>
                   <cbc:ID>1000</cbc:ID>
                   <cbc:Name>IGV</cbc:Name>
                   <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
             </cac:TaxCategory>
          </cac:TaxSubtotal>';

          if($comprobante['total_opexoneradas']>0){
             $xml.='<cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_opexoneradas'].'</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                <cac:TaxCategory>
                   <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                   <cac:TaxScheme>
                      <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                      <cbc:Name>EXO</cbc:Name>
                      <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                   </cac:TaxScheme>
                </cac:TaxCategory>
             </cac:TaxSubtotal>';
          }

          if($comprobante['total_opinafectas']>0){
             $xml.='<cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total_opinafectas'].'</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">0.00</cbc:TaxAmount>
                <cac:TaxCategory>
                   <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                   <cac:TaxScheme>
                      <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                      <cbc:Name>INA</cbc:Name>
                      <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                   </cac:TaxScheme>
                </cac:TaxCategory>
             </cac:TaxSubtotal>';
          }

       $xml.='</cac:TaxTotal>
       <cac:LegalMonetaryTotal>
          <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total'].'</cbc:PayableAmount>
       </cac:LegalMonetaryTotal>';
       
       foreach($detalle as $k=>$v){

          $xml.='<cac:CreditNoteLine>
             <cbc:ID>'.$v['item'].'</cbc:ID>
             <cbc:CreditedQuantity unitCode="'.$v['unidad'].'">'.$v['cantidad'].'</cbc:CreditedQuantity>
             <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:LineExtensionAmount>
             <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                   <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['precio_unitario'].'</cbc:PriceAmount>
                   <cbc:PriceTypeCode>'.$v['tipo_precio'].'</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
             </cac:PricingReference>
             <cac:TaxTotal>
                <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                <cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_total'].'</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="'.$comprobante['moneda'].'">'.$v['igv'].'</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cbc:Percent>'.$v['porcentaje_igv'].'</cbc:Percent>
                      <cbc:TaxExemptionReasonCode>'.$v['codigo_afectacion_alt'].'</cbc:TaxExemptionReasonCode>
                      <cac:TaxScheme>
                         <cbc:ID>'.$v['codigo_afectacion'].'</cbc:ID>
                         <cbc:Name>'.$v['nombre_afectacion'].'</cbc:Name>
                         <cbc:TaxTypeCode>'.$v['tipo_afectacion'].'</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>
             </cac:TaxTotal>
             <cac:Item>
                <cbc:Description><![CDATA['.$v['descripcion'].']]></cbc:Description>
                <cac:SellersItemIdentification>
                   <cbc:ID>'.$v['codigo'].'</cbc:ID>
                </cac:SellersItemIdentification>
             </cac:Item>
             <cac:Price>
                <cbc:PriceAmount currencyID="'.$comprobante['moneda'].'">'.$v['valor_unitario'].'</cbc:PriceAmount>
             </cac:Price>
          </cac:CreditNoteLine>';
       }
       $xml.='</CreditNote>';






 
       // Guarda el XML en un archivo
       $nombreDocumento = $emisor['ruc'] . '-' . $comprobante['tipodoc'] . '-' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
       // $ruta = storage_path('app/public/fe/xml/creados/venta/');
       $ruta = 'app/public/fe/xml/creados/' . $rutaDocumento;
       $nombrexml= $nombreDocumento . '.XML';
       // $rutaArchivo = storage_path('app/public/fe/xml/creados/venta/productos.xml');
       $rutaArchivo = storage_path($ruta . $nombrexml);
       file_put_contents($rutaArchivo, $xml);

       $facturacion = new FacturacionController();
       return $facturacion->enviarDocumento($emisor, $nombreDocumento, $ruta, $rutaDocumento);
    }

}
