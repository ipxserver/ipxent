<?
class CLASS_GIFT_A_TUNE
{
    private $vlc_msisdn;
    private $vlc_ani;
    private $vlc_id_tono;
    private $vlc_short;

    function MTD_ASIGNAR_MSISDN($vp_msisdn)
    {
        $this->vlc_msisdn= $vp_msisdn;
    }
    function MTD_ASIGNAR_ANI($vp_ani)
    {
        $this->vlc_ani= $vp_ani;
    }
    function MTD_ASIGNAR_TONO($vp_id_tono)
    {
        $this->vlc_id_tono= $vp_id_tono;
    }

    function MTD_REGALAR_TONO()
    {
        global $vg_feature_giftatune;
        FN_NET_LOGGER("MTD_REGALAR_TONO msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono);
        $vl_lista_usuarios = array();
        $vl_lista_usuarios = $this->MTD_VER_LISTA(101);

        //VERIFICA QUE NO ESTE EN BLACKLIST
        if (!($this->MTD_VER_LISTA(101)))
        {
            //VERIFICA QUE NO ESTE EN WHITELIST
            if (!($this->MTD_VER_LISTA(201)))
            {
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > ",5);
                if ($this->MTD_ASIGNAR_SHORT_NUMBER(1) == true)
                {

                    if (!$vg_feature_giftatune) # si no esta activo gifatune
                    {
                        FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > feature giftatune desactivado ",5);
                        $vl_nombre_tono="";
                        $vl_arreglo_datos= array();
                        $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
                        $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];
                        $vl_mensaje = "El usuario del numero ".$this->vlc_msisdn." desea dedicarte y pagarte por un mes el backtone ".$vl_nombre_tono." Responde 1.Aceptar 2.-Rechazar";

                        FN_NET_SMS($vl_mensaje ,$this->vlc_ani,$this->vlc_short);
                        //SendSMS($short,$num,"");
                    }
                    else # si esta activo giftatune
                    { # fin del if
                        FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > feature giftatune activado ",5);

                        //NOMBRE DEL TONO
                        $vl_nombre_tono="";
                        $vl_arreglo_datos= array();
                        $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
                        $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];

                        $vl_mensaje = "El usuario del numero ".$this->vlc_msisdn." desea dedicarte y pagarte por un mes el backtone ".$vl_nombre_tono." Responde 1.Aceptar 2.-Rechazar";
                        //$vl_mensaje2 = "Responde:\n1. Aceptar \n2. Rechazar";

                        FN_NET_SMS($vl_mensaje ,$this->vlc_ani,$this->vlc_short);

                        return true;
                        //SendSMS($short,$num,"El '$msisdn' desea regalarte el tono '".get_name($t)."'");
                        //SendSMS($short,$num,"Responde:\n1. Para Aceptar esta vez.\n2. Aceptar siempre.\n3. Rechazar esta vez.\n4. Rechazar siempre.");
                    } # fin del else

                }
                else
                {
                    FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > fallo la asignacion del shortnumber ".$this->vlc_short,5);
                    return false;
                }
            }
            else
            {# se encuentra en lista blanca
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > Se encuentra en lista blanca ",5);
                procesar_solicitud($msisdn, $num, 1, $t);

                //NOMBRE DEL TONO
                $vl_nombre_tono="";
                $vl_arreglo_datos= array();
                $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
                $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];

                include_once('class/CLASS_USUARIOS_BACKTONES.php');
                $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
                $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_ani);
                $obj_usuarios_backtones->MTD_REGISTRAR_USUARIO();

                //ASIGNAR BACKTONE
                FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Agregando backtone al usuario ");

