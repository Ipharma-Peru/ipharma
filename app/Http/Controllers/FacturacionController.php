<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DOMDocument;
use ZipArchive;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class FacturacionController extends Controller
{
    public function enviarDocumento($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr)
    {
        $flg_firma = 0; //Posicion del XML: 0 para firma
        // $ruta_xml_firmar = $ruta . '.XML'; //es el archivo XML que se va a firmar
        // $ruta = $ruta_archivo_xml . $nombre . '.XML';
        // storage_path('app/public/fe/xml/firmados/' . $ruta_archivo_cdr);
        $ruta = storage_path($ruta_archivo_xml . $nombre . '.XML');

        $ruta_firma = storage_path('app/public/fe/firma/firma.pfx');
        $pass_firma = 'PharmaWeb20';
        
        $resp = $this->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);
        // print_r($resp);
        // echo '</br> XML FIRMADO';
        
        //FIRMAR XML - FIN
        
        //CONVERTIR A ZIP - INICIO
        $zip = new ZipArchive();

        $nombrezip = $nombre.".ZIP";
        $rutazip = storage_path($ruta_archivo_xml . $nombre.".ZIP");
        
        if($zip->open($rutazip, ZipArchive::CREATE) === TRUE)
        {
            $zip->addFile($ruta, $nombre . '.XML');
            $zip->close();
        }
        
        // echo '</br>XML ZIPEADO';
        
        //CONVERTIR A ZIP - FIN
        
        
        //ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
        $ws = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService'; //ruta del servicio web de pruebad e SUNAT para enviar documentos
        // $ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
        $ruta_archivo = $rutazip;
		$nombre_archivo = $nombrezip;

        $contenido_del_zip = base64_encode(file_get_contents($ruta_archivo)); //codificar y convertir en texto el .zip
        
        //echo '</br> '. $contenido_del_zip;
        $xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>'.$emisor['ruc'].$emisor['usuario_sol'].'</wsse:Username>
                                <wsse:Password>'.$emisor['clave_sol'].'</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                        </soapenv:Header>
                        <soapenv:Body>
                        <ser:sendBill>
                            <fileName>'.$nombre_archivo.'</fileName>
                            <contentFile>'.$contenido_del_zip.'</contentFile>
                        </ser:sendBill>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        
            $header = array(
                "Content-type: text/xml; charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-lenght: ".strlen($xml_envio)
                );
        
        $ch = curl_init(); //iniciar la llamada
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1); //
        curl_setopt($ch,CURLOPT_URL, $ws);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch,CURLOPT_TIMEOUT, 30);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $xml_envio);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        
        //para ejecutar los procesos de forma local en windows
        //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea
        
        $response = curl_exec($ch); // ejecucion del llamado y respuesta del WS SUNAT.
        
        $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
        $estadofe = "0"; //inicializo estado de operación interno
        // storage_path('app/public/fe/xml/firmados/' . $ruta_archivo_cdr);
        // $ruta_cdr = "fe/xml/firmados/" . $ruta_archivo_cdr;
        $ruta_cdr = storage_path('app/public/fe/xml/firmados/' . $ruta_archivo_cdr);
        if($httpcode == 200)//200: La comunicacion fue satisfactoria
        {
            $doc = new DOMDocument();//clase que nos permite crear documentos XML
            $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response
        
            if( isset( $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue ) ) // si en la etique de rpta hay valor entra
            {
                $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue; //guadarmos la respuesta(text-xml) en la variable 
                $cdr = base64_decode($cdr); //decodificando el xml
                file_put_contents($ruta_cdr . 'R-' . $nombrezip, $cdr ); //guardo el CDR zip en la carpeta cdr
                $zip = new ZipArchive();
                if($zip->open($ruta_cdr. 'R-' . $nombrezip ) === true ) //rpta es identica existe el archivo
                {
                    $zip->extractTo($ruta_cdr, 'R-' . $nombre . '.XML');
                    $zip->close();
                }
                $data = [
                    'estado' => 1,
                    'mensaje' => 'Envio exitoso',
                    'codigo' => null
                ];
            }
            else {
                $estadofe = '2';
                $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                $codigo = explode('.', $codigo)[1];
                $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                //LOG DE TRAX ERRORES DB
                $data = [
                    'estado' => 2,
                    'codigo' => $codigo,
                    'mensaje' => $mensaje
                ];
                // echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
            }
        }
        else { //Problemas de comunicacion
            $data = [
                'estado' => 3,
                'codigo' => curl_errno($ch),
                'mensaje' => curl_error($ch)
            ];
            // $estadofe = "3";
            //LOG DE TRAX ERRORES DB
            echo curl_error($ch);
            // echo 'Hubo existe un problema de conexión';
        }
        
        curl_close($ch);
        return $data;
        //ENVIAR EL ZIP A LOS WS DE SUNAT - FIN
    }

    public function signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma) {        
        $doc = new DOMDocument();

        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->load($ruta);

        $objDSig = new XMLSecurityDSig(FALSE);
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
        $options['force_uri'] = TRUE;
        $options['id_name'] = 'ID';
        $options['overwrite'] = FALSE;

        $objDSig->addReference($doc, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'), $options);
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

        $pfx = file_get_contents($ruta_firma);
        $key = array();

        openssl_pkcs12_read($pfx, $key, $pass_firma);
        $objKey->loadKey($key["pkey"]);
        $objDSig->add509Cert($key["cert"], TRUE, FALSE);
        $objDSig->sign($objKey, $doc->documentElement->getElementsByTagName("ExtensionContent")->item($flg_firma));

        $atributo = $doc->getElementsByTagName('Signature')->item(0);
        $atributo->setAttribute('Id', 'SignatureSP');
        
        //===================rescatamos Codigo(HASH_CPE)==================
        $hash_cpe = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        $firma_cpe = $doc->getElementsByTagName('SignatureValue')->item(0)->nodeValue;

        $doc->save($ruta);
        $resp['respuesta'] = 'ok';
        $resp['hash_cpe'] = $hash_cpe;
        $resp['firma_cpe'] = $firma_cpe;
        return $resp;
    }
}
