<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Respuestas
 *
 * @author root
 */
class RespuestasSistema {

    //put your code here
    const EXITO = 'EXITO';
    const INFO = 'INFO';
    const FALLO = 'FALLO';
    const ALERTA = 'ALERTA';
    const ERROR = 'ERROR';

    public static function responseJson($respuesta, $mensaje, $datos = null) {
        echo self::respuesta($respuesta, $mensaje, $datos);
    }

    public static function respuesta($respuesta, $mensaje, $datos = null, $error = null) {
        if(!isset($_SESSION)){
            @session_start();
        }
        if(array_key_exists('IDLOG',$_SESSION) && !empty(SesionCliente::valor("IDLOG"))):
            Log::respuestaOperacion(SesionCliente::valor("IDLOG"), $respuesta, $mensaje);
        endif;
        @session_write_close();

        if( !empty(SesionCliente::valor('ERROR_BD')) ){
          $mensaje .= "<br /><strong>___Datos para Soporte TICS___</strong><br /><em><small>" . SesionCliente::valor('ERROR_BD')."</small></em>";
          SesionCliente::valor('ERROR_BD', '');
        }

        if(!empty($mensaje)){
          $mensaje = "<em>".$mensaje."</em>";
        }

        $arrayRespuesta = array(
                'RESPUESTA' => $respuesta,
                'MENSAJE' => $mensaje,
                'DATOS' => $datos,
                'CODIGO' => $error,
                'ERROR' => $error
            );
        $jsonRespuesta = json_encode($arrayRespuesta);
        return $jsonRespuesta;
    }

    static public function exito($mensaje = null, $datos = null) {
        if( is_array($mensaje)  ){
            $tmp = $datos;
            $datos = $mensaje;
            $mensaje = $tmp;
        }

        if( is_object ($mensaje)  ){
            $array =  (array) $mensaje;
            $mensaje = "";
            $datos = $array;
        }

        return self::respuesta(self::EXITO, $mensaje, $datos );
    }

    static public function alerta($mensaje, $datos = null) {
        return self::respuesta(self::ALERTA, $mensaje, $datos );
    }

    static public function fallo($mensaje, $datos = null) {
        return self::respuesta(self::FALLO, $mensaje, $datos );
    }

    static public function error($mensaje, $codigo = null, $datos = null) {
        return self::respuesta(self::ERROR, $mensaje, $datos, $codigo );
    }

    public static function getRespuesta() {
        return self::$respuesta;
    }

    public static function getMensajeRespuesta() {
        return self::$mensaje;
    }

}
