<?php
function FN_NET_LOGGER($vp_mensaje, $vp_debug_level =0, $vp_criticidad="")
{
     /*
      * NIVELES DE DEBUG
      * ----
      * 0 BASICO
      * 1 MEDIO
      * 2 ALTO
      * 3 MUY ALTO
      * 4
      * 5
      *
      * CRITICIDAD
      * -----------
      * WARN -> ERRORES DE ADVERTENCIA
      *
      */
     global $vg_conf_logger_server,$vg_conf_logger_port,$vg_conf_logger_from,$vg_conf_debug_level,$vg_conf_logger_net;
     $vf_resultado=false;
     if ($vg_conf_logger_net == true)
     {
         if ($vp_debug_level <= $vg_conf_debug_level )
         {
            //LOGUEA
             //
             $vf_mensaje ="GET /cgi?texto=$vp_mensaje&medio=$vg_conf_logger_from HTTP/1.0\n\n";
             $vf_resultado  = FN_NET_SOCKET($vg_conf_logger_server, $vg_conf_logger_port, $vf_mensaje,false);
             if ($vf_resultado <=  0)
             {
                 FN_FILE_LOGGER($vp_mensaje);
             }
         }
         return $vf_resultado;
     }
     else
     {
         FN_FILE_LOGGER($vp_mensaje);
     }
}

function FN_FILE_LOGGER($vp_mensaje)
{
    //VERIFICA LA FECHA DEL DÍA
    $vf_fecha=date("m-d-y");
    //ARMA LA ESTAMPA DE TIEMPO
    //$vf_estampa_tiempo=date('l jS \of F Y h:i:s A');
    $vf_estampa_tiempo=date("D M j G:i:s T Y");
    //INGRESA EL LOG
    $vf_nombre_archivo = "WEB_RBT_V3_".$vf_fecha.".log";
    
    $vf_file_handler = fopen("logs/$vf_nombre_archivo", 'a');

    fwrite($vf_file_handler, $vf_estampa_tiempo.": ".$vp_mensaje."\n");
    fclose($vf_file_handler);
}

?>
