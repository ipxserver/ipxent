<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function FN_NET_SOCKET($vp_server,$vp_port,$vp_mensaje,$vp_orientado_conexion=true,$vp_buffer=512)
{

    //OPEN CONECTION
    $vf_socket          = null;
    $vf_socket_timeout  = 2;
    $vf_mensaje_error   = "";
    $vf_respuesta_mensaje = "";
    //echo "<BR> FN_NET_SOCKET: CONECTANDO CON $vp_server $vp_port [$vp_mensaje]";
    // open a socket
    if(!$vf_socket_timeout)
    {
        // without timeout

        $vf_socket = fsockopen($vp_server, $vp_port);

    }
    else
    {
        // with timeout
        $vf_socket = fsockopen($vp_server, $vp_port, $errno, $errstr, $vf_socket_timeout);
    }
    if(!$vf_socket )
    {
        $vf_mensaje_error= "-1";
        //fclose($vf_socket);
        return $vf_mensaje_error;
    }
    else
    {
            //ENVIAR MENSAJE AL SOCKET
            $vf_respuesta_mensaje      ="";
            $tmp = fwrite($vf_socket,$vp_mensaje);
            if ($tmp > 0)
            {
                if ($vp_orientado_conexion == true)
                {
                     while(!feof($vf_socket))
                     {
                         $vf_respuesta_mensaje .= fread($vf_socket, $vp_buffer);
                         if(preg_match("/\n/",$vf_respuesta_mensaje )) break;
                     }
                    //$respuesta_mensaje= fread($vf_socket,8192);
                    fclose($vf_socket);
                    return $vf_respuesta_mensaje ;
                }
                else
                {
                    fclose($vf_socket);
                    return $tmp;
                }
            }
            else
            {
                return -1;
            }
    }
    fclose($vf_socket);
}


?>
