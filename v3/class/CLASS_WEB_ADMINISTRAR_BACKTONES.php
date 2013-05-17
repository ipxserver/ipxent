<?php
class CLASS_WEB_ADMINISTRAR_BACKTONES
{
    private $vlc_codigo_html;
    private $vlc_msisdn;
    private $vlc_modalidad;
    private $vlc_param;


    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_msisdn="";
        $this->vlc_msisdn= $_SESSION['msisdn'];
        include_once('class/CLASS_USUARIOS_BACKTONES.php');               
    }
    function MTD_APLICAR_TEMPLATE()
    {
        
        $this->MTD_LISTAR_MODO_ROTACION_BACTONES();
        $vl_modalidad = $this->vlc_modalidad;
        $vl_asignado1="";
        $vl_asignado2="";
        $vl_asignado3="";
        $vl_asignado4="";
        $vl_asignado5="";

        if ($vl_modalidad == 1)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-backtones.html');
            $vl_asignado1="-asignado";
        }
        elseif ($vl_modalidad == 2)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-backtones.html');
            $vl_asignado2="-asignado";
            
        }
        elseif ($vl_modalidad == 3)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-backtones-hora.html');             
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-intervalo-'.$this->vlc_param.'}', 'selected', $this->vlc_codigo_html);
            $vl_asignado3="-asignado";
        }
        elseif ($vl_modalidad == 4)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-backtones-turno.html');
            $vl_asignado4="-asignado";
           
        }
        elseif ($vl_modalidad == 5)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-backtones-fijo.html');
            $vl_asignado5="-asignado";
        }
        $vl_lista_backtones = $this->MTD_LISTAR_TONOS();
        //$vl_lista_backtones = $this-
        //aplicar modadlidad id="modo-backtone-actual"
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-asignado-1}', $vl_asignado1, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-asignado-2}', $vl_asignado2, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-asignado-3}', $vl_asignado3, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-asignado-4}', $vl_asignado4, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-asignado-5}', $vl_asignado5, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-modalidad-'.$vl_modalidad.'}', 'id="modo-backtone-actual"', $this->vlc_codigo_html);        
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-backtones}', $vl_lista_backtones, $this->vlc_codigo_html);
    }
    function MTD_LISTAR_MODO_ROTACION_BACTONES()
    {
        global $vg_db_conexion;
        $vl_sql="select modo, param from usuarios where msisdn='".$this->vlc_msisdn."';";
        $vl_arreglo = FN_RUN_QUERY($vl_sql, 2,$vg_db_conexion);
        $vl_modalidad=0;
        $this->vlc_modalidad = $vl_arreglo[0][0];
        $this->vlc_param = $vl_arreglo[0][1];
        $vl_modalidad=$this->vlc_modalidad ;
        FN_NET_LOGGER("LISTAR_MODO_ROTACION_BACTONES > usuario: ".$this->vlc_msisdn."  modo:$vl_modalidad ");
        return $vl_modalidad;
    }
    function MTD_DB_ASIGNAR_PARAM($vp_param)
    {
        global $vg_db_conexion;
        $vl_sql="UPDATE usuarios set param='$vp_param' where msisdn='".$this->vlc_msisdn."';";
        $vl_resultado= false;
        $vl_resultado= FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
        if ($vl_resultado ==true)
        {
            FN_NET_LOGGER("DB_ASIGNAR_PARAM > usuario: ".$this->vlc_msisdn."  param:$vp_param ");
            $this->vlc_param = $vp_param;
            return true;
        }
        else
        {
            FN_NET_LOGGER("DB_ASIGNAR_PARAM > usuario: ".$this->vlc_msisdn."  param:$vp_param > Error en la asignacion");
            return false;
        }      
        return false;
    }
    function MTD_ELIMINAR_BACKTONE($vp_id_backtone)
    {
        
        //ENVIAR ASYNC
        $vf_reason=6;
        $vf_isquery=0;

        //ENVIAR A ASYNC MULTI LA COMPRA
        include_once('class/CLASS_USUARIOS_BACKTONES.php');
        $obj_usu_backtones =  new CLASS_USUARIOS_BACKTONES();

        $vl_resultado = false;
        FN_NET_LOGGER("ELIMINAR_BACKTONE > Eliminar Backtone del usuario:".$this->vlc_msisdn." tono:".$vp_id_backtone." ");
        $obj_usu_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado = $obj_usu_backtones->MTD_DB_ELIMINAR_BACKTONES($vp_id_backtone);
        if ($vl_resultado == true)
        {
            FN_NET_LOGGER("ELIMINAR_BACKTONE > Envio a ASYNC:".$this->vlc_msisdn." tono:".$vp_id_backtone." Reason 6");
            $vf_resultado_async=-1;
            $vf_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_backtone,$vf_reason,$vf_isquery);
            FN_NET_LOGGER("ELIMINAR_BACKTONE > Envio a ASYNC:".$this->vlc_msisdn." tono:".$vp_id_backtone." Reason 6 > Async responde:$vf_resultado_async");
            $this->MTD_APLICAR_TEMPLATE();
            return $this->vlc_codigo_html;
        }
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        if ($this->vlc_codigo_html == "")
        {
            $this->MTD_APLICAR_TEMPLATE();
        }
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    function MTD_ASGINAR_MODALIDAD_BACKTONE($vp_modalidad)
    {
        global $vg_db_conexion;
        $vl_modalidad =$vp_modalidad;
        FN_NET_LOGGER("MTD_ASGINAR_MODALIDAD_BACKTONE> Asignacion de modalidad: $vl_modalidad usuario:".$this->vlc_msisdn);
        if (($vl_modalidad >=1) && ($vl_modalidad <=5))
        {
            $vl_sql ="UPDATE usuarios set modo=$vl_modalidad where msisdn='".$this->vlc_msisdn."';";
            $vl_resultado=false;
            $vl_resultado= FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
            if ($vl_resultado == true)
            {
                if ($vp_modalidad == 1 || $vp_modalidad == 2)
                {
                    $this->MTD_DB_ASIGNAR_PARAM('');
                }
                elseif ($vp_modalidad == 3)
                {
                    $this->MTD_DB_ASIGNAR_PARAM(1);
                }
                elseif ($vp_modalidad == 4)
                {
                    $this->MTD_DB_ASIGNAR_PARAM('::');
                }
                elseif ($vp_modalidad == 5)
                {
                    $this->MTD_DB_ASIGNAR_PARAM('');
                }
                $this->MTD_APLICAR_TEMPLATE();
                return $this->vlc_codigo_html;
            }
            else
            {
                FN_NET_LOGGER("MTD_ASGINAR_MODALIDAD_BACKTONE> Asignacion de modalidad: $vl_modalidad usuario:".$this->vlc_msisdn." > Fallo la asignacion de la modalidad");
                return "-1";
            }
        }
        else
        {
            FN_NET_LOGGER("MTD_ASGINAR_MODALIDAD_BACKTONE> Asignacion de modalidad: $vl_modalidad usuario:".$this->vlc_msisdn." > Modalidad Invalida");
        }
    }
    function MTD_LISTAR_TONOS()
    {
        include_once('class/CLASS_USUARIOS_BACKTONES.php');
        $obj_usu_backtones =  new CLASS_USUARIOS_BACKTONES();
        $vl_arreglo_backtones = array();
        $obj_usu_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_arreglo_backtones = $obj_usu_backtones->MTD_DB_LISTAR_TONOS();
        $vl_contador=0;        
        $vl_lista_tonos="";
        //BUSCAR TONOS
        $vl_cantidad_registros=0;
        $vl_cantidad_registros = sizeof($vl_arreglo_backtones) -1 ;
        $vl_contador=0;

        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_contador >= 1)
            {
                $vl_lista_tonos.=",";
            }
            $vl_lista_tonos.=$vl_arreglo_backtones[$vl_contador][0];            
            $vl_contador++;
        }
        FN_NET_LOGGER("MTD_LISTAR_TONOS -> Se encontraron los siguientes tonos:$vl_lista_tonos",5);
        //VERIFICAR CUALES BACKTONES ESTAN ACTIVOS POR USUARIO
        $vl_arreglo_tonos  = array();
        $vl_arreglo_tonos  = $this->MTD_DB_LISTAR_TONOS($vl_lista_tonos);
        $vl_lista_backtones="";
        $vl_lista_backtones= $this->MTD_FORMATEAR_LISTA_TONOS($vl_arreglo_tonos,$vl_arreglo_backtones );
        //FN_NET_LOGGER("Mis Backtones > Listado de tonos :$vl_lista_backtones ");
        return $vl_lista_backtones;
        //VERIFICAR QUE TIPO ESTA
    }
    function MTD_DB_LISTAR_TONOS($vp_lista_tonos)
    {
        /*
         * TODO: CORREGIR SQL QUE TRAE TONOS MAS DESCARGADOS Me las como!!!
         */
        global $vg_db_conexion;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path
        FROM tonos as t, autores as a
        WHERE
	t.autor = a.id
        and FIND_IN_SET(t.id,'".$vp_lista_tonos."');";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_ASIGNAR_BACKTONE_TURNO($vp_id_backtone, $vp_turno)
    {
        global $vg_db_conexion;
        $vl_tono_activo_dia="";
        $vl_tono_activo_tarde="";
        $vl_tono_activo_noche="";
        $vl_param="";        

        $this->MTD_LISTAR_MODO_ROTACION_BACTONES();
        FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno:$vp_turno dia:$vl_tono_activo_dia tarde:$vl_tono_activo_tarde noche:$vl_tono_activo_noche param: $this->vlc_param");
        list($vl_tono_activo_dia,$vl_tono_activo_tarde,$vl_tono_activo_noche) = explode(":",$this->vlc_param);

        if ($vp_turno == 1)
        {
            $vl_param= $vp_id_backtone.":".$vl_tono_activo_tarde.":".$vl_tono_activo_noche;
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno: $vp_turno > param: $vl_param");
        }
        elseif ($vp_turno == 2)
        {
            $vl_param= $vl_tono_activo_dia.":".$vp_id_backtone.":".$vl_tono_activo_noche;
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno: $vp_turno > param: $vl_param");
        }
        elseif ($vp_turno == 3)
        {
            $vl_param= $vl_tono_activo_dia.":".$vl_tono_activo_tarde.":".$vp_id_backtone;
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno: $vp_turno > param: $vl_param");
        }
        $vl_sql="UPDATE usuarios set param='$vl_param' where msisdn='".$this->vlc_msisdn."'";
        $vl_resultado=false;
        

        FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno: $vp_turno > query:$vl_sql ");
        $vl_resultado= FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
        if ($vl_resultado == true)
        {
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  asingacion  turno: $vp_turno > Asignacion exitosa de param::$vl_param ");
            $this->MTD_APLICAR_TEMPLATE();
            return $this->vlc_codigo_html;
        }                
    }
    function MTD_DESASIGNAR_BACKTONE_TURNO($vp_id_backtone, $vp_turno)
    {
        global $vg_db_conexion;
        $vl_tono_activo_dia="";
        $vl_tono_activo_tarde="";
        $vl_tono_activo_noche="";
        $vl_param="";

        $this->MTD_LISTAR_MODO_ROTACION_BACTONES();
        FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno:$vp_turno dia:$vl_tono_activo_dia tarde:$vl_tono_activo_tarde noche:$vl_tono_activo_noche param: $this->vlc_param");
        list($vl_tono_activo_dia,$vl_tono_activo_tarde,$vl_tono_activo_noche) = explode(":",$this->vlc_param);

        if ($vp_turno == 1)
        {
            $vl_param= ":".$vl_tono_activo_tarde.":".$vl_tono_activo_noche;
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno: $vp_turno > param: $vl_param");
        }
        elseif ($vp_turno == 2)
        {
            $vl_param= $vl_tono_activo_dia."::".$vl_tono_activo_noche;
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno: $vp_turno > param: $vl_param");
        }
        elseif ($vp_turno == 3)
        {
            $vl_param= $vl_tono_activo_dia.":".$vl_tono_activo_tarde.":";
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno: $vp_turno > param: $vl_param");
        }
        $vl_sql="UPDATE usuarios set param='$vl_param' where msisdn='".$this->vlc_msisdn."'";
        $vl_resultado=false;


        FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno: $vp_turno > query:$vl_sql ");
        $vl_resultado= FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
        if ($vl_resultado == true)
        {
            FN_NET_LOGGER("ASIGNAR_BACKTONE_TURNO > Ingreso  desasingacion  turno: $vp_turno > Desasignacion exitosa de param::$vl_param ");
            $this->MTD_APLICAR_TEMPLATE();
            return $this->vlc_codigo_html;
        }
    }
    function MTD_FORMATEAR_LISTA_TONOS($vp_arreglo_tonos, $vp_arreglo_backtones)
    {
        global $vg_conf_path_tonos,$vg_conf_simular_tonos;
        $vl_cantidad_tonos=sizeof($vp_arreglo_tonos);
        $vl_cantidad_backtones=sizeof($vp_arreglo_backtones);
        $vl_contador=0;
        $vl_contador2=0;
        $vl_lista_tonos="";
        $vl_id_tono="";
        $vl_nombre_tono="";
        $vl_path_tono="";
        $vl_coma_final=",";
        $vl_tono_activo=0;
        $vl_tono_vigente=0;

        //INICIALIZACION DE DATOS PARA MODALIDAD 4 (POR TURNO)
        $vl_tono_activo_dia="";
        $vl_tono_activo_tarde="";
        $vl_tono_activo_noche="";
        $vl_flag_activo_dia=0;
        $vl_flag_activo_tarde=0;
        $vl_flag_activo_noche=0;
        $vl_flag_activo_fijo=0;
        
        if ($this->vlc_modalidad == 4)
        {
            list($vl_tono_activo_dia,$vl_tono_activo_tarde,$vl_tono_activo_noche) = explode(":",$this->vlc_param);
            FN_NET_LOGGER("FORMATEAR_LISTA_TONOS > Verificacion de tonos por turnos , dia:$vl_tono_activo_dia tarde:$vl_tono_activo_tarde noche:$vl_tono_activo_noche");
        }

        //VERIFICAR CUALES BACKTONES ESTAN VIGENTES O EN MORA
        while( $vl_contador < $vl_cantidad_tonos)
        {
            $vl_id_tono      =$vp_arreglo_tonos[$vl_contador][0];
            $vl_nombre_tono  =$vp_arreglo_tonos[$vl_contador][1];            
            //$vl_path_tono   =$vp_arreglo_tonos[$vl_contador][2];
            if ($vg_conf_simular_tonos == true)
            {
                $vl_path_tono   ="$vg_conf_path_tonos/tono".$vl_contador.".mp3";
            }
            else
            {
                   $vl_path_tono   =$vg_conf_path_tonos."/".$vp_arreglo_tonos[$vl_contador][2].".mp3";
            }
            $vl_tono_activo=0;
            $vl_tono_vigente=0;
            $vl_flag_activo_fijo=0;
            //BUSCA DEL ARREGLO DE BACKTONES LOS DATOS ACTIVO Y VIGENTE
            $vl_contador2=0;
            while($vl_contador2 < $vl_cantidad_backtones)
            {
                //BUSCA DEL ARREGLO DE BACKTONES LOS DATOS ACTIVO Y VIGENTE MODALIDAD 1 & 2 & 3
                //if (($this->vlc_modalidad >=1) && ($this->vlc_modalidad
                // <= 3))
                
                if($vl_id_tono == $vp_arreglo_backtones[$vl_contador2][0])
                {
                    $vl_tono_activo =$vp_arreglo_backtones[$vl_contador2][1];
                    $vl_tono_vigente=$vp_arreglo_backtones[$vl_contador2][4];
                }
                
                if ($this->vlc_modalidad == 4)
                {
                    $vl_flag_activo_dia     =0;
                    $vl_flag_activo_tarde   =0;
                    $vl_flag_activo_noche   =0;
                    if($vl_id_tono == $vl_tono_activo_dia)
                    {
                        $vl_flag_activo_dia     =1;                     
                    }
                    if($vl_id_tono == $vl_tono_activo_tarde)
                    {                   
                        $vl_flag_activo_tarde   =1;                  
                    }
                    if($vl_id_tono == $vl_tono_activo_noche)
                    {  
                        $vl_flag_activo_noche   =1;
                    }                   
                }
                if ($this->vlc_modalidad ==5)
                {
                    if ($vl_id_tono == $this->vlc_param)
                    {
                        $vl_flag_activo_fijo=1;
                    }
                }
                $vl_contador2++;
            }

            //ASIGNA FORMATO AL ARREGLO DEL PLAYER MODALIDAD 1  - 3            
            if (($this->vlc_modalidad >=1) && ($this->vlc_modalidad <= 3))
            {
                $vl_lista_tonos.='{name:"'.$vl_nombre_tono.'",mp3:"'.$vl_path_tono.'",ogg:"'.$vl_path_tono.'",id:"'.$vl_id_tono.'",activo:"'.$vl_tono_activo.'", vigente:"'.$vl_tono_vigente.'"}';
            }
            elseif ($this->vlc_modalidad == 4)
            {
                $vl_lista_tonos.='{name:"'.$vl_nombre_tono.'",mp3:"'.$vl_path_tono.'",ogg:"'.$vl_path_tono.'",id:"'.$vl_id_tono.'",vigente:"'.$vl_tono_vigente.'",dia:"'.$vl_flag_activo_dia.'",tarde:"'.$vl_flag_activo_tarde.'",noche:"'.$vl_flag_activo_noche.'"}';
            }
            elseif ($this->vlc_modalidad == 5)
            {
                $vl_lista_tonos.='{name:"'.$vl_nombre_tono.'",mp3:"'.$vl_path_tono.'",ogg:"'.$vl_path_tono.'",id:"'.$vl_id_tono.'",activo:"'.$vl_flag_activo_fijo.'", vigente:"'.$vl_tono_vigente.'"}';
            }

            if ($vl_contador != ($vl_cantidad_tonos -1))
            {
                $vl_lista_tonos.=$vl_coma_final;
            }
            $vl_contador++;
        }
        //FN_NET_LOGGER("FORMATEAR_LISTA_TONOS > se listan los siguientes tonos:$vl_lista_tonos",5);
       // echo "<textarea>$vl_lista_tonos</textarea>";
        return $vl_lista_tonos;
    }
    function MTD_REQUERIMIENTOS_ENCABEZADO()
    {
        return '
        <link href="estilos/tigo/styles/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <link href="estilos/tigo/styles/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
        <script type="text/javascript" src="js/facebox.js" ></script>
        ';

    }
    function MTD_DESACTIVAR_BACKTONE($vp_id_backtone)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado= $obj_usuarios_backtones->MTD_DB_DESACTIVAR_BACKTONE($vp_id_backtone);
        if ($vl_resultado== true)
        {
            //ENVIANDO A ASYNC REGISTRO DE ASIGNACION DE TONO
            $vf_reason=22;
            $vf_isquery=0;
            $vl_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_backtone,$vf_reason,$vf_isquery);

            $this->MTD_APLICAR_TEMPLATE();
            return $this->vlc_codigo_html;
        }

    }
    function MTD_ACTIVAR_BACKTONE($vp_id_backtone)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado= $obj_usuarios_backtones->MTD_DB_ACTIVAR_BACKTONE($vp_id_backtone);
        if ($vl_resultado== true)
        {
            //ENVIANDO A ASYNC REGISTRO DE ASIGNACION DE TONO
            $vf_reason=21;
            $vf_isquery=0;
            $vl_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_id_backtone,$vf_reason,$vf_isquery);

            $this->MTD_APLICAR_TEMPLATE();
            return $this->vlc_codigo_html;
        }

    }

}
?>