                $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS($this->vlc_id_tono);
                $vl_mensaje_sms= "El usuario ".$this->vlc_msisdn." te ha regalado el backtone '$vl_nombre_tono'";
                FN_NET_SMS($vl_mensaje_sms, $vp_ani);
                return 'tono-regalado';
                //SendSMS($smsFrom,$num,"El '$msisdn' te ha regalado el tono '".get_name($t)."'.");

            }

        }
    }
    function MTD_PEDIR_TONO()
    {
        global $vg_feature_giftatune;
        FN_NET_LOGGER("MTD_PEDIR_TONO msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono);
        $vl_lista_usuarios = array();
        $vl_lista_usuarios = $this->MTD_VER_LISTA(101);

        //VERIFICA QUE NO ESTE EN BLACKLIST
        if (!($this->MTD_VER_LISTA(101)))
        {
            //VERIFICA QUE NO ESTE EN WHITELIST
            if (!($this->MTD_VER_LISTA(201)))
            {
                FN_NET_LOGGER("MTD_PEDIR_TONO  msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > ",5);
                if ($this->MTD_ASIGNAR_SHORT_NUMBER(6) == true)
                {
                    FN_NET_LOGGER("MTD_PEDIR_TONO  msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > feature giftatune activado ",5);
                    //NOMBRE DEL TONO
                    $vl_nombre_tono="";
                    $vl_arreglo_datos= array();
                    $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
                    $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];
                    $vl_mensaje = "El usuario del numero ".$this->vlc_ani." desea pedirte el backtone ".$vl_nombre_tono." Responde 1.Aceptar 2.-Rechazar";
                    //$vl_mensaje1 = "El ".$this->vlc_ani." desea pedirte el tono ".$vl_nombre_tono."";
                    //$vl_mensaje2 = "Responde:\n1. Aceptar tono.\n2. Rechazar.";

                    //FN_NET_SMS($vl_mensaje1 ,$this->vlc_msisdn,$this->vlc_short);
                    //FN_NET_SMS($vl_mensaje2 ,$this->vlc_msisdn,$this->vlc_short);
                    FN_NET_SMS($vl_mensaje1."\n".$vl_mensaje2 ,$this->vlc_msisdn,$this->vlc_short);
                    return true;

                }
                else
                {
                    FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > fallo la asignacion del shortnumber ".$this->vlc_short,5);
                    return false;

                }
            }
            else
            {# se encuentra en lista blanca
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > Se encuentra en lista blanca ",5);
                procesar_solicitud($msisdn, $num, 1, $t);

                //NOMBRE DEL TONO
                $vl_nombre_tono="";
                $vl_arreglo_datos= array();
                $vl_arreglo_datos= $this->MTD_DB_LISTAR_TONO();
                $vl_nombre_tono= $vl_arreglo_datos[0][1]." - ".$vl_arreglo_datos[0][2];

                include_once('class/CLASS_USUARIOS_BACKTONES.php');
                $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
                $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_ani);
                $obj_usuarios_backtones->MTD_REGISTRAR_USUARIO();

                //ASIGNAR BACKTONE
                FN_NET_LOGGER("MTD_REGALAR_BACKTONES > msisdn:".$this->vlc_msisdn." ani: $vp_ani  tono: $vp_id_tono > Agregando backtone al usuario ");

                $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS($this->vlc_id_tono);
                $vl_mensaje_sms= "El usuario ".$this->vlc_msisdn." te ha regalado el backtone '$vl_nombre_tono'";
                FN_NET_SMS($vl_mensaje_sms, $vp_ani);
                return 'tono-regalado';
                //SendSMS($smsFrom,$num,"El '$msisdn' te ha regalado el tono '".get_name($t)."'.");

            }

        }
    }
    function MTD_ASIGNAR_SHORT_NUMBER($vp_tipo)
    {
        //vp_tipo
        //1 = regalo
        //6 = pedir

        global $vg_db_conexion,$vg_conf_min_short_number ,$vg_conf_max_short_number ;
        $vl_arreglo_datos=array();
        $vl_fecha = time();
        FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono);
        $vl_sql="SELECT short FROM regalos_pendientes WHERE msisdn='".$this->vlc_msisdn."' AND tono=".$this->vlc_id_tono." AND numero='".$this->vlc_ani."'";
        $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
        $this->vlc_short= $vl_arreglo_datos[0][0];

        //EL REGISTRO EXISTO HAY QUE ACTUALIZAR
        if ($vl_arreglo_datos[0][0] >0)
        {
            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > El registro existe, actualizando fecha");
            $vl_sql="UPDATE regalos_pendientes SET fecha=$vl_fecha WHERE msisdn='".$this->vlc_msisdn."' AND tono=".$this->vlc_id_tono." AND numero='".$this->vlc_ani."'";
            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > query_asignacion: $vl_sql",5);
            $vl_resultado=false;
            $vl_resultado=FN_RUN_QUERY($vl_sql,$vg_db_conexion);
            return true;
        }
        else
        {
            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > El registro no existe");
            $vl_sql = "SELECT short FROM regalos_pendientes WHERE numero='".$this->vlc_ani."'";
            $vl_arreglo_datos=FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > Se buscan los short asignados :$vl_sql");
            $vl_short_asignado=0;
            $vl_lista_short=$vg_conf_min_short_number ;
            $vl_contador=0;
            $vl_cantidad_registros= sizeof($vl_arreglo_datos);

            while ($vl_lista_short < $vg_conf_max_short_number )
            {
                //VERIFICA CUAL SHORT NUMBER ESTA LIBRE PARA USARLO
                $vl_contador=0;
                while($vl_contador < $vl_cantidad_registros)
                {
                    if ($vl_short_asignado > 0)
                    {
                        break;
                    }
                    FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > verifica si [".$vl_arreglo_datos[$vl_contador][0]."] != .".$vl_lista_short."");
                    if ($vl_arreglo_datos[$vl_contador][0] != $vl_lista_short)
                    {
                        FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > SE ASIGNA EL SHORT= .".$vl_lista_short."");
                        $vl_short_asignado = $vl_lista_short;
                        break;
                    }
                    $vl_contador++;
                }
                $vl_lista_short++;
            }
            $this->vlc_short=$vl_short_asignado;
            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > Se encontro el short number libre:$vl_short_asignado ");
            if ($vl_short_asignado > 0)
            {
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > Se encontro el short number libre:$vl_short_asignado ");
                $vl_sql="INSERT INTO regalos_pendientes (fecha,msisdn,tono,short,numero,tipo) VALUES ($vl_fecha ,'".$this->vlc_msisdn."',".$this->vlc_id_tono.",'$vl_short_asignado','".$this->vlc_ani."',$vp_tipo)";

                $this->vlc_short= $vl_short_asignado;
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > query_asignacion: $vl_sql",5);
                $vl_resultado=false;
                $vl_resultado=FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                return true;
            }
            else
            {
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > no se encontro ningun short se busca uno ");
                $vl_sql="SELECT id,short FROM regalos_pendientes WHERE fecha = (select min(fecha) from regalos_pendientes where numero='".$this->vlc_ani."') LIMIT 1;";
                $vl_arreglo_datos = FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > query_seleccion: $vl_sql",5);
                //insert con el minimo
                //
                //$vl_sql="UPDATE regalos_pendientes SET fecha=$vl_fecha,msisdn='".$this->vlc_msisdn."',tono=".$this->vlc_id_tono.", tipo = 1 WHERE id=".$vl_arreglo_datos[0][0];
                //$this->vlc_short= $vl_arreglo_datos[0][1];
                $vl_sql="INSERT INTO regalos_pendientes (fecha,msisdn,tono,short,numero,tipo) VALUES ($vl_fecha ,'".$this->vlc_msisdn."',".$this->vlc_id_tono.",'$vg_conf_min_short_number','".$this->vlc_ani."',$vp_tipo)";
                $this->vlc_short= $vg_conf_min_short_number;
                FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > query_asignacion: $vl_sql",5);
                $vl_resultado=false;
                $vl_resultado=FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                return true;
            }

            FN_NET_LOGGER("BUSCAR_SHORT_NUMBER msisdn:".$this->vlc_msisdn." ani: ".$this->vlc_ani." tono:".$this->vlc_id_tono." > El short asignado es: $vl_short");
        }
        return false;

    }
    function MTD_VER_LISTA($vp_tipo)
    {
        global $vg_db_conexion;
        $vl_sql="";
        $vl_arreglo = array();
        $vl_sql = "SELECT * FROM Lista_usuarios WHERE msisdn_A='".$this->vlc_msisdn."' AND msisdn_B='".$this->vlc_ani."' AND tipo=$vp_tipo";
        $vl_arreglo = FN_RUN_QUERY($vl_sql , 4,$vg_db_conexion);
        return $vl_arreglo ;
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
}
?>
