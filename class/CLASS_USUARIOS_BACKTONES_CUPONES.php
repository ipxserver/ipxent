<?php
class CLASS_USUARIOS_BACKTONES
{
    private $vlc_msisdn;
    private $vlc_id_tono;
    private $vlc_mensaje;
    function __construct()
    {
        $this->vlc_msisdn ="";
        $this->vlc_id_tono ="";
        $this->vlc_mensaje ="";
    }
    function MTD_ASIGNAR_MSISDN($vp_msisdn)
    {
        $this->vlc_msisdn=$vp_msisdn;
    }
    function MTD_DB_LISTAR_TONOS()
    {
        global $vg_db_conexion;

        //VERIFICAR TONO ACTIVO O INACTIVO
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from usuarios where msisdn='$this->vlc_msisdn'";
        $vl_arreglo_datos_tono = FN_RUN_QUERY($vl_sql_tono,1, $vg_db_conexion);
        $vl_arreglo_tonos      = explode('|',$vl_arreglo_datos_tono[0][0]);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador_tonos     = 0;
        $vl_tonos=array();
        while ($vl_contador_tonos < $vl_cantidad_tonos)
        {
            $vl_tonos[$vl_contador_tonos]= explode(':',$vl_arreglo_tonos[$vl_contador_tonos]);
            $vl_contador_tonos++;
        }
        return $vl_tonos;
    }
    function MTD_DB_LISTAR_DATOS_USUARIOS()
    {
        global $vg_db_conexion;

        //VERIFICAR TONO ACTIVO O INACTIVO
        $vl_arreglo_datos      = array();
        $vl_sql_datos           = "SELECT msisdn,tonos,modo,param from usuarios where msisdn='".$this->vlc_msisdn."'";
        $vl_arreglo_datos = FN_RUN_QUERY($vl_sql_datos,4, $vg_db_conexion);
        return $vl_arreglo_datos ;
    }
    function MTD_DB_AGREGAR_TONOS($vp_id_tono_agregar)
    {
        global $vg_db_conexion;

        FN_NET_LOGGER("Agregando tono msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_agregar);
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from usuarios where msisdn='$this->vlc_msisdn'";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);

        
        //VERIFICAR SI EXISTE EL TONO
        $vl_arreglo_tonos      = $this->MTD_DB_LISTAR_TONOS();
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador           = 0;
        $vl_posee_eltono       = false;

        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_agregar == $vl_arreglo_tonos[$vl_contador][0])
           {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn." El tono :$vp_id_tono_agregar  ya esta asignado al usuario",0,"WARN -");
                $vl_posee_eltono=true;
                $this->vlc_mensaje.="Atenci&oacute;n, usted ya posee el tono seleccionado";
           }
            $vl_contador++;
        }
        //ASIGNAR EL TONO
        if ($vl_posee_eltono == false)
        {
            if (($vl_tonos_adquiridos[0][0] != "")  && ($vl_tonos_adquiridos[0][0] != null))
            {

                $vl_nuevo_tono=$vl_tonos_adquiridos[0][0]."$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            else
            {
                $vl_nuevo_tono="$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            $this->vlc_mensaje.="<br> DEBUG:".$vl_nuevo_tono;
            $vl_sql_nuevo_tono="UPDATE usuarios set tonos='$vl_nuevo_tono' where msisdn='".$this->vlc_msisdn."' limit 1;";
            $vl_resultado=false;
            $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
            if ($vl_resultado == true)
            {
                //ASIGNACION EXITOSA
                //TODO: loguear la adquisicion del tono
                //INGRESO EN BILLING_SCHED
              
                 //VERIFICA SI INSERTA CORRECTAMENTE EN BILLING
                 if ($this->MTD_DB_INGRESAR_BILLING($vp_id_tono_agregar) == true)
                 {
                     FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn." ingreso exitoso de billing tono: $vp_id_tono_agregar ",2);
                     return true;
                 }
                 else
                 {
                     //TODO: BORRAR EL TONO ASIGNADO PREVIAMENTE
                     // VERIFICAR SI EXISTE EL REGISTRO EN BILLING_SCHED
                     // SI EXISTE ACTUALIZARLO
                     // SI NO EXISTE LOGUEAR CONDICION DE ERROR CRITICO O QUE RE-INTENTE
                     FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn." Error de ingreso de billing tono:$vp_id_tono_agregar",0,"ERROR -");
                     $this->vlc_mensaje.="<br> Favor intente nuevamente mas tarde .";
                     return false;
                 }

            }
            else
            {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
                $this->vlc_mensaje.="<br> Favor intente nuevamente mas tarde";
                return false;
            }
        }
        else
        {
            $this->vlc_mensaje.="<br> Atencion, no se realizo la compra del backtone, debido a que usted ya posee el tono";
            return false;
        }

       
    }
    function MTD_DB_LISTAR_TONOS_ASIGNADOS_AMIGOS($vp_id_ani)
    {
        global $vg_db_conexion;

        //VERIFICAR TONO ACTIVO O INACTIVO
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from asignados where msisdn='$this->vlc_msisdn' and ani='$vp_id_ani'";
        FN_NET_LOGGER("Listar tonos asignados al amigo: $vl_sql_tono");
        $vl_arreglo_datos_tono = FN_RUN_QUERY($vl_sql_tono,1, $vg_db_conexion);
        $vl_arreglo_tonos      = explode('|',$vl_arreglo_datos_tono[0][0]);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador_tonos     = 0;
        $vl_tonos=array();
        while ($vl_contador_tonos < $vl_cantidad_tonos)
        {
            $vl_tonos[$vl_contador_tonos]= explode(':',$vl_arreglo_tonos[$vl_contador_tonos]);
            $vl_contador_tonos++;
        }
        return $vl_tonos;
    }
    function MTD_DB_LISTAR_TONOS_ASIGNADOS($vp_id_ani)
    {
        global $vg_db_conexion;

        //VERIFICAR TONO ACTIVO O INACTIVO
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from asignados where msisdn='$this->vlc_msisdn' and ani='$vp_id_ani'";
        FN_NET_LOGGER("Listar tonos asignados al amigo: $vl_sql_tono");
        $vl_arreglo_datos_tono = FN_RUN_QUERY($vl_sql_tono,1, $vg_db_conexion);
        $vl_arreglo_tonos      = explode('|',$vl_arreglo_datos_tono[0][0]);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador_tonos     = 0;
        $vl_tonos=array();
        while ($vl_contador_tonos < $vl_cantidad_tonos)
        {
            $vl_tonos[$vl_contador_tonos]= explode(':',$vl_arreglo_tonos[$vl_contador_tonos]);
            $vl_contador_tonos++;
        }
        return $vl_tonos;
    }
    function MTD_DB_LISTAR_TONOS_GRUPOS($vp_id_grupo)
    {
        global $vg_db_conexion;

        //VERIFICAR TONO ACTIVO O INACTIVO
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from grupos where msisdn='$this->vlc_msisdn' and id=$vp_id_grupo";
        FN_NET_LOGGER("Listar tonos del grupo: $vl_sql_tono");
        $vl_arreglo_datos_tono = FN_RUN_QUERY($vl_sql_tono,1, $vg_db_conexion);
        $vl_arreglo_tonos      = explode('|',$vl_arreglo_datos_tono[0][0]);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador_tonos     = 0;
        $vl_tonos=array();
        while ($vl_contador_tonos < $vl_cantidad_tonos)
        {
            $vl_tonos[$vl_contador_tonos]= explode(':',$vl_arreglo_tonos[$vl_contador_tonos]);
            $vl_contador_tonos++;
        }
        return $vl_tonos;
    }
    function MTD_DB_AGREGAR_TONOS_ASIGNADOS_AMIGOS($vp_id_tono_agregar,$vp_id_ani)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Agregar Tonos Asignados al amigo-> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_agregar." ani:$vp_id_ani ");
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from asignados where msisdn='$this->vlc_msisdn' and ani='$vp_id_ani'";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);


        //VERIFICAR SI EXISTE EL TONO
        $vl_arreglo_tonos      = $this->MTD_DB_LISTAR_TONOS_ASIGNADOS_AMIGOS($vp_id_ani);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador           = 0;
        $vl_posee_eltono       = false;

        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_agregar == $vl_arreglo_tonos[$vl_contador][0])
           {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn." El tono :$vp_id_tono_agregar ya esta asignado al amigo =  ".$vl_arreglo_tonos[$vl_contador][0],0,"WARN -");
                $vl_posee_eltono=true;
           }
            $vl_contador++;
        }
        //ASIGNAR EL TONO
        if ($vl_posee_eltono == false)
        {
            if (($vl_tonos_adquiridos[0][0] != "")  && ($vl_tonos_adquiridos[0][0] != null))
            {
                $vl_nuevo_tono=$vl_tonos_adquiridos[0][0]."$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            else
            {
                $vl_nuevo_tono="$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            $this->vlc_mensaje.="<br> DEBUG:".$vl_nuevo_tono;
            $vl_sql_nuevo_tono="UPDATE asignados set tonos='$vl_nuevo_tono' where msisdn='".$this->vlc_msisdn."' and ani='".$vp_id_ani."' limit 1;";
            $vl_resultado=false;
            $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
            if ($vl_resultado == true)
            {
               return true;
            }
            else
            {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
                return false;
            }
        }
        else
        {
            $this->vlc_mensaje.="<br> Atencion, no se realizo la compra del backtone, debido a que usted ya posee el tono";
            return false;
        }


    }
    function MTD_DB_QUITAR_TONOS_ASIGNADOS_AMIGOS($vp_id_tono_quitar,$vp_id_ani)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Quitar Tonos Asignados al amigo -> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_quitar." ani:$vp_id_ani");
        $vl_arreglo_tonos_asignados = array();
        $vl_sql_tono           = "SELECT tonos from asignados where msisdn='$this->vlc_msisdn' and ani='$vp_id_ani';";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);


        //VERIFICAR SI EXISTE EL TONO
        $vl_arreglo_tonos_asignados = $this->MTD_DB_LISTAR_TONOS_ASIGNADOS_AMIGOS($vp_id_ani);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos_asignados) -1;
        $vl_contador           = 0;
        $vl_posee_eltono       = false;
        $vl_tonos_asignados        = "";


        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_quitar != $vl_arreglo_tonos_asignados[$vl_contador][0])
           {
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][0].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][1].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][4]."|";
           }
           else
           {
               FN_NET_LOGGER("Quitar Tonos Asignados al amigo> Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar  ani: $vp_id_ani se encontro y no se asigno",0,"WARN -");
           }
           $vl_contador++;
        }
        FN_NET_LOGGER("Quitar Tonos Asignados al grupo > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar -> quedo asi:$vl_tonos_asignados");
        $vl_sql_nuevo_tono="UPDATE asignados set tonos='$vl_tonos_asignados' where msisdn='".$this->vlc_msisdn."' and ani='".$vp_id_ani."' limit 1;";
        $vl_resultado=false;
        $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
        if ($vl_resultado == true)
        {
           return true;
        }
        else
        {
            FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
            return false;
        }
    }
    function MTD_DB_AGREGAR_TONOS_ASIGNADOS($vp_id_tono_agregar,$vp_id_grupo)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Agregar Tonos Asignados al grupo-> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_agregar." id_grupo:$vp_id_grupo ");
        $vl_arreglo_tonos      = array();
        $vl_sql_tono           = "SELECT tonos from grupos where msisdn='$this->vlc_msisdn' and id=$vp_id_grupo";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);


        //VERIFICAR SI EXISTE EL TONO
        $vl_arreglo_tonos      = $this->MTD_DB_LISTAR_TONOS_ASIGNADOS($vp_id_grupo);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos);
        $vl_contador           = 0;
        $vl_posee_eltono       = false;

        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_agregar == $vl_arreglo_tonos[$vl_contador][0])
           {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn." El tono :$vp_id_tono_agregar ya esta asignado al grupo =  ".$vl_arreglo_tonos[$vl_contador][0],0,"WARN -");
                $vl_posee_eltono=true;               
           }
            $vl_contador++;
        }
        //ASIGNAR EL TONO
        if ($vl_posee_eltono == false)
        {
            if (($vl_tonos_adquiridos[0][0] != "")  && ($vl_tonos_adquiridos[0][0] != null))
            {
                $vl_nuevo_tono=$vl_tonos_adquiridos[0][0]."$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            else
            {
                $vl_nuevo_tono="$vp_id_tono_agregar:1:0:0:1|";
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn."Asignando tono:$vl_nuevo_tono ",2);
            }
            $this->vlc_mensaje.="<br> DEBUG:".$vl_nuevo_tono;
            $vl_sql_nuevo_tono="UPDATE grupos set tonos='$vl_nuevo_tono' where msisdn='".$this->vlc_msisdn."' and id=".$vp_id_grupo." limit 1;";
            $vl_resultado=false;
            $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
            if ($vl_resultado == true)
            {
               return true;
            }
            else
            {
                FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");                
                return false;
            }
        }
        else
        {
            $this->vlc_mensaje.="<br> Atencion, no se realizo la compra del backtone, debido a que usted ya posee el tono";
            return false;
        }


    }
    function MTD_DB_QUITAR_TONOS_ASIGNADOS($vp_id_tono_quitar,$vp_id_grupo)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Quitar Tonos Asignados al grupo -> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_quitar." grupo:$vp_id_grupo");
        $vl_arreglo_tonos_asignados = array();
        $vl_sql_tono           = "SELECT tonos from grupos where msisdn='$this->vlc_msisdn' and id=$vp_id_grupo;";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);


        //VERIFICAR SI EXISTE EL TONO
        $vl_arreglo_tonos_asignados = $this->MTD_DB_LISTAR_TONOS_ASIGNADOS($vp_id_grupo);
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos_asignados) -1;
        $vl_contador           = 0;
        $vl_posee_eltono       = false;
        $vl_tonos_asignados        = "";


        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_quitar != $vl_arreglo_tonos_asignados[$vl_contador][0])
           {
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][0].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][1].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][4]."|";
           }
           else
           {
               FN_NET_LOGGER("Quitar Tonos Asignados al grupo> Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar  grupo: $vp_id_grupo se encontro y no se asigno",0,"WARN -");
           }
           $vl_contador++;
        }
        FN_NET_LOGGER("Quitar Tonos Asignados al grupo > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar -> quedo asi:$vl_tonos_asignados");
        $vl_sql_nuevo_tono="UPDATE grupos set tonos='$vl_tonos_asignados' where msisdn='".$this->vlc_msisdn."' and id=".$vp_id_grupo." limit 1;";
        $vl_resultado=false;
        $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
        if ($vl_resultado == true)
        {
           return true;
        }
        else
        {
            FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");            
            return false;
        }
    }
	function MTD_DB_GET_DIAS_RENOVACION($vp_id_tono)
	{
		 global $vg_db_conexion;
		$vl_arreglo=array();

        FN_NET_LOGGER("Msisdn0:".$this->vlc_msisdn.".Tono:".$vp_id_tono.".Renovacion:$daysRenovacion");
        $vl_sql_renovacion = "SELECT renovacion FROM renovacion WHERE msisdn='$this->vlc_msisdn' AND tono = $vp_id_tono";
		$vl_arreglo =  FN_RUN_QUERY($vl_sql_renovacion,1,$vg_db_conexion);
        $daysRenovacion =  $vl_arreglo[0][0];

		if($daysRenovacion > 0)
		{
			return $daysRenovacion;
		} else
		{
			$daysRenovacion = 30;
			return $daysRenovacion;
		}
	}	

    function MTD_DB_INGRESAR_BILLING($vp_id_tono,$vp_concepto='Tono comprado desde la WEB')
    {
        global $vg_db_conexion;

        $descripcion=$vp_concepto;
		$daysRenovacion = 0;
		$vl_arreglo=array();
		FN_NET_LOGGER("Msisdn0:".$this->vlc_msisdn.".Tono:".$vp_id_tono."Renovacion:$daysRenovacion");
		$vl_sql_renovacion = "SELECT renovacion FROM renovacion WHERE msisdn='$this->vlc_msisdn' AND tono = $vp_id_tono";
	FN_NET_LOGGER("QUERY:$vl_sql_renovacion");
		$vl_arreglo =  FN_RUN_QUERY($vl_sql_renovacion,1,$vg_db_conexion);
		$daysRenovacion =  $vl_arreglo[0][0];
		if($daysRenovacion > 0)
		{
			FN_NET_LOGGER("Msisdn1:".$this->vlc_msisdn.".Tono:".$vp_id_tono."Renovacion:$daysRenovacion");
			$prox   = fecha_cobro($daysRenovacion);
			$deleteRenovacion = "DELETE FROM renovacion WHERE msisdn='$this->vlc_msisdn' AND tono = $vp_id_tono";
			FN_NET_LOGGER("QUERY:$deleteRenovacion");
			$vl_resultado=FN_RUN_NONQUERY($deleteRenovacion,$vg_db_conexion);
		}else
		{
			$daysRenovacion = 0;
			FN_NET_LOGGER("Msisdn2:".$this->vlc_msisdn.".Tono:".$vp_id_tono."Renovacion:$daysRenovacion");
			$prox   = fecha_cobro($daysRenovacion);
		}
		
        
        $costo  = 0;
        //VERIFICAR SI ESTA EN ADVERTISEMENT
        $tiempo_vida_publicidad=FN_RUN_QUERY("SELECT tiempo_vida FROM advertisement WHERE id_tono=$vp_id_tono",1,$vg_db_conexion);
        $sqlDuracion=$sqlDuracion2="";
        if ($tiempo_vida_publicidad !="")
        {
            $sqlDuracion=",duracion";
            $sqlDuracion2=",0";
            $prox= time()+ $tiempo_vida_publicidad;
        }                

        $query = "INSERT INTO billing_sched (msisdn,inicial,proximo,costo,tono,obs $sqlDuracion) VALUES ".
                "('".$this->vlc_msisdn."',unix_timestamp(),$prox,'$costo',$vp_id_tono,'$descripcion' $sqlDuracion2)";
        $vl_resultado = false;
        $vl_resultado = FN_RUN_NONQUERY($query,$vg_db_conexion);
        if ($vl_resultado == true)
        {
            $fecha = fecha_alerta($daysRenovacion);
            $this->vlc_mensaje= "BackTones Tigo: El backtone tiene vigencia hasta ". fecha($prox).". ".
                    "En esa fecha, le enviaremos un SMS para confirmar si desea mantener el backtone activo. ".
                    "Para eliminar el backtone ingresa al menu Mis Backtones. Costo de mantenimiento del Backtone Bs 3,5.";
            $this->vlc_mensaje = str_replace("'","\'",$this->vlc_mensaje);

            $query = "INSERT INTO alert_sms (fecha,msisdn,texto) VALUES ($fecha,'$msisdn','$this->vlc_mensaje')";
            $vl_resultado = FN_RUN_NONQUERY($query,$vg_db_conexion);

            return true;
        }
        else
        {
            return false;
        }

        

    }

    function MTD_DB_DESACTIVAR_BACKTONE($vp_id_tono_quitar)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Desactivar Tonos Asignados al usuario -> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_quitar."");
        $vl_arreglo_tonos  = array();
        //$vl_sql_tono           = "SELECT tonos from usuarios where msisdn='$this->vlc_msisdn';";
        $vl_arreglo_backtones  = $this->MTD_DB_LISTAR_TONOS();



        //VERIFICAR SI EXISTE EL TONO                
        $vl_contador           = 0;
        $vl_posee_eltono       = false;
        $vl_tonos_asignados        = "";
        $vl_cantidad_tonos = sizeof($vl_arreglo_backtones) -1;


        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_quitar == $vl_arreglo_backtones[$vl_contador][0])
           {
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][0].":";
               $vl_tonos_asignados.="0:";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][4]."|";
               FN_NET_LOGGER("Desactivar Backtones al usuario > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar -> Tono encontrado:$vp_id_tono_quitar ");
           }
           else
           {
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][0].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][1].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][4]."|";
           }

           $vl_contador++;
        }
        FN_NET_LOGGER("Desactivar Backtones al usuario > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_quitar -> quedo asi:$vl_tonos_asignados");
        $vl_sql_nuevo_tono="UPDATE usuarios  set tonos='$vl_tonos_asignados' where msisdn='".$this->vlc_msisdn."'  limit 1;";
        $vl_resultado=false;
        $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
        if ($vl_resultado == true)
        {
           return true;
        }
        else
        {
            FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
            return false;
        }
    }
    function MTD_DB_ACTIVAR_BACKTONE($vp_id_tono_activar)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("Activar Tonos Asignados al usuario -> msisdn:".$this->vlc_msisdn." tono:".$vp_id_tono_activar."");
        $vl_arreglo_tonos  = array();
        //$vl_sql_tono           = "SELECT tonos from usuarios where msisdn='$this->vlc_msisdn';";
        $vl_arreglo_backtones  = $this->MTD_DB_LISTAR_TONOS();



        //VERIFICAR SI EXISTE EL TONO
        $vl_contador           = 0;
        $vl_posee_eltono       = false;
        $vl_tonos_asignados        = "";
        $vl_cantidad_tonos = sizeof($vl_arreglo_backtones) -1;


        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_tono_activar == $vl_arreglo_backtones[$vl_contador][0])
           {
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][0].":";
               $vl_tonos_asignados.="1:";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][4]."|";
               FN_NET_LOGGER("Activar Backtones al usuario > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_activar -> Tono encontrado:$vp_id_tono_activar ");
           }
           else
           {
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][0].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][1].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_backtones[$vl_contador][4]."|";
           }

           $vl_contador++;
        }
        FN_NET_LOGGER("Activar Backtones al usuario > Msisdn:".$this->vlc_msisdn." tono :$vp_id_tono_activar -> quedo asi:$vl_tonos_asignados");
        $vl_sql_nuevo_tono="UPDATE usuarios  set tonos='$vl_tonos_asignados' where msisdn='".$this->vlc_msisdn."'  limit 1;";
        $vl_resultado=false;
        $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
        if ($vl_resultado == true)
        {
           return true;
        }
        else
        {
            FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
            return false;
        }
    }
    function MTD_RETORNAR_MENSAJE()
    {
        return $this->vlc_mensaje;
        //return  $this->vlc_codigo_menues;
    }
    function MTD_DB_ELIMINAR_BACKTONES($vp_id_backtone)
    {
        global $vg_db_conexion;
        FN_NET_LOGGER("DB_ELIMINAR_BACKTONES -> msisdn:".$this->vlc_msisdn." tono:".$vp_id_backtone."");
        $vl_arreglo_tonos_asignados = array();
        $vl_sql_tono           = "SELECT tonos from usuarios where msisdn='$this->vlc_msisdn';";
        $vl_tonos_adquiridos   = FN_RUN_QUERY($vl_sql_tono, 1,$vg_db_conexion);


        //QUITAR  EL TONO DE USUARIOS
        $vl_arreglo_tonos_asignados = $this->MTD_DB_LISTAR_TONOS();
        $vl_cantidad_tonos     = sizeof($vl_arreglo_tonos_asignados) -1;
        $vl_contador           = 0;
        $vl_posee_eltono       = false;
        $vl_tonos_asignados        = "";


        while ($vl_contador < $vl_cantidad_tonos)
        {
           if ($vp_id_backtone != $vl_arreglo_tonos_asignados[$vl_contador][0])
           {
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][0].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][1].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][2].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][3].":";
               $vl_tonos_asignados.=$vl_arreglo_tonos_asignados[$vl_contador][4]."|";
           }           
           $vl_contador++;
        }
        FN_NET_LOGGER("DB_ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quedo asi:$vl_tonos_asignados");
        $vl_sql_nuevo_tono="UPDATE usuarios set tonos='$vl_tonos_asignados' where msisdn='".$this->vlc_msisdn."' limit 1;";
        $vl_resultado=false;
        $vl_resultado=FN_RUN_NONQUERY($vl_sql_nuevo_tono,$vg_db_conexion);
        if ($vl_resultado == true)
        {
            FN_NET_LOGGER("DB_ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quedo asi:$vl_tonos_asignados");
            //BORRAR DE BILLING SCHED
            $vl_sql_borrar_billing="DELETE from billing_sched where tono=$vp_id_backtone and msisdn='".$this->vlc_msisdn."' limit 1";
            $vl_resultado=false;
            FN_NET_LOGGER("DB_ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> borrando de billing sched: $vl_sql_borrar_billing");
            $vl_resultado=FN_RUN_NONQUERY($vl_sql_borrar_billing,$vg_db_conexion);
          
            //BORRAR DE ASIGNADOS
            //VERIFICAR CUALES SON LOS ANI ASIGNADOS CON EL TONO
            //--------------------------------------------------
            $vl_sql_asignados="SELECT ani,tonos from asignados WHERE msisdn='".$this->vlc_msisdn."' and (tonos LIKE '$vp_id_backtone:%' or tonos LIKE '%|$vp_id_backtone:%');";
            $vl_contador_asignados=0;
            $vl_cantidad_asignados=0;
            $vl_ani_asignado      ="";

            $vl_arreglo_asginados = array();
            $vl_arreglo_asginados = FN_RUN_QUERY($vl_sql_asignados, 2,$vg_db_conexion);
            $vl_cantidad_asignados= sizeof($vl_arreglo_asginados);
            FN_NET_LOGGER("DB_ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quitando tonos asignados a $vl_cantidad_asignados amigos");
            while ($vl_contador_asignados < $vl_cantidad_asignados)
            {
                $vl_ani_asignado=$vl_arreglo_asginados[$vl_contador_asignados][0];
                if (trim($vl_ani_asignado) != "")
                {
                    FN_NET_LOGGER("DB_ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quitando tono:$vp_id_backtone asignado a :$vl_ani_asignado ");
                    $this->MTD_DB_QUITAR_TONOS_ASIGNADOS_AMIGOS($vp_id_backtone, $vl_ani_asignado);
                }
                
                $vl_contador_asignados++;
            }

            //VERIFICAR CUALES SON LOS GRUPOS ASIGNADOS CON EL TONO
            //-----------------------------------------------------
            $vl_sql_grupos="SELECT id,tonos from grupos WHERE msisdn='".$this->vlc_msisdn."' and (tonos LIKE '$vp_id_backtone:%' or tonos LIKE '%|$vp_id_backtone:%');";
            $vl_contador_grupos=0;
            $vl_cantidad_grupos=0;
            $vl_id_grupo      ="";

            $vl_arreglo_asginados = array();
            $vl_arreglo_asginados = FN_RUN_QUERY($vl_sql_grupos, 2,$vg_db_conexion);
            $vl_cantidad_grupos= sizeof($vl_arreglo_asginados);
            FN_NET_LOGGER("ELIMINAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quitando tonos del grupo a $vl_cantidad_grupos grupos");
            while ($vl_contador_grupos < $vl_cantidad_grupos)
            {
                $vl_id_grupo=$vl_arreglo_asginados[$vl_contador_grupos][0];
                if ($vl_id_grupo > 0)
                {
                    FN_NET_LOGGER("ELIMINAR_BACKTONES > Msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> quitando tono:$vp_id_backtone asignado al grupo :$vl_id_grupo ");
                    $this->MTD_DB_QUITAR_TONOS_ASIGNADOS($vp_id_backtone, $vl_id_grupo);
                }
                
                $vl_contador_grupos++;
            }
            FN_NET_LOGGER("ELIMINAR_BACKTONES > msisdn:".$this->vlc_msisdn." tono :$vp_id_backtone -> fin de eliminacion de backtone");
            return true;
           
        }
        else
        {
            FN_NET_LOGGER("Msisdn:".$this->vlc_msisdn.".Tono:".$this->vlc_id_tono."Error de actualizacion de nuevo tono: $vl_sql_nuevo_tono",0,"ERROR -");
            return false;
        }
    }

    function MTD_REGISTRAR_USUARIO()
    {
           global $vg_db_conexion;
           $p       = rand(1000,9999);
           $pin     = crypt($p,'aa');
           $vl_mensaje_sms  = "Tu PIN para Back tones Tigo es $p";
           
           if ($this->MTD_VALIDAR_MSISDN($this->vlc_msisdn))
           {
               //VERIFICAR SI EXISTE EL USUARIO
               $vl_sql="";
               $vl_sql="SELECT msisdn from usuarios where msisdn='".$this->vlc_msisdn."'";
               FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." verificar si existe el usuario");
               $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);

               if ($this->vlc_msisdn == $vl_arreglo_datos[0][0])
               {
                   FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." el usuario ya se encuentra registrado = ".$vl_arreglo_datos[0][0]);                                      
                   return "1";
               }
               else
               {
                   FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." el usuario es nuevo");
                   $fecha = time();
                   FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." nuevo pin:$p");
                   //ENVIO AL ASYNC
                   $vf_reason=1;
                   $vf_isquery=0;
                   $vf_tono =0;
                   $vf_respuesta_async= FN_SEND_ASYNC($this->vlc_msisdn,$vf_tono ,$vf_reason,$vf_isquery);
                   FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." usuario registrado async responde:$vf_respuesta_async");

                   //VERIFICAR SI EL ASYNC INSERTO
                   $vl_sql="SELECT msisdn from usuarios where msisdn='".$this->vlc_msisdn."'";
                   FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." verificar si existe el usuario");
                   $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);

                   if ($this->vlc_msisdn != $vl_arreglo_datos[0][0])
                   {
                        //REGISTRANDO EL USUARIO EN LA PLATAFORMA
                       FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." el async no inserto el registro");
                       $vl_sql = "INSERT INTO usuarios (msisdn,planid,tonos,modo,param,inicio,pin) VALUES ('".$this->vlc_msisdn."',8,'',2,'',$fecha,'$pin')";
                       $vl_resultado=false;
                       $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                       FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." usuario registrado en db");
                       FN_NET_SMS($vl_mensaje_sms,$this->vlc_msisdn);
                       return "1";
                   }
                   else
                   {
                       FN_NET_LOGGER("REGISTRAR_USUARIO >".$this->vlc_msisdn." el async inserto el registro");
                       $vl_sql="";
                       $vl_sql ="UPDATE usuarios SET pin='$pin' where msisdn='".$this->vlc_msisdn."'";
                       $vl_resultado=false;
                       $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                       $vl_mensaje= "Tu PIN para Back tones Tigo es $p";
                       //FN_NET_SMS($vl_mensaje_sms,$this->vlc_msisdn);
                       return "1";
                   }
               }
               //ENVIAR SMS

           }
           else
           {
                FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$this->vlc_msisdn." > Msdisdn invalido no se proceso el envio");
                return "-1";

           }

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
            if ($vl_resultado < 300)
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
    function MTD_ACTUALIZAR_PIN_USUARIO($vp_msisdn)
    {
           global $vg_db_conexion;
           $this->vlc_msisdn=$vp_msisdn;
           $p       = rand(1000,9999);
           $pin     = crypt($p,'aa');
            if ($this->MTD_CHEQUEO_REINTENTO() == false)
            {
                return "-5";
            }

           $vl_mensaje_sms  = "Tu PIN para Back tones Tigo es $p";

           FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." nuevo pin:$p");
           if ($this->MTD_VALIDAR_MSISDN($vp_msisdn))
           {
               //VERIFICAR SI EXISTE EL USUARIO
               $vl_sql="";
               $vl_sql="SELECT msisdn from usuarios where msisdn='".$vp_msisdn."'";
               FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." verificar si existe el usuario");
               $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);

               if ($vp_msisdn == $vl_arreglo_datos[0][0])
               {
                   FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." el usuario ya se encuentra registrado = ".$vl_arreglo_datos[0][0]);
                   $vl_sql="";
                   $vl_sql ="UPDATE usuarios SET pin='$pin' where msisdn='".$vp_msisdn."'";
                   $vl_resultado=false;
                   $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                   $vl_mensaje= "Tu PIN para Back tones Tigo es $p";
                   FN_NET_SMS($vl_mensaje_sms,$vp_msisdn);
                   return "1";
               }
               else
               {
                   FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." el usuario es nuevo");
                   $fecha = time();
                  
                   //ENVIO AL ASYNC
                   $vf_reason=1;
                   $vf_isquery=0;
                   $vf_tono =0;                   
                   $vf_respuesta_async= FN_SEND_ASYNC($vp_msisdn,$vf_tono ,$vf_reason,$vf_isquery);
                   FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." usuario registrado async responde:$vf_respuesta_async");

                   //VERIFICAR SI EL ASYNC INSERTO
                   $vl_sql="SELECT msisdn from usuarios where msisdn='".$vp_msisdn."'";
                   FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." verificar si existe el usuario");
                   $vl_arreglo_datos= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);

                   if ($vp_msisdn != $vl_arreglo_datos[0][0])
                   {
                        //REGISTRANDO EL USUARIO EN LA PLATAFORMA
                       FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." el async no inserto el registro");
                       $vl_sql = "INSERT INTO usuarios (msisdn,planid,tonos,modo,param,inicio,pin) VALUES ('".$vp_msisdn."',8,'',2,'',$fecha,'$pin')";
                       $vl_resultado=false;
                       $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                       FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." usuario registrado en db");
                       FN_NET_SMS($vl_mensaje_sms,$vp_msisdn);
                       return "1";
                   }
                   else
                   {
                       FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." el async inserto el registro");
                       $vl_sql="";
                       $vl_sql ="UPDATE usuarios SET pin='$pin' where msisdn='".$vp_msisdn."'";
                       $vl_resultado=false;
                       $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                       $vl_mensaje= "Tu PIN para Back tones Tigo es $p";
                       FN_NET_SMS($vl_mensaje_sms,$vp_msisdn);
                       return "1";
                   }
               }             
               //ENVIAR SMS               

           }
           else
           {
                FN_NET_LOGGER("ACTUALIZAR_PIN_USUARIO >".$vp_msisdn." > Msdisdn invalido no se proceso el envio");
                return "-1";

           }
         
    }
    function MTD_VALIDAR_MSISDN($vp_msisdn)
    {
        global $vg_db_conexion, $vg_conf_prefijo,$vg_conf_cantidad_digitos,$vg_conf_cantidad_prefijo;
        $vl_ani=trim($vp_msisdn);

        $vl_prefijo = substr($vl_ani,0,$vg_conf_cantidad_prefijo);
        $vl_valor_ok = 0;
        $vl_sql_check="";
        $vl_arreglo_check=array();
        //CHEQUEO DE PREFIJO
        if ((in_array($vl_prefijo, $vg_conf_prefijo)) && (strlen($vl_ani) == $vg_conf_cantidad_digitos ))
        {
            //CHEQUEAR QUE SEA NUMERICO
            if (is_numeric($vp_msisdn))
            {
                 //NUMERO VALIDO
                  FN_NET_LOGGER("VALIDAR_MSISDN >".$vp_msisdn." numero valido");
                 return true;
            }
            else
            {
                FN_NET_LOGGER("VALIDAR_MSISDN >".$vp_msisdn." numero invalido > no numerico");
                return false;
            }

        }
        else
        {
                FN_NET_LOGGER("VALIDAR_MSISDN >".$vp_msisdn." numero invalido");
                return false;
        }
    }
}
?>

