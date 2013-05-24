<?php
class CLASS_OPERACIONES_BACKTONES
{
    private $vlc_codigo_html;
    private $vlc_id_tono;
    private $vlc_msisdn;
    private $vlc_simbolo_moneda;
    private $vlc_mensaje;
    

    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_id_tono="";
        $this->vlc_msisdn="";
        $this->vlc_mensaje="";
        global $vg_conf_simbolo_moneda;
        $this->vlc_simbolo_moneda=$vg_conf_simbolo_moneda;
        $this->vlc_msisdn = $_SESSION['msisdn'];
    
    }

    function MTD_ASIGNAR_TONO ($vp_id_tono)
    {
        $this->vlc_id_tono= $vp_id_tono;
    }

    function MTD_ASIGNAR_MSISDN($vp_msisdn)
    {
        //$this->vlc_msisdn= $vp_msisdn;
        if (isset($_SESSION['msisdn']))
        {
            $this->vlc_msisdn= $_SESSION['msisdn'];
        }
        else
        {
            $this->vlc_msisdn= "";
        }

    }
    function MTD_CONSULTAR_PRECIO($vp_reason)
    {
        global $vg_conf_feature_advertisement;
        $vf_reason=$vp_reason;
        $vf_isquery=1;
        if ($vf_reason == 2)
        {
            if ($vg_conf_feature_advertisement == true)
            {
                //VERIFICAR SI EL TONO ES ADVERTISEMENT
                if ($this->MTD_CHECK_ADVERTISEMENT() == true)
                {
                    $vf_reason=14;
                }
                else
                {
                    $vf_reason=2;
                }
            }
            else
            {
                $vf_reason=2;
            }
        }
        $vf_precio= FN_SEND_ASYNC($this->vlc_msisdn,$this->vlc_id_tono,$vf_reason,$vf_isquery);
        if ($vf_precio >= 0)
        {
            FN_NET_LOGGER("Consulta de precio de reason:$vp_reason - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono. " Async responde:$vf_precio ", 0);
            $vf_precio = $vf_precio /100;

            //IMPRIMIR EL PRECIO A ENVIARLO POR AJAX
            //902-498
            //0981 490 556
            return $vf_precio;
        }
        else
        {
            FN_NET_LOGGER("Error al consultar precio de renovacion - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." - Async responde ".$vf_precio, 0);
            return "--";
        }
    }
    
    function MTD_REGALAR_BACKTONES($vp_ani, $vp_id_tono)
    {
        //INICIALIZAR VARIABLES
        include_once("class/CLASS_USUARIOS_BACKTONES.php");
        global $vg_configuracion_regional;
        $vl_contador = 0;
        $vl_resultado_regalo=-1;
        $vl_ani_posee_tono =false;
        $vl_arreglo_datos = array();
        $vl_arreglo_tonos = array();
        $vl_cantidad_regitros=0;
        $vl_mensaje_sms="";

        FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono");
        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES;

        //asigna el msisdn del usuario que recibe el regalo
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($vp_ani);

        //VALIDA EL MSISDN
        if ($obj_usuarios_backtones->MTD_VALIDAR_MSISDN($vp_ani) == false)
        {
            FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Error al validar el ani");
            return "<div style='width:400px; height:50px;'> <span style='color:gray; font-size:10px;'><b>Atenci&oacute;n</b>!!<br> El numero ingresado no es valido </span> </div>";
        }

        //VERIFICAR QUE NO TENGA EL TONO
        $vl_arreglo_tonos = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS();
        $vl_cantidad_registros = sizeof($vl_arreglo_tonos);
        FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Verificando si el usuario no posee el tono");
        $vl_contador=0;
        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_arreglo_tonos[$vl_contador][0] == $vp_id_tono)
            {
                FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Error el destinatario ya posee el tono");
                $vl_ani_posee_tono =true;
            }
            $vl_contador++;
        }

        if ($vl_ani_posee_tono == false)
        {
            //----------------->REALIZAR LA COMPRA -->
            $vf_reason=3;
            $vf_isquery=0;

            $vl_resultado_regalo= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_tono,$vf_reason,$vf_isquery,$vp_ani);
            FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Async responde:$vl_resultado_regalo ");
            $vl_resultado_regalo == 0;
            if ($vl_resultado_regalo == 0)
            {
                //REGALO EXITOSO
                /*
                if ($vg_configuracion_regional == "BO")
                {
                    $obj_usuarios_backtones->MTD_REGISTRAR_USUARIO();
                    //ASIGNAR BACKTONE
                    FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Agregando backtone al usuario ");
                    $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS($vp_id_tono);
					$vl_mensaje_sms= "El usuario ".$this->vlc_msisdn." te ha regalado un backtone. Si deseas escucharlo, llama al *1111";
                    #$vl_mensaje_sms= "El usuario ".$this->vlc_msisdn." te ha regalado un backtone. Si deseas escucharlo, envia 'escuchar $vp_id_tono' al 1111";
                        
                    // REASON 4? > REGALO 
                    $vf_reason=4;
                    $vf_isquery=0;

                    $vl_resultado_regalo= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_tono,$vf_reason,$vf_isquery);
                    FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Async responde:$vl_resultado_regalo ");

                    $vl_mensaje_sms= "El usuario ".$this->vlc_msisdn." te ha regalado un backtone. Si deseas escucharlo, envia 'escuchar $vp_id_tono' al 1111";
                    FN_NET_SMS($vl_mensaje_sms, $vp_ani);
                    return $this->MTD_APLICAR_TEMPLATE_POPUP_REGALO($vp_ani,'regalar');
                }
                else
                {
                 *
                 */
                    //giftautune
                    $obj_usuarios_backtones->MTD_REGISTRAR_USUARIO();
                    //ASIGNAR BACKTONE
                   // FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Agregando backtone al usuario ");
                   // $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS($vp_id_tono);

                    //REGISTRANDO REGALO
                    //$vf_reason=3;
                    //$vf_isquery=0;
                   // $vl_resultado_regalo= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_tono,$vf_reason,$vf_isquery);
                    //FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Async responde:$vl_resultado_regalo ");

                    include('class/CLASS_GIFT_A_TUNE.php');
                    $obj_giftatune= new CLASS_GIFT_A_TUNE();
                    $obj_giftatune->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
                    $obj_giftatune->MTD_ASIGNAR_ANI($vp_ani);
                    $obj_giftatune->MTD_ASIGNAR_TONO($vp_id_tono);
                    $vl_resultado == false;
                    $vl_resultado = $obj_giftatune->MTD_REGALAR_TONO();
                    if ($vl_resultado == 'tono-regalado')
                    {
                         return $this->MTD_APLICAR_TEMPLATE_POPUP_REGALO($vp_ani,'regalar');
                    }
                    else
                    {
                        return "0";
                    }

               // }
                
           }
            else
            {
                  return $this->MTD_RETORNOS_RESPUESTA_ASYNC($vl_resultado_regalo);
            }
       }
       else
       {
           FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > el usuario ya posee el tono");
          return "-4";//ya posee el backtone
       }
       return true;
    }
    function MTD_PEDIR_BACKTONES($vp_ani, $vp_id_tono)
    {
        //INICIALIZAR VARIABLES
        include_once("class/CLASS_USUARIOS_BACKTONES.php");
        global $vg_configuracion_regional;
        $vl_contador = 0;
        $vl_resultado_regalo=-1;
        $vl_ani_posee_tono =false;
        $vl_arreglo_datos = array();
        $vl_arreglo_tonos = array();
        $vl_cantidad_regitros=0;
        $vl_mensaje_sms="";

        FN_NET_LOGGER("MTD_PEDIR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono");
        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES;

        //asigna el msisdn del usuario que recibe el regalo
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);

        //VALIDA EL MSISDN
        if ($obj_usuarios_backtones->MTD_VALIDAR_MSISDN($vp_ani) == false)
        {
            FN_NET_LOGGER("MTD_PEDIR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Error al validar el ani");
            return "<div style='width:400px; height:50px;'> <span style='color:gray; font-size:10px;'><b>Atenci&oacute;n</b>!!<br> El numero ingresado no es valido </span> </div>";
        }

        //VERIFICAR QUE NO TENGA EL TONO
        $vl_arreglo_tonos = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS();
        $vl_cantidad_registros = sizeof($vl_arreglo_tonos);
        FN_NET_LOGGER("MTD_PEDIR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Verificando si el usuario no posee el tono");
        $vl_contador=0;

        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_arreglo_tonos[$vl_contador][0] == $vp_id_tono)
            {
                FN_NET_LOGGER("MTD_PEDIR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono = ".$vl_arreglo_tonos[$vl_contador][0]." > Error el destinatario ya posee el tono");
                $vl_ani_posee_tono =true;
            }
            $vl_contador++;
        }

        if ($vl_ani_posee_tono == false)
        {
            //----------------->REALIZAR LA COMPRA -->
            $vf_reason=3;
            $vf_isquery=0;           
            //giftautune -> pedir
            include('class/CLASS_GIFT_A_TUNE.php');
            $obj_giftatune= new CLASS_GIFT_A_TUNE();

            $obj_giftatune->MTD_ASIGNAR_MSISDN($vp_ani);//--------->SE INVIERTE LA ASIGNACION PARA PEDIR
            $obj_giftatune->MTD_ASIGNAR_ANI($this->vlc_msisdn);//-->SE INVIERTE LA ASIGNACION PARA PEDIR
            $obj_giftatune->MTD_ASIGNAR_TONO($vp_id_tono);
            $vl_resultado == false;
            $vl_resultado = $obj_giftatune->MTD_PEDIR_TONO();
            if ($vl_resultado == 'tono-regalado')
            {
                 return $this->MTD_APLICAR_TEMPLATE_POPUP_REGALO($vp_ani,'pedir');
            }
            else
            {
                return "0";
            }
       }
       else
       {
          FN_NET_LOGGER("MTD_PEDIR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > el usuario ya posee el tono");
          return "-4";//ya posee el backtone
       }
       return true;
    }
   
    function MTD_CHECK_ADVERTISEMENT()
    {
        global $vg_db_conexion, $vg_conf_filtro_web;
        $vf_arreglo_tonos= array();
        $vf_sql="SELECT	ad.id, ad.peso, ad.id_tono, ad.tiempo_vida, t.interfases, t.nombre
        FROM 	advertisement as ad, tonos as t
        WHERE 	ad.id_tono = t.id
	and t.id = $this->vlc_id_tono
	and t.categoria = 200
	and t.autor = 1100
	$vg_conf_filtro_web; ";
        $vf_arreglo_tonos = FN_RUN_QUERY($vf_sql, 6,$vg_db_conexion);

        if ($vf_arreglo_tonos[0][2] == $this->vlc_id_tono)
        {
            FN_NET_LOGGER("CHECK_ADVERTISEMENT > El tono es advertisement ".$vf_arreglo_tonos[0][2]);
            return true;
        }
        else
        {
            FN_NET_LOGGER("CHECK_ADVERTISEMENT > El tono no es advertisement ".$vf_arreglo_tonos[0][2]);
            return false;
        }
    }
    function MTD_COMPRAR_BACKTONES()
    {
        global $vg_conf_feature_advertisement;

        if ($vg_conf_feature_advertisement == true)
        {
            //VERIFICAR SI EL TONO ES ADVERTISEMENT
            if ($this->MTD_CHECK_ADVERTISEMENT() == true)
            {
                $vf_reason=14;
            }
            else
            {
                $vf_reason=2;
            }
        }
        else
        {
            $vf_reason=2;
        }

        $vf_isquery=0;
        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        //ENVIAR A ASYNC MULTI LA COMPRA
        FN_NET_LOGGER("Iniciando compra - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono, 0);
        FN_NET_LOGGER("Envio a ASYNC - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono, 2);

        $vf_resultado_compra=-1;
        $vf_resultado_compra= FN_SEND_ASYNC($this->vlc_msisdn,$this->vlc_id_tono,$vf_reason,$vf_isquery);


        // crear metodo procesar respuesta async.
        FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde:".$vf_resultado_compra);
        if (trim($vf_resultado_compra) == "0")
        {
            include_once 'CLASS_USUARIOS_BACKTONES.php';
            FN_NET_LOGGER("Compra exitosa de backtone - ingresando el backtone al usuario  msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono."", 0);
            $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
            $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
            $vf_resultado_agregar_tono=false;
			
			$vf_dias_renovacion = $obj_usuarios_backtones->MTD_DB_GET_DIAS_RENOVACION($this->vlc_id_tono);
            $vf_resultado_agregar_tono=$obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS($this->vlc_id_tono);
            if ($vf_resultado_agregar_tono == true)
            {
			##	 $vf_dias_renovacion = $obj_usuarios_backtones->MTD_DB_GET_DIAS_RENOVACION($this->vlc_id_tono);
                 $arreglo_tono = $this->MTD_DB_LISTAR_TONO();
                 $vf_mensaje_sms="Compraste el tono ".$arreglo_tono[0][1]." - ".$arreglo_tono[0][2]." que estara activo por ".$vf_dias_renovacion." dias.Costo mensual de bs 3,5. renovacion automatica.";
                 FN_NET_SMS($vf_mensaje_sms, $this->vlc_msisdn);
                 FN_NET_LOGGER("Compra exitosa de backtone - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Async responde:".$vf_resultado_compra, 0);
                 $vf_mensaje_sms="Para eliminar tu backtone llama al *1111 ingresando a la opcion 4 o envia un SMS con la palabra 'SALIR' al 1111";
                 FN_NET_SMS($vf_mensaje_sms, $this->vlc_msisdn);
                 $this->vlc_mensaje= $obj_usuarios_backtones->MTD_RETORNAR_MENSAJE();
                 FN_NET_LOGGER(" R: template");
                 return  $this->MTD_APLICAR_TEMPLATE_POPUP_COMPRA();
            }
            else
            {
                FN_NET_LOGGER("Fallo de asignacion de backtone - msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Async responde:".$vf_resultado_compra, 0,"ERR-");
                //$this->vlc_mensaje= $obj_usuarios_backtones->MTD_RETORNAR_MENSAJE();
                FN_NET_LOGGER(" R: -4");
                return "-4";
            }
        }
        else
        {
            return $this->MTD_RETORNOS_RESPUESTA_ASYNC($vf_resultado_compra);
        }

        return true;
    }

	//-----NUEVA FUNCION DE DESCARGA
	function MTD_DESCARGAR_BACKTONES()
    {
        global $vg_smpp_port_wap, $vg_smpp_host, $vg_db_conexion;

        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        //REUTILIZACION DE CODIGO DE V2
        $fp = fsockopen($vg_smpp_host, $vg_smpp_port_wap, $errno, $errstr);
        if (!$fp)
        {
            FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Error de conexion con el servicio host:$vg_smpp_host puerto:$vg_smpp_port_wap", 2);
            return "-1";
        }
        //ENVIAR A ASYNC MULTI LA COMPRA DE DESCARGA
        //$vf_reason=5;
        //$vf_isquery=0;
        //FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Envio a Async", 2);

        //$vf_resultado_compra=-1;
        //$vf_resultado_compra= FN_SEND_ASYNC($this->vlc_msisdn,$this->vlc_id_tono,$vf_reason,$vf_isquery);
       $vf_resultado_compra=0;

        //        FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Async Responde:$vf_resultado_compra ", 2);
        if ($vf_resultado_compra == 0)
        {

            $vl_arreglo_datos= array();
            $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
            $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];
			$query = "DELETE FROM ringtones WHERE msisdn='".$this->vlc_msisdn."' and tono=".$this->vlc_id_tono."";
            $vl_resultado =false;
            $vl_resultado =FN_RUN_NONQUERY($query,$vg_db_conexion);

            $query = "INSERT INTO ringtones (msisdn,tono,fecha) VALUES ('".$this->vlc_msisdn."',$this->vlc_id_tono,unix_timestamp())";
            $vl_resultado =false;
            $vl_resultado =FN_RUN_NONQUERY($query,$vg_db_conexion);

            $codigo = FN_DB_ULTIMO_REGISTRO();
            FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Id:$codigo ");
            if ($vg_configuracion_regional == 'PY')
            {
                $this->vlc_msisdn = preg_replace('/^098/','59598',$this->vlc_msisdn);
            }
            elseif($vg_configuracion_regional == 'BO')
            {
                $this->vlc_msisdn = preg_replace('/^7/','5917',$this->vlc_msisdn);
            }

        $url = "http://backtones.tigo.com.bo/bajar.php?msisdn=".$this->vlc_msisdn."&amp;id=".$codigo;

            $content = "\r\n\r\n";
            $content.= "--asdlfkjiurwghasf\r\n";
            $content.= "Content-Type: application/xml\r\n\r\n";
            $content.= "<?xml version=\"1.0\"?>
            <!DOCTYPE pap PUBLIC \"-//WAPFORUM//DTD PAP//EN\"
                                 \"http://www.wapforum.org/DTD/pap_1.0.dtd\">
               <pap>
              <push-message push-id=\"9fjeo39jf084@pi.com\"
              progress-notes-requested=\"false\">
                <address address-value=\"WAPPUSH=+".$this->vlc_msisdn."/TYPE=PLMN@tigo.com.bo\">
                </address>
                <quality-of-service
                priority=\"low\"
                delivery-method=\"unconfirmed\"
                network-required=\"true\"
                network=\"gsm\"
 bearer-required=\"true\"
                bearer=\"sms\">
                </quality-of-service>
              </push-message>
            </pap>\r\n\r\n";
            $content.= "--asdlfkjiurwghasf\r\n";
            $content.= "Content-Type: text/vnd.wap.si\r\n\r\n";
            $content.= "<?xml version=\"1.0\"?>
            <!DOCTYPE si PUBLIC \"-//WAPFORUM//DTD SI 1.0//EN\"
                                \"http://www.wapforum.org/DTD/si.dtd\">
         <si>
         <indication href=\"$url\" action=\"signal-high\">
                 $vl_nombre_tono
            Te quedan 3 intentos para realizar la descarga, el precio por cada descarga es 3 Bs. la descarga estara habilitada por 4 horas, para consultas llamar al *611
         </indication>
         </si>\r\n\r\n";

            $content .= "--asdlfkjiurwghasf--\r\n";

            $headers = "POST /wappush HTTP/1.1\r\n";
            $headers.= "User-Agent: SS7Solutions/1.1\r\n";
            $headers.= "Content-Type: multipart/related; boundary=asdlfkjiurwghasf; type=\"application/xml\";\r\n";
            $headers.= "Content-Length: ".strlen($content)."\r\n\r\n";

            fputs($fp,$headers,strlen($headers));
            fputs($fp,$content,strlen($content));
            FN_NET_LOGGER("DESCARGA BACKTONE> Envio de MSG a WAPPUSH > msisdn: ".$this->vlc_msisdn);
            FN_NET_LOGGER("DESCARGA BACKTONE> PUSH MSG > URL: $url ");

            return $this->MTD_APLICAR_TEMPLATE_POPUP_DESCARGA($this->vlc_msisdn,'descargar');

        }
 else
        {
            FN_NET_LOGGER("DESCARGA BACKTONE> fallo de compra> msisdn: ".$this->vlc_msisdn);
            return $this->MTD_RETORNOS_RESPUESTA_ASYNC($vf_resultado_compra);
        }

    }








    function MTD_DESCARGAR_BACKTONES_OLD()
    {
        global $vg_smpp_port_wap, $vg_smpp_host, $vg_db_conexion;
        
        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        //REUTILIZACION DE CODIGO DE V2
        $fp = fsockopen($vg_smpp_host, $vg_smpp_port_wap, $errno, $errstr);
        if (!$fp)
        {
            FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Error de conexion con el servicio host:$vg_smpp_host puerto:$vg_smpp_port_wap", 2);
            return "-1";
        }
        //ENVIAR A ASYNC MULTI LA COMPRA DE DESCARGA
        $vf_reason=5;
        $vf_isquery=0;
        FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Envio a Async", 2);

        $vf_resultado_compra=-1;
        $vf_resultado_compra= FN_SEND_ASYNC($this->vlc_msisdn,$this->vlc_id_tono,$vf_reason,$vf_isquery);

        FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Async Responde:$vf_resultado_compra ", 2);
        if ($vf_resultado_compra == 0)
        {

            $vl_arreglo_datos= array();
            $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
            $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];



            $query = "DELETE FROM ringtones WHERE msisdn='".$this->vlc_msisdn."' and tono=".$this->vlc_id_tono."";
            $vl_resultado =false;
            $vl_resultado =FN_RUN_NONQUERY($query,$vg_db_conexion);

            $query = "INSERT INTO ringtones (msisdn,tono,fecha) VALUES ('".$this->vlc_msisdn."',$this->vlc_id_tono,unix_timestamp())";
            $vl_resultado =false;
            $vl_resultado =FN_RUN_NONQUERY($query,$vg_db_conexion);

            $codigo = FN_DB_ULTIMO_REGISTRO();
            FN_NET_LOGGER("MTD_DESCARGAR_BACKTONES msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Id:$codigo ");
            if ($vg_configuracion_regional == 'PY')
            {
                $this->vlc_msisdn = preg_replace('/^098/','59598',$this->vlc_msisdn);
            }
            elseif($vg_configuracion_regional == 'BO')
            {
                $this->vlc_msisdn = preg_replace('/^7/','5917',$this->vlc_msisdn);
            }

	    $url = "http://backtones.tigo.com.bo/bajar.php?msisdn=".$this->vlc_msisdn."&amp;id=".$codigo;

            $content = "\r\n\r\n";
            $content.= "--asdlfkjiurwghasf\r\n";
            $content.= "Content-Type: application/xml\r\n\r\n";
            $content.= "<?xml version=\"1.0\"?>
            <!DOCTYPE pap PUBLIC \"-//WAPFORUM//DTD PAP//EN\"
                                 \"http://www.wapforum.org/DTD/pap_1.0.dtd\">
               <pap>
              <push-message push-id=\"9fjeo39jf084@pi.com\"
              progress-notes-requested=\"false\">
                <address address-value=\"WAPPUSH=+".$this->vlc_msisdn."/TYPE=PLMN@tigo.com.py\">
                </address>
                <quality-of-service
                priority=\"low\"
                delivery-method=\"unconfirmed\"
                network-required=\"true\"
                network=\"gsm\"
                bearer-required=\"true\"
                bearer=\"sms\">
                </quality-of-service>
              </push-message>
            </pap>\r\n\r\n";
            $content.= "--asdlfkjiurwghasf\r\n";
            $content.= "Content-Type: text/vnd.wap.si\r\n\r\n";
            $content.= "<?xml version=\"1.0\"?>
            <!DOCTYPE si PUBLIC \"-//WAPFORUM//DTD SI 1.0//EN\"
                                \"http://www.wapforum.org/DTD/si.dtd\">
         <si>
         <indication href=\"$url\" action=\"signal-high\">
                 $vl_nombre_tono
         </indication>
         </si>\r\n\r\n";

            $content .= "--asdlfkjiurwghasf--\r\n";

            $headers = "POST /wappush HTTP/1.1\r\n";
            $headers.= "User-Agent: SS7Solutions/1.1\r\n";
            $headers.= "Content-Type: multipart/related; boundary=asdlfkjiurwghasf; type=\"application/xml\";\r\n";
            $headers.= "Content-Length: ".strlen($content)."\r\n\r\n";

            fputs($fp,$headers,strlen($headers));
            fputs($fp,$content,strlen($content));
            FN_NET_LOGGER("DESCARGA BACKTONE> Envio de MSG a WAPPUSH > msisdn: ".$this->vlc_msisdn);
            return $this->MTD_APLICAR_TEMPLATE_POPUP_DESCARGA($this->vlc_msisdn,'descargar');

        }
        else
        {
            FN_NET_LOGGER("DESCARGA BACKTONE> fallo de compra> msisdn: ".$this->vlc_msisdn);
            return $this->MTD_RETORNOS_RESPUESTA_ASYNC($vf_resultado_compra);
        }
        
    }
    function MTD_RETORNOS_RESPUESTA_ASYNC($vp_respuestas)
    {
        
        switch ($vp_respuestas)
        {
                case "1060" :
                    FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde: ".$vf_resultado_compra." Sin Saldo",0);
                    FN_NET_LOGGER(" R: -3");
                    return "-3";
                break;
                case "-1" :
                    FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde: ".$vf_resultado_compra." Fuera de Servicio",0);
                    FN_NET_LOGGER(" R: -1");
                    return "-1";
                break;
                case "-16" :
                    FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde: ".$vf_resultado_compra." Fuera de Servicio",0);
                    FN_NET_LOGGER(" R: -1");
                    return "-4";
                break;
                case "-16" :
                    FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde: ".$vf_resultado_compra." Fuera de Servicio",0);
                    FN_NET_LOGGER(" R: -1");
                    return "-4";
                break;
                default :
                    FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono.".Async responde: ".$vf_resultado_compra,0);
                    FN_NET_LOGGER(" R: -1");
                    return "-1";
                break;
       }
    }
    function MTD_DB_LISTAR_AMIGOS()
    {
        global $vg_db_conexion;
        $vlf_sql = "
            SELECT
                a.msisdn,
                a.ani,
                a.tonos,
                a.modo,
                a.param,
                a.nombre,
                dp.imagen,
                dp.nombre,
                dp.apellido
                FROM asignados as a
                left join usuarios_datos_personales as dp ON (a.ani = dp.msisdn)

                WHERE
                a.msisdn='".$this->vlc_msisdn."';";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 9,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_LISTA_AMIGOS()
    {
        $vl_arreglo_datos = array();
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_AMIGOS();
        $vl_cantidad_registros= sizeof($vl_arreglo_datos);
        $vl_contador=0;
        $vl_template="<ul>";
        $vl_nombre_amigo;
        $vl_numero_amigo;
        $vl_imagen_amigo;
        $vl_nombre_real;
        $vl_opciones="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_numero_amigo = $vl_arreglo_datos[$vl_contador][1];
            $vl_nombre_amigo = $vl_arreglo_datos[$vl_contador][5];
            $vl_imagen_amigo = $vl_arreglo_datos[$vl_contador][6];
            $vl_nombre_real  = $vl_arreglo_datos[$vl_contador][7] . " ".$vl_arreglo_datos[$vl_contador][8];

            if ($vl_imagen_amigo == "")
            {
                $vl_imagen_amigo="imagenes/usuarios/imagen-usuarios-default.png";
            }
            else
            {
                $vl_imagen_amigo="imagenes/usuarios/".$vl_imagen_amigo;
            }


            $vl_template.="<li id='lista-de-amigos2' style='width:100%;height:50px;vetical-align:middle; border:0.1em dotted #eeeeee;'>\n";
            $vl_template.="<a href='#' onclick=javascript:{tpl-metodo}('".$vl_numero_amigo."','$this->vlc_id_tono')>\n";
            $vl_template.="<img src='$vl_imagen_amigo' height='45' border='0' style='float:left;'/></a>\n";
            $vl_template.="<a href='#' onclick=javascript:{tpl-metodo}('".$vl_numero_amigo."','$this->vlc_id_tono')>\n";
            if ($vl_nombre_real != " ")
            {
                $vl_template.=" [$vl_numero_amigo] - $vl_nombre_amigo - ($vl_nombre_real) </a></li>\n";
            }
            else
            {
                $vl_template.=" [$vl_numero_amigo] - $vl_nombre_amigo  </a></li>\n";
            }

            
            $vl_contador++;
        }
        $vl_template.="</ul>";
        /*
                            <ul>
                            </ul>

         *
         */
        return $vl_template;
    }
    function MTD_APLICAR_TEMPLATE_POPUP_LISTA_AMIGOS($vp_metodo)
    {
        $vp_arreglo_datos= array();
        FN_NET_LOGGER("APLICAR TEMPLATE REGALO: tono:".$this->vlc_id_tono. " Metodo:$vp_metodo ",5);
        $vp_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
        $vl_template_tonos= "";
        $id_tono=0;
        $tono="";
        $autor="";
        $imagen_album="";        
        //ASIGNACION DE VARIABLES
        $vl_template_tonos= "";
        $id_tono=$vp_arreglo_datos[0][0];
        $tono=ucfirst(strtolower(trim($vp_arreglo_datos[0][1])));
        $titulo_tono=$tono;

        $autor=ucfirst(strtolower(trim($vp_arreglo_datos[0][2])));
        $titulo_autor=$autor;

        $imagen_album=$vp_arreglo_datos[0][3];
        if ($imagen_album == "")
        {
                $imagen_album="th-album-default.jpg";
        }
        $link_tono="index.php?seccion=backtones&backtone=$id_tono";
        $lista_amigos="";
        $lista_amigos=$this->MTD_LISTA_AMIGOS();
        //$texto="BackTones Tigo: El backtone se renovara automaticamente el 18/19/2010 ";
        $texto=$this->vlc_mensaje;
        $vl_template = FN_LEER_TPL('tpl/tpl-popup-regalar-backtones.html');
        $vl_template = FN_REEMPLAZAR('{tpl-texto-renovacion}', $texto, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-imagen-backtone}', $imagen_album, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-tono}', $tono, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-autor}', $autor, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-lista-amigos}', $lista_amigos, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-titulo-metodo}', $vp_metodo, $vl_template);
        FN_NET_LOGGER("APLICAR TEMPLATE REGALO: tono:".$this->vlc_id_tono." Metodo ingresado: $vp_metodo",5);
        if ($vp_metodo == 'regalar')
        {
            FN_NET_LOGGER("APLICAR TEMPLATE REGALO: tono:".$this->vlc_id_tono." Metodo regalo",5);
            $vl_template = FN_REEMPLAZAR('{tpl-metodo}', 'MTD_REGALAR_BACKTONE', $vl_template);
        }
        elseif ($vp_metodo == 'pedir')
        {
            FN_NET_LOGGER("APLICAR TEMPLATE REGALO: tono:".$this->vlc_id_tono." Metodo regalo",5);
            $vl_template = FN_REEMPLAZAR('{tpl-metodo}', 'MTD_PEDIR_BACKTONE', $vl_template);
        }


        //FN_NET_LOGGER("APLICAR TEMPLATE regalo: $vl_template",5);
        return $vl_template;

    }
    function MTD_APLICAR_TEMPLATE_POPUP_COMPRA()
    {
        global $vg_conf_dominio;
        $vp_arreglo_datos= array();
        FN_NET_LOGGER("APLICAR TEMPLATE COMPRA: tono:".$this->vlc_id_tono,5);
        $vp_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
        $vl_template_tonos= "";
        $id_tono=0;
        $tono="";
        $autor="";
        $imagen_album="";        
        //ASIGNACION DE VARIABLES
        $vl_template_tonos= "";
        $id_tono=$vp_arreglo_datos[0][0];
        $tono=ucfirst(strtolower(trim($vp_arreglo_datos[0][1])));
        $titulo_tono=$tono;

        $autor=ucfirst(strtolower(trim($vp_arreglo_datos[0][2])));
        $titulo_autor=$autor;

        $imagen_album=$vp_arreglo_datos[0][3];
        if ($imagen_album == "")
        {
                $imagen_album="th-album-default.jpg";
        }
        $link_tono="index.php?seccion=backtones&backtone=$id_tono";

        $url=urlencode("$vg_conf_dominio/v3/index.php?seccion=backtones&backtone=$id_tono");
        $vl_titulo_facebook="Ha comprado el backtone Tigo  $autor - $tono  ";        

        //$texto="BackTones Tigo: El backtone se renovara automaticamente el 18/19/2010 ";
        $texto=$this->vlc_mensaje;
        $vl_template = FN_LEER_TPL('tpl/tpl-popup-compra-backtone.html');
        $vl_template = FN_REEMPLAZAR('{tpl-texto-renovacion}', $texto, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-imagen-backtone}', $imagen_album, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-tono}', $tono, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-autor}', $autor, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-url}',$url, $vl_template );
        $vl_template = FN_REEMPLAZAR('{tpl-titulo-url}',$vl_titulo_facebook, $vl_template );
        
        //FN_NET_LOGGER("APLICAR TEMPLATE COMPRA: $vl_template",5);
        return $vl_template;
    }
    function MTD_APLICAR_TEMPLATE_POPUP_REGALO($vp_ani,$vp_metodo)
    {
        $vp_arreglo_datos= array();
        FN_NET_LOGGER("APLICAR TEMPLATE REGALO: ani :$vp_ani",5);
        $vp_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
        $vl_template_tonos= "";
        $id_tono=0;
        $tono="";
        $autor="";
        $imagen_album="";
        //ASIGNACION DE VARIABLES
        $vl_template_tonos= "";
        $id_tono=$vp_arreglo_datos[0][0];
        $tono=ucfirst(strtolower(trim($vp_arreglo_datos[0][1])));
        $titulo_tono=$tono;

        $autor=ucfirst(strtolower(trim($vp_arreglo_datos[0][2])));
        $titulo_autor=$autor;

        $imagen_album=$vp_arreglo_datos[0][3];
        if ($imagen_album == "")
        {
                $imagen_album="th-album-default.jpg";
        }
        $link_tono="index.php?seccion=backtones&backtone=$id_tono";

        //$texto="BackTones Tigo: El backtone se renovara automaticamente el 18/19/2010 ";
        $texto=$this->vlc_mensaje;
        $vl_texto_titulo_metodo = "";
        if ($vp_metodo == 'regalar')
        {
            $vl_texto_titulo_metodo ='regalado';
        }
        elseif ($vp_metodo == 'pedir')
        {
            $vl_texto_titulo_metodo ='pedido';
        }
        elseif ($vp_metodo == 'descargar')
        {
            $vl_texto_titulo_metodo ='descargado';
        }
        $vl_template = FN_LEER_TPL('tpl/tpl-popup-regalo-backtone.html');
        $vl_template = FN_REEMPLAZAR('{tpl-texto-renovacion}', $texto, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-imagen-backtone}', $imagen_album, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-tono}', $tono, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-autor}', $autor, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-ani}', $vp_ani, $vl_template);
        $vl_template = FN_REEMPLAZAR('{tpl-titulo-metodo}', $vl_texto_titulo_metodo, $vl_template);

        //FN_NET_LOGGER("APLICAR TEMPLATE COMPRA: $vl_template",5);
        return $vl_template;

    }
    
    function MTD_DB_LISTAR_TONO()
    {
        global $vg_db_conexion;
        $vlf_sql = "SELECT t.id, t.nombre as tono,aut.nombre as autor, alb.imagen
                    FROM tonos as t
                    inner join autores as aut on ( t.autor = aut.id)
                    left join albumes as alb on (t.album = alb.id_album)
                    WHERE t.id = ".$this->vlc_id_tono."                    
                    limit 1 ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 4,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }          
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    function MTD_ASIGNAR_ULTIMO_BACKTONE_ESCUCHADO()
    {
         //$_SESSION['backtones_escuchados']="";
        /*
        if (isset($_SESSION['backtones_escuchados']))
        {
            $vl_arreglo = array();
            $vl_arreglo = explode(",",$_SESSION['backtones_escuchados']);

            if ($vl_arreglo[1] != "")
            {
                $tono_viejo=$vl_arreglo[1];
            }
            else
            {
                $tono_viejo=$vl_arreglo[0];
            }

            
            $_SESSION['backtones_escuchados']=$tono_viejo.",".$this->vlc_id_tono;            
        }
        else
        {            
            $_SESSION['backtones_escuchados'].=$this->vlc_id_tono;
        }
         *
         */
        
        
        $_SESSION['backtones_escuchados']=$this->vlc_id_tono.",".$_SESSION['backtones_escuchados'];
        
        $vl_tonos_asignados = array();
        $vl_tonos_unicos    = array();        
        $vl_tonos_asignados = explode(",",$_SESSION['backtones_escuchados']);
        
        
        $vl_tonos_unicos    = array_unique($vl_tonos_asignados);
        $vl_contador=0;
        $vl_cantidad_registros = sizeof($vl_tonos_unicos) ;
        
        $_SESSION['backtones_escuchados']="";        
        
        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_contador >= 20)
            {
                break;
            }
            if ($vl_tonos_unicos[$vl_contador])
            {
                $_SESSION['backtones_escuchados'].= $vl_tonos_unicos[$vl_contador].",";
            }
       
            $vl_contador++;         
        }
        FN_NET_LOGGER("Asignar backtone escuchado > Asi termino:".$_SESSION['backtones_escuchados'],5);

    }
    function MTD_CHEQUEO_REINTENTO()
    {
        //CHEQUEO DE NO INGRESO DE COMPRA TIPO RAFAGA
        if (!(isset($_SESSION['CHK_REINTENTO'])))
        {
            $_SESSION['CHK_REINTENTO']=time();
            FN_NET_LOGGER("MTD_CHEQUEO_REINTENTO >- msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." Asignad?o:".$_SESSION['CHK_REINTENTO']." ", 0);
            return true;
        }
        else
        {
            $vl_segundos_compra = time();
            $vl_resultado=0;
            $vl_resultado=$vl_segundos_compra  - $_SESSION['CHK_REINTENTO']  ;

            FN_NET_LOGGER("MTD_CHEQUEO_REINTENTO >- msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono." diferencia tiempo:$vl_resultado ", 0);
            if ($vl_resultado < 5)
            {

                FN_NET_LOGGER(" R: -5");
                FN_NET_LOGGER("MTD_CHEQUEO_REINTENTO >- msisdn:".$this->vlc_msisdn." tono:".$this->vlc_id_tono."  >> Retorno por diferencia tiempo:$vl_resultado ", 0);
                return false;
            }
            else
            {
                $_SESSION['CHK_REINTENTO']=time();
                return true;
            }
        }

    }
    function MTD_VOTAR_RANKING($vp_ranking)
    {
        global $vg_db_conexion;
        //CHEQUEO DE REINTENTO
        if ($this->MTD_CHEQUEO_REINTENTO() == false)
        {
            return "-5";
        }

        $vl_sql="select msisdn from web_tonos_ranking where msisdn='$this->vlc_msisdn' and tonos_id=$this->vlc_id_tono ";
        $vl_arreglo= array();
        $vl_arreglo= FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);


        if (!($vl_arreglo))
        {
            FN_NET_LOGGER("Votar Ranking > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn ranking:$vp_ranking > Ingresando votacion ");
            $vl_sql="INSERT INTO web_tonos_ranking (tonos_id,msisdn,calificacion) values ($this->vlc_id_tono,'$this->vlc_msisdn',$vp_ranking);";
            $vl_resultado=false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
            return "0";
        }
        else
        {
            FN_NET_LOGGER("Votar Ranking > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn ranking:$vp_ranking > Actualizando votacion ");
            $vl_sql="UPDATE web_tonos_ranking set calificacion= $vp_ranking  where tonos_id=$this->vlc_id_tono and msisdn='$this->vlc_msisdn' limit 1 ;";
            $vl_resultado=false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
            return "0";
        }        
    }
    function MTD_VOTAR_MEGUSTA()
    {
        global $vg_db_conexion;
        $vl_sql="select msisdn from web_tonos_megusta where msisdn='$this->vlc_msisdn' and tonos_id=$this->vlc_id_tono ";
        $vl_arreglo= array();
        $vl_arreglo= FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);
        FN_NET_LOGGER("Votar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn  > SQL:$vl_sql ");

        if (!($vl_arreglo))
        {
            FN_NET_LOGGER("Votar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Ingresando votacion ");
            $vl_sql="INSERT INTO web_tonos_megusta (tonos_id,msisdn) values ($this->vlc_id_tono,'$this->vlc_msisdn');";
            $vl_resultado=false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
            return "0";
        }
        else
        {
            FN_NET_LOGGER("Votar Ranking > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > El usuario ya realizo el voto ");
            return "-3";
        }        
    }

    function MTD_MOSTRAR_RANKING()
    {
        global $vg_db_conexion;
        $vl_template=FN_LEER_TPL("tpl/tpl-backtones-ranking.html");
        $vl_sql     ="select round(avg(calificacion),0) from web_tonos_ranking where tonos_id=$this->vlc_id_tono;";
        $vl_arreglo = array();
        $vl_arreglo = FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);
        $vl_calificacion=0;
        $vl_calificacion=$vl_arreglo[0][0];
        FN_NET_LOGGER("Mostrar Ranking: tono:$this->vlc_id_tono calificacion: $vl_calificacion");
        if ($vl_calificacion > 0 )
        {
            $vl_template=FN_REEMPLAZAR("{tpl-select-$vl_calificacion}", ' selected ',$vl_template);
        }
        return $vl_template;

    }

    function MTD_MOSTRAR_IMAGENES_ALBUM()
    {
        global $vg_db_conexion, $vg_conf_filtro_web ;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path,
	aut.id as id_autor,
	aut.nombre as autor,
	alb.id_album,
	alb.nombre as album,
        alb.imagen_portada,
	gen.id_genero,
	gen.nombre as genero,
        alb.imagen
        FROM tonos as t
	inner join autores as aut ON (t.autor = aut.id)
	left join albumes as alb ON (t.album = alb.id_album)
	left join generos as gen ON (t.genero = gen.id_genero)
        WHERE
	t.id=$this->vlc_id_tono $vg_conf_filtro_web ;";
        $vlf_arreglo_datos          = FN_RUN_QUERY($vlf_sql, 11,$vg_db_conexion);

        
        $vlf_imagen_portada   = $vlf_arreglo_datos [0][7];
        $vlf_nombre_artista   = $vlf_arreglo_datos [0][4];
        $template_portada_album="albumes/".trim($vlf_imagen_portada);
        if ($template_portada_album == "albumes/")
        {
            $template_portada_album="albumes/album-default.jpg";
        }
        $template_imagen_facebook ="albumes/".trim($vlf_imagen_portada);
        if ($template_imagen_facebook == "albumes/")
        {
            $template_imagen_facebook="albumes/th-album-default.jpg";
        }
        FN_NET_LOGGER("Mostrar Album: >$template_portada_album");
        $template="";
        $template="<img src='$vlf_imagen_portada' class='border-picture'  style='padding-bottom:0px; margin-bottom:0px;' alt='$vlf_nombre_artista' title='$vlf_nombre_artista' />";
        return $template_portada_album;
    }

    function MTD_MOSTRAR_NOMBRE_ARTISTA()
    {
        global $vg_db_conexion, $vg_conf_filtro_web ;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path,
	aut.id as id_autor,
	aut.nombre as autor
        FROM tonos as t
	inner join autores as aut ON (t.autor = aut.id)
        WHERE
	t.id=$this->vlc_id_tono $vg_conf_filtro_web ;";
        $vlf_arreglo_datos          = FN_RUN_QUERY($vlf_sql, 5,$vg_db_conexion);

        $vlf_id_autor       = $vlf_arreglo_datos [0][3];
        $vlf_nombre_autor   = $vlf_arreglo_datos [0][4];
        $vlf_template="";
        $vlf_template="Artista:  <a href='index.php?seccion=playlist&autor=$vlf_id_autor'>$vlf_nombre_autor</a>";
        FN_NET_LOGGER("Mostrar Album: >$vlf_template");                
        return $vlf_template;
    }

    function MTD_MOSTRAR_NOMBRE_GENERO()
    {
        global $vg_db_conexion, $vg_conf_filtro_web ;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path,
	gen.id_genero as id_genero,
	gen.nombre as genero
        FROM tonos as t
	inner join generos as gen ON (t.genero = gen.id_genero)
        WHERE
	t.id=$this->vlc_id_tono $vg_conf_filtro_web ;";
        $vlf_arreglo_datos          = FN_RUN_QUERY($vlf_sql, 5,$vg_db_conexion);

        $vlf_id_genero       = $vlf_arreglo_datos [0][3];
        $vlf_nombre_genero   = $vlf_arreglo_datos [0][4];
        $vlf_template="";
        $vlf_template="G&eacute;nero:  <a href='index.php?seccion=playlist&genero=$vlf_id_genero'>$vlf_nombre_genero</a>";
        FN_NET_LOGGER("Mostrar Genero: >$vlf_template");
        return $vlf_template;
    }

    function MTD_MOSTRAR_REPRODUCCIONES()
    {
        global $vg_db_conexion;
        
        $vl_sql     ="select sum(cant) from reportes_backtones where idtono=$this->vlc_id_tono;";
        $vl_arreglo = array();
        $vl_arreglo = FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);
        $vl_cantidad=0;
        $vl_cantidad=number_format($vl_arreglo[0][0], 0, '.', '.');

        FN_NET_LOGGER("Mostrar Cantidad reproducciones: tono:$this->vlc_id_tono cantidad: $vl_cantidad");
        $vl_template="";
        if ($vl_cantidad> 0 )
        {
            $vl_template=FN_LEER_TPL("tpl/tpl-backtones-cantidad-reproducciones.html");
            $vl_template=FN_REEMPLAZAR("{tpl-cantidad}", $vl_cantidad,$vl_template);
        }
        else
        {
            $vl_template="";
        }
        return $vl_template;

    }
    function MTD_MOSTRAR_MEGUSTA()
    {
        global $vg_db_conexion;

        $vl_texto_mensaje_ami="";
        $vl_texto_mensaje_amigos="";
        $vl_texto_mensaje_otros="";
        $vl_texto_mensaje_="";
        $vl_amigos_id="";
        $vl_cantidad_amigos=0;

        /*
         * 
         */
         if ($this->vlc_msisdn !="")
         {
                // VERIFICAR SI A MI ME GUSTA
                $vl_sql="select msisdn from web_tonos_megusta where msisdn='$this->vlc_msisdn' and tonos_id=$this->vlc_id_tono ";
                $vl_arreglo= array();
                $vl_arreglo= FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);
             

                if ($vl_arreglo)
                {
                    $vl_texto_mensaje_ami=" <span> ti </span> ";
                    FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > te gusta a ti [$vl_texto_mensaje_ami] ",5);
                    
                }
                // VERIFICAR A QUE AMIGOS LE GUSTA
                $vl_sql="
                SELECT ud.nombre,ud.apellido,q1.r1, q1.r2, q1.r3
                FROM
                (
                SELECT
                        a.nombre as r1,
                        a.ani as r2,
                        wg.tonos_id as r3

                from web_tonos_megusta as wg , asignados as a
                where
                        wg.tonos_id=".$this->vlc_id_tono."
                AND	a.msisdn='".$this->vlc_msisdn."'
                AND	a.ani = wg.msisdn
                order by rand()
                limit 2) as q1
                left join usuarios_datos_personales as ud ON (ud.msisdn = q1.r2)
              
                group by  ud.nombre,ud.apellido,q1.r1, q1.r2, q1.r3
                  order by rand()
                limit 2";

                $vl_arreglo= array();
                
                $vl_arreglo= FN_RUN_QUERY($vl_sql, 5, $vg_db_conexion);                
                $vl_nombre1="";
                $vl_cantidad_registros=0;
                $vl_cantidad_registros= sizeof($vl_arreglo) -1;
                $vl_nombre2="";
                FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > cantidad de registros: $vl_cantidad_registros",5);
                //nombre
                //apellido
                //sobrenombre
                //numero
                //tono

                if ($vl_arreglo)
                {
                    if ($vl_cantidad_registros >= 0)
                    {
                        if ($vl_arreglo[0][0] !="")
                        {
                            $vl_nombre1=$vl_arreglo[0][0]." ".$vl_arreglo[0][1];
                            $vl_texto_mensaje_amigos.="<span> $vl_nombre1 </span> ";
                            $vl_amigos_id=",'".$vl_arreglo[0][3]."'";
                            $vl_cantidad_amigos=1;
                            FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 00 [".$vl_arreglo[0][0]."] 01 [".$vl_arreglo[0][1]."]",5);
                        }
                        elseif($vl_arreglo[0][2] != "")
                        {
                            $vl_nombre1=$vl_arreglo[0][2];
                            $vl_texto_mensaje_amigos.="<span> $vl_nombre1 </span> ";
                            $vl_amigos_id=",'".$vl_arreglo[0][3]."'";
                            $vl_cantidad_amigos=1;
                            FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 02 [".$vl_arreglo[0][2]."]",5);
                        }
                    }

                    if ($vl_cantidad_registros >= 1)
                    {
                        
                        if ($vl_arreglo[1][0] !="")
                        {
                            $vl_nombre2=$vl_arreglo[1][0]." ".$vl_arreglo[1][1];
                            $vl_texto_mensaje_amigos.=", <span> $vl_nombre2 </span> ";
                            $vl_amigos_id=",'".$vl_arreglo[1][3]."'";
                            $vl_cantidad_amigos=2;
                            FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 10 [".$vl_arreglo[1][0]."] 01 [".$vl_arreglo[1][1]."]",5);
                        }
                        elseif ($vl_arreglo[1][2] != "")
                        {
                            $vl_nombre2=$vl_arreglo[1][2];
                            $vl_texto_mensaje_amigos.=", <span> $vl_nombre2 </span> ";
                            $vl_amigos_id=",'".$vl_arreglo[1][3]."'";
                            $vl_cantidad_amigos=2;
                            FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 12 [".$vl_arreglo[1][2]."] id [".$vl_arreglo[1][3]."]",5);
                        }
                        FN_NET_LOGGER("Mostrar Megusta > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > les gusta a tus amigos $vl_texto_mensaje_amigos",5);
                    }
                }
         }
        // VERIFICAR A CUANTOS OTROS LES GUSTA


        
        $vl_sql="select count(msisdn) from web_tonos_megusta where  tonos_id=$this->vlc_id_tono  and msisdn not in('$this->vlc_msisdn' $vl_amigos_id  )";
        $vl_arreglo= array();
        //FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > a otros SQL:$vl_sql ");
        $vl_arreglo= FN_RUN_QUERY($vl_sql, 1, $vg_db_conexion);
        
        $vl_cantidad=0;
        if ($vl_arreglo)
        {
            $vl_cantidad= $vl_arreglo[0][0];
            if ($vl_cantidad >= 1)
            {
                FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > a otros $vl_cantidad  les gusta ",5);
                $vl_texto_mensaje_otros.=" $vl_cantidad ";
            }
        }
        // SACAR LAS <= 7 IMAGENES
