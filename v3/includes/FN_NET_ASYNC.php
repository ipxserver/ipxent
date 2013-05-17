<?php
function FN_SEND_ASYNC($vp_msisdn,$vp_tono,$vp_reason,$vp_isquery,$msisdn_b = 0,$vp_promo = 0,$vp_paquete = 0)
{
    if ($vp_tono > 0)
    {
         global $vg_conf_async_server,$vg_conf_async_port,$vg_conf_async_from;
         $vf_simulador_async=false;
         if ($vf_simulador_async == false)
         {
             if (!($vp_tono >= 0))
             {
                return '-1';
             }


             if ($vp_promo > 0)
             {
                $vf_mensaje ="GET /cgi-bin/nose?msisdn=$vp_msisdn&tone=$vp_tono&reason=$vp_reason&isquery=$vp_isquery&from=$vg_conf_async_from&isfake=0&promo=$vp_promo&msisdnb=$msisdn_b HTTP/1.0\n\n";
             }
             elseif ($vp_paquete > 0)
             {
                 $vf_mensaje ="GET /cgi-bin/nose?msisdn=$vp_msisdn&tone=$vp_tono&reason=$vp_reason&isquery=$vp_isquery&from=$vg_conf_async_from&isfake=0&paquete=$vp_paquete&msisdnb=$msisdn_b HTTP/1.0\n\n";
             }
             else
             {
                $vf_mensaje ="GET /cgi-bin/nose?msisdn=$vp_msisdn&tone=$vp_tono&reason=$vp_reason&isquery=$vp_isquery&from=$vg_conf_async_from&isfake=0&msisdnb=$msisdn_b HTTP/1.0\n\n";
                //FN_NET_LOGGER("ASYNC -> $vf_mensaje ",5);
             }

             $vl_tiempo_inicial= microtime();

			if($vp_msisdn=="77800558" or $vp_msisdn=="77390660")
				$vf_resultado  = FN_NET_SOCKET($vg_conf_async_server, 3334, $vf_mensaje);
			else
             	$vf_resultado  = FN_NET_SOCKET($vg_conf_async_server, $vg_conf_async_port, $vf_mensaje);

             $vl_tiempo_final= microtime();
             $vl_tiempo_resultado= ($vl_tiempo_final- $vl_tiempo_inicial);
             FN_NET_LOGGER("ASYNC -> STATS> Tiempo respuesta $vl_tiempo_resultado seg.",5);
         }
         else
         {
             if($vp_isquery == 1)
             {
                 if (($vp_reason >=2) || ($vp_reason <= 10))
                 {
                    $rand = rand(0,10);
                    if ($rand >=5)
                    {
                        return "350";
                    }
                    elseif ($rand >=2)
                    {
                        return "250";
                    }

                    else
                    {
                        return "-1";
                    }
                 }

             }
             else
             {
                 $rand = rand(0,10);

                    if ($rand < 1)
                    {
                        return "1060";
                    }
                    elseif ($rand < 2)
                    {
                        return "-1";
                    }
                    else
                    {
                        return "0";
                    }
             }
         }
         return $vf_resultado;
    }
    else
    {
        FN_NET_LOGGER("ASYNC -> ERROR TONO INVALIDO: $vp_tono");
    }
}

?>
