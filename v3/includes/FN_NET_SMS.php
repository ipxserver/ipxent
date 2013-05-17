<?php
function FN_NET_SMS($vp_mensaje,$vp_msisdn,$vp_shortnumber = 0)
{
     global $vg_conf_logger_server,$vg_smpp_from,$vg_conf_logger_port,$vg_conf_logger_from,$vg_conf_debug_level,$vg_conf_logger_net;
     FN_NET_LOGGER("SMS > ingreso de sms: $vp_mensaje | from: $vp_shortnumber | msisdn: $vp_msisdn");
     $vl_shortnumber=0;

     if ($vp_shortnumber == 0)
     {
         $vl_shortnumber= $vg_smpp_from;
     }
     else
     {
         $vl_shortnumber=$vp_shortnumber;
     }
     global $vg_smpp_host ,$vg_smpp_port,$vg_smpp_from;
    //LOGUEA
    //$vf_resultado=false;
     //
     $vf_resultado="";
     
     /*
     $vf_mensaje ="GET /cgi?texto=$vp_mensaje&medio=$vg_conf_logger_from HTTP/1.0\n\n";
     $vf_resultado  = FN_NET_SOCKET($vg_conf_logger_server, $vg_conf_logger_port, $vf_mensaje,false);
     if ($vf_resultado <=  0)
     {
         FN_FILE_LOGGER($vp_mensaje);
     }
      *
      */
     $text   = urlencode($vp_mensaje);
     FN_NET_LOGGER("SMS >:http://$vg_smpp_host:$vg_smpp_port/cgi-bin/sendsms?username=foo&password=buz&to=$vp_msisdn&from=$vl_shortnumber&text=$text");

      $vl_tiempo_inicial= microtime();
     $vf_resultado=@file("http://$vg_smpp_host:$vg_smpp_port/cgi-bin/sendsms?username=foo&password=buz&to=$vp_msisdn&from=$vl_shortnumber&text=$text");
     $vl_tiempo_final= microtime();
     $vl_tiempo_resultado= ($vl_tiempo_final- $vl_tiempo_inicial);
     FN_NET_LOGGER("SMS >: Servidor responde:$vf_resultado > STATS > tiempo respuesta:$vl_tiempo_resultado ");
     return true;     
}

function FN_NET_SMS_2($vp_mensaje,$vp_msisdn,$vp_shortnumber)
{
     global $vg_conf_logger_server,$vg_smpp_from,$vg_conf_logger_port,$vg_conf_logger_from,$vg_conf_debug_level,$vg_conf_logger_net;
     FN_NET_LOGGER("SMS > ingreso de sms: $vp_mensaje | from: $vp_shortnumber | msisdn: $vp_msisdn");
     $vl_shortnumber=0;

	$vl_shortnumber= $vp_shortnumber;
     /*if ($vp_shortnumber == 0)
     {
         $vl_shortnumber= $vg_smpp_from;
     }
     else
     {
         $vl_shortnumber=$vp_shortnumber;
     }*/
     global $vg_smpp_host ,$vg_smpp_port,$vg_smpp_from;
    //LOGUEA
    //$vf_resultado=false;
     //
     $vf_resultado="";
     
     /*
     $vf_mensaje ="GET /cgi?texto=$vp_mensaje&medio=$vg_conf_logger_from HTTP/1.0\n\n";
     $vf_resultado  = FN_NET_SOCKET($vg_conf_logger_server, $vg_conf_logger_port, $vf_mensaje,false);
     if ($vf_resultado <=  0)
     {
         FN_FILE_LOGGER($vp_mensaje);
     }
      *
      */
     $text   = urlencode($vp_mensaje);
     FN_NET_LOGGER("SMS >:http://$vg_smpp_host:$vg_smpp_port/cgi-bin/sendsms?username=foo&password=buz&to=$vp_msisdn&from=$vl_shortnumber&text=$text");

      $vl_tiempo_inicial= microtime();
     $vf_resultado=@file("http://$vg_smpp_host:$vg_smpp_port/cgi-bin/sendsms?username=foo&password=buz&to=$vp_msisdn&from=$vl_shortnumber&text=$text");
     $vl_tiempo_final= microtime();
     $vl_tiempo_resultado= ($vl_tiempo_final- $vl_tiempo_inicial);
     FN_NET_LOGGER("SMS >: Servidor responde:$vf_resultado > STATS > tiempo respuesta:$vl_tiempo_resultado ");
     return true;     
}
?>