/*
        $vl_sql="
           SELECT
	wg.tonos_id,
	wg.msisdn,
	ud.imagen,
	ud.nickname
        FROM web_tonos_megusta as wg
        LEFT JOIN usuarios_datos_personales as ud ON (wg.msisdn = ud.msisdn)
        WHERE wg.tonos_id=".$this->vlc_id_tono."
        order by rand()
        limit 7
          ";
        $vl_arreglo= array();
        $vl_arreglo= FN_RUN_QUERY($vl_sql, 4, $vg_db_conexion);
        //FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Imagenes SQL: $vl_sql");
        $vl_cantidad_registros=0;
        $vl_contador=0;
        $vl_imagenes="";
        $vl_imagen_usuario;
        if ($vl_arreglo)
        {
            $vl_cantidad_registros= sizeof($vl_arreglo);
            while ($vl_contador < $vl_cantidad_registros)
            {
                $vl_imagen_usuario="<img src='imagenes/usuarios/".$vl_arreglo[$vl_contador][2]."'>";
                if($vl_imagen_usuario == "<img src='imagenes/usuarios/'>")
                {                
                    $vl_imagenes.="<img src='imagenes/usuarios/imagen-usuarios-default.png'/>";
                }
                else
                {
                    $vl_imagenes.=$vl_imagen_usuario;
                }              
                $vl_contador++;
                
            }
        }

*/
        //ASIGNAR TEXTOS
        /*
         * 3 casos
         * ---------
         * {ningun voto -> sin mensaje}
         * solo voto tuyo       ----------------> a ti te gusta este backtone
         * solo voto amigo      ----------------> a {fulano}, {mengano} y otros 293 le gustan este backtone
         * voto tuyo y voto amigo---------------> a ti, a {fulano y mengano} y otros 293 le gustan este backtone
         * solo voto de otros ---------------> a otras 293 personas le gustan este backtone
         */
        //$vl_texto_mensaje_ami
        //$vl_texto_mensaje_amigos
        //$vl_texto_mensaje_otros
        $vl_texto_mensaje="";
        if (($vl_texto_mensaje_ami != "") &&  ($vl_texto_mensaje_amigos == "") && ($vl_texto_mensaje_otros == ""))//100
        {
            $vl_texto_mensaje = "a ti te gusta este backtone";
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 100",5);
        }
        elseif (($vl_texto_mensaje_ami == "") &&  ($vl_texto_mensaje_amigos != "") && ($vl_texto_mensaje_otros == ""))//010
        {
            if ($vl_cantidad_amigos == 1)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_amigos." le gusta este backtone";
            }
            else if($vl_cantidad_amigos == 2)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_amigos." les gusta este backtone";
            }            
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 010");
        }
        elseif (($vl_texto_mensaje_ami == "") &&  ($vl_texto_mensaje_amigos == "") && ($vl_texto_mensaje_otros != ""))//001
        {
            if ($vl_texto_mensaje_otros == 1)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_otros." persona le gusta este backtone";           
            }
            elseif ($vl_texto_mensaje_otros > 1)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_otros." personas les gusta este backtone";           
            }
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 001");
        }
        elseif (($vl_texto_mensaje_ami == "") &&  ($vl_texto_mensaje_amigos != "") && ($vl_texto_mensaje_otros != ""))//011
        {
            if ($vl_texto_mensaje_otros == 1)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_amigos." y a otra persona le gusta este backtone";
            }
            elseif ($vl_texto_mensaje_otros > 1)
            {
                $vl_texto_mensaje = "a ".$vl_texto_mensaje_amigos." y a otras ".$vl_texto_mensaje_otros." personas le gusta este backtone";
            }
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 001");
        }
        elseif (($vl_texto_mensaje_ami != "") &&  ($vl_texto_mensaje_amigos == "") && ($vl_texto_mensaje_otros != ""))//101
        {
            $vl_texto_mensaje = "a ti y a ".$vl_texto_mensaje_otros." personas les gusta este backtone";
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 101");
        }
        elseif (($vl_texto_mensaje_ami != "") &&  ($vl_texto_mensaje_amigos != "") && ($vl_texto_mensaje_otros == ""))//110
        {
            $vl_texto_mensaje = "a ti, a  ".$vl_texto_mensaje_amigos." les gusta este backtone";
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 110");
        }
        elseif (($vl_texto_mensaje_ami != "") &&  ($vl_texto_mensaje_amigos != "") && ($vl_texto_mensaje_otros != ""))//111
        {
            $vl_texto_mensaje = "a ti, a ".$vl_texto_mensaje_amigos."  y a otras $vl_texto_mensaje_otros personas les gusta este backtone";
            FN_NET_LOGGER("Mostrar Megusta> tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > 111");
        }

        $vl_template=FN_LEER_TPL("tpl/tpl-backtones-megusta.html");                
        $vl_template=FN_REEMPLAZAR('{tpl-texto}', $vl_texto_mensaje, $vl_template);
        //$vl_template=FN_REEMPLAZAR('{tpl-imagenes}', $vl_imagenes, $vl_template);
        //lFN_NET_LOGGER("Mostar Megusta:".$vl_template);
        return $vl_template;

    }
    function MTD_INGRESAR_COMENTARIO($vp_comentario)
    {
        global $vg_db_conexion;
        $vl_nickname="";

        $vl_nickname=$_SESSION['nickname'];
        $vl_arreglo=array();
        if(!($vl_nickname))
        {
            //recurso-i si ingreso el comentario luego actualizo su nick y ingresa nuevamente (no esta en session)
            $vl_sql="SELECT nickname from usuarios_datos_personales where msisdn='".$this->vlc_msisdn ."'";
            $vl_arreglo = FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
            $vl_nickname =$vl_arreglo[0][0];
        }
        
        FN_NET_LOGGER("Ingresar comentario > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Chequeo de nickname:$vl_nickname");
        if ($vl_nickname)
        {
            if ($vp_comentario)
            {
                FN_NET_LOGGER("Ingresar comentario > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Ingresando comentario:$vp_comentario ");
                $vl_sql="INSERT INTO web_tonos_comentarios
                                (tonos_id,msisdn,comentario,fecha,activo)
                        values ($this->vlc_id_tono,'$this->vlc_msisdn',
                                '$vp_comentario',now(),1);";
                $vl_resultado=false;
                $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
            }
            return "0";
        }
        else
        {
            FN_NET_LOGGER("Ingresar comentario > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Fallo de ingreso, no posee un nickname");
            return "-3";
        }
    }
    function MTD_MOSTRAR_COMENTARIOS()
    {
        global $vg_db_conexion;
        
        $vl_sql     ="
        SELECT
	ud.nickname,
	ud.imagen,
	wc.comentario,
	wc.fecha,
        wc.id_comentario
        FROM usuarios_datos_personales as ud,
                web_tonos_comentarios as wc
        WHERE
                wc.tonos_id = $this->vlc_id_tono
        AND	wc.msisdn = ud.msisdn
        AND     wc.activo=1
        order by wc.fecha desc
        limit 3;";
        $vl_arreglo = array();
        FN_NET_LOGGER("Mostrar Comentarios : tono:$this->vlc_id_tono ");
        $vl_arreglo = FN_RUN_QUERY($vl_sql, 5, $vg_db_conexion);
        if ($vl_arreglo)
        {
            $vl_cantidad_registros= sizeof($vl_arreglo);
            $vl_contador=0;
            $vl_template= FN_LEER_TPL("tpl/tpl-backtones-comentarios.html");
            $vl_comentarios="";

            //ud.nickname,
            //ud.imagen,
            //wc.comentario,            
            while ($vl_contador < $vl_cantidad_registros)
            {
                $vl_imagen="";
                $vl_imagen=$vl_arreglo[$vl_contador][1];
                if ($vl_imagen == "")
                {
                    $vl_imagen="imagen-usuarios-default.png";
                }

                if (is_file("imagenes/usuarios/".$vl_imagen) == false)
                {
                    $vl_imagen="imagen-usuarios-default.png";
                }

                $vl_comentarios.=$vl_template;
                $vl_comentarios = FN_REEMPLAZAR("{tpl-nickname}", $vl_arreglo[$vl_contador][0], $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-comentario}", $vl_arreglo[$vl_contador][2], $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-imagen-usuario}", $vl_imagen, $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-id-comentario}", $vl_arreglo[$vl_contador][4], $vl_comentarios );
                $vl_contador++;
            }

            if ($vl_cantidad_registros >2)
            {
                $vl_comentarios.="<script type='text/javascript'> $('#contenedor-todos-los-comentarios').fadeIn();</script> ";
            }
            else
            {
                $vl_comentarios.="<script type='text/javascript'> $('#contenedor-todos-los-comentarios').fadeOut();</script> ";
            }
            return $vl_comentarios;
        }
        
        $vl_comentarios="<div>&nbsp;</div><script type='text/javascript'> $('#contenedor-todos-los-comentarios').fadeOut();</script> ";
        return $vl_comentarios;

    }
    function MTD_MOSTRAR_TODOS_LOS_COMENTARIOS()
    {
        global $vg_db_conexion;

        $vl_sql     ="
        SELECT
	ud.nickname,
	ud.imagen,
	wc.comentario,
	wc.fecha,
        wc.id_comentario
        FROM usuarios_datos_personales as ud,
                web_tonos_comentarios as wc
        WHERE
                wc.tonos_id = $this->vlc_id_tono
        AND	wc.msisdn = ud.msisdn
        AND     wc.activo=1
        order by wc.fecha desc
        ";
        $vl_arreglo = array();
        FN_NET_LOGGER("Mostrar Comentarios : tono:$this->vlc_id_tono ");
        $vl_arreglo = FN_RUN_QUERY($vl_sql, 5, $vg_db_conexion);
        if ($vl_arreglo)
        {
            $vl_cantidad_registros= sizeof($vl_arreglo);
            $vl_contador=0;
            $vl_template= FN_LEER_TPL("tpl/tpl-backtones-comentarios.html");
            $vl_comentarios="<div id='contenedor-comentarios-contenido' style='position:static;padding-top:15px;height:300px; overflow-x:hidden; overflow-y:scroll;width:410px;' > ";

            //ud.nickname,
            //ud.imagen,
            //wc.comentario,
            while ($vl_contador < $vl_cantidad_registros)
            {
                $vl_imagen="";
                $vl_imagen=$vl_arreglo[$vl_contador][1];
                if ($vl_imagen == "")
                {
                    $vl_imagen="imagen-usuarios-default.png";
                }
                $vl_comentarios.=$vl_template;
                $vl_comentarios = FN_REEMPLAZAR("{tpl-nickname}", $vl_arreglo[$vl_contador][0], $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-comentario}", $vl_arreglo[$vl_contador][2], $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-imagen-usuario}", $vl_imagen, $vl_comentarios );
                $vl_comentarios = FN_REEMPLAZAR("{tpl-id-comentario}", $vl_arreglo[$vl_contador][4], $vl_comentarios );
                $vl_contador++;
            }

            $vl_comentarios.="</div>";
            return $vl_comentarios;
        }
        return '<div>&nbsp;</div>';

    }

    function MTD_DENUNCIAR_COMENTARIOS($vp_id_comentario)
    {
        include_once 'includes/FN_NET_ENVIAR_MAIL.php';
        global $vg_db_conexion;
        $vl_id_comentario=0;
        $vl_id_comentario = $vp_id_comentario;
        $vl_arreglo_datos = array();
        $vl_comentario="";
        $vl_msisdn="";
        $vl_fecha="";
        $vl_mensaje="";
        if (is_numeric($vl_id_comentario))
        {
            $vl_sql="SELECT comentario, fecha,msisdn from web_tonos_comentarios where id_comentario=$vl_id_comentario";
            $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 3,$vg_db_conexion);
            $vl_comentario=$vl_arreglo_datos[0][0];
            $vl_fecha=$vl_arreglo_datos[0][1];
            $vl_msisdn=$vl_arreglo_datos[0][2];
            
            $vl_sql="UPDATE web_tonos_comentarios set activo=0 where id_comentario=$vl_id_comentario";
            $vl_resultado=false;
            FN_NET_LOGGER("Denunciar comentario > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Ejecutando query: $vl_sql");
            $vl_resultado= FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
            
            $vl_mensaje="<br><b>Denuncia de comentario:</b><br>$vl_comentario <br> <b>Usuario:</b> $vl_msisdn <br> <b> Fecha del comentario:</b>$vl_fecha ";
            FN_NET_ENVIAR_MAIL("Denuncia de comentarios",$vl_mensaje);
            return "0";
        }
        else
        {
            FN_NET_LOGGER("Denunciar comentario > tono: $this->vlc_id_tono msisdn:$this->vlc_msisdn > Fallo de denuncia, id invalido (no numerico)");
            return "0";
        }
    }

    function MTD_ACTUALIZAR_LINK_FACEBOOK()
    {
        global $vg_db_conexion, $vg_conf_filtro_web,$vg_conf_dominio ;
        FN_NET_LOGGER("ACTUALIZAR LINK FACEBOOK > TONO ".$this->vlc_id_tono);
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path,
	aut.id as id_autor,
	aut.nombre as autor,
	alb.id_album,
	alb.nombre as album,
        alb.imagen_portada,
	gen.id_genero,
	gen.nombre as genero,
        alb.imagen
        FROM tonos as t
	inner join autores as aut ON (t.autor = aut.id)
	left join albumes as alb ON (t.album = alb.id_album)
	left join generos as gen ON (t.genero = gen.id_genero)
        WHERE
	t.id=".$this->vlc_id_tono." $vg_conf_filtro_web ;";


        $vlf_arreglo_datos          = FN_RUN_QUERY($vlf_sql, 11,$vg_db_conexion);
        $vlf_id_artista       = $vlf_arreglo_datos [0][3];
        $vlf_nombre_tono      = $vlf_arreglo_datos [0][1];
        $vlf_nombre_artista   = $vlf_arreglo_datos [0][4];
        $vlf_id_album         = $vlf_arreglo_datos [0][5];
        $vlf_nombre_album     = $vlf_arreglo_datos [0][6];
        $vlf_imagen_portada   = $vlf_arreglo_datos [0][7];
        $vlf_id_genero        = $vlf_arreglo_datos [0][8];
        $vlf_nombre_genero    = $vlf_arreglo_datos [0][9];
        $vlf_imagen_facebook  = $vlf_arreglo_datos [0][10];
        
        //FACEBOOK
        
        $template_portada_album="albumes/".trim($vlf_imagen_portada);        
        if ($template_portada_album == "albumes/")
        {
            $template_portada_album="albumes/album-default.jpg";
        }
        $template_imagen_facebook ="albumes/".trim($vlf_imagen_facebook);
        if ($template_imagen_facebook == "albumes/")
        {
            $template_imagen_facebook="albumes/th-album-default.jpg";
        }

        $vlf_imagen_facebook="$vg_conf_dominio"."/v3/".$template_imagen_facebook;
        $vlf_estampa_tiempo=time();
        $url_facebook="$vg_conf_dominio/v3/index.php?seccion=backtones&backtone=$this->vlc_id_tono&f=1&t=$vlf_estampa_tiempo";
        FN_NET_LOGGER("ACTUALIZAR LINK FACEBOOK > TONO ".$this->vlc_id_tono."\n".$url_facebook);
        $url_facebook=urlencode($url_facebook);
        $vlf_titulo_head="Backtones TIGO | $vlf_nombre_artista - $vlf_nombre_tono ";

        $titulo_url="";
        return $url_facebook;
    }
}
?>
