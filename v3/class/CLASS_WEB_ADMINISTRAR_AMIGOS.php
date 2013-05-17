<?php
class CLASS_WEB_ADMINISTRAR_AMIGOS
{
    private $vlc_codigo_html;
    private $vlc_msisdn;

    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_msisdn="";
        $this->vlc_msisdn= $_SESSION['msisdn'];
        include_once('class/CLASS_USUARIOS_BACKTONES.php');
        $vl_lista_amigos ="";
        $vl_lista_amigos = $this->MTD_LISTA_AMIGOS();
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-amigos.html');
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-amigos}', $vl_lista_amigos, $this->vlc_codigo_html);                      
    }  
    function MTD_DB_LISTAR_AMIGOS()
    {
        global $vg_db_conexion;
        $vlf_sql = "
            SELECT
                msisdn,
                ani,
                tonos,
                modo,
                param,
                nombre
                FROM asignados
                WHERE
                msisdn='".$this->vlc_msisdn."';";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6,$vg_db_conexion);
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
        $vl_opciones="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_numero_amigo = $vl_arreglo_datos[$vl_contador][1];
            $vl_nombre_amigo = $vl_arreglo_datos[$vl_contador][5];
            
            $vl_template.="<li id='lista-de-amigos' ><a href='#' ";
            $vl_template.="onclick=javascript:MTD_EDITAR_AMIGO('".$vl_numero_amigo."')";
            $vl_template.="> [$vl_numero_amigo] - $vl_nombre_amigo </a></li>";
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
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    function MTD_AGREGAR_AMIGOS ($vp_ani, $vp_nombre_ani)
    {
        global $vg_db_conexion, $vg_conf_prefijo,$vg_conf_cantidad_digitos;
        FN_NET_LOGGER("Agregar amigos: > msisdn:$this->vlc_msisdn ani:$vp_ani  nombre:$vp_nombre_ani ",2);
        $vl_nombre_ani=trim($vp_nombre_ani);
        include_once('class/CLASS_USUARIOS_BACKTONES.php');
        $obj_usuarios_backtone = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtone->MTD_ASIGNAR_MSISDN($vp_ani);
        
        //CHEQUEO DE PREFIJO
        if ($obj_usuarios_backtone->MTD_VALIDAR_MSISDN($vp_ani) == true)
        {
                //CHEQUEO DE VALOR NULO
                $vl_sql_check="SELECT ani from asignados where msisdn='".$this->vlc_msisdn."' AND ani='".$vp_ani."'";
                $vl_arreglo_check=  FN_RUN_QUERY($vl_sql_check, 1, $vg_db_conexion);
                if ($vl_arreglo_check[0][0] ==$vp_ani )
                {
                     FN_NET_LOGGER("Agregar amigos: > msisdn:$this->vlc_msisdn ani:$vp_ani  nombre:$vp_nombre_ani , fallo: amigo actualmente ya ingresado",2);
                    return false;
                }
                if ($vl_nombre_ani != "")
                {
                    $vl_sql = "INSERT INTO asignados (msisdn, ani,modo,nombre,tonos) VALUES ('$this->vlc_msisdn','$vp_ani',2,'$vl_nombre_ani','')";
                    $vl_resultado = false;
                    $vl_resultado = FN_RUN_NONQUERY($vl_sql ,$vg_db_conexion);
                    return $vl_resultado;
                }
                else
                {
                    FN_NET_LOGGER("Agregar amigos: > msisdn:$this->vlc_msisdn ani:$vp_ani  nombre:$vp_nombre_ani , fallo: nombre nulo",2);
                    return false;
                }
        }
        else
        {
            FN_NET_LOGGER("Agregar amigos: > msisdn:$this->vlc_msisdn ani:$vp_ani  nombre:$vp_nombre_ani , fallo: numero invalido",2);
            return false;
        }              
       
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
	AND t.categoria <> 207	
	AND FIND_IN_SET(t.id,'$vp_lista_tonos');";
        
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }

    function MTD_FORMATEAR_LISTA_TONOS($vp_arreglo_tonos)
    {
        global $vg_conf_path_tonos,$vg_conf_simular_tonos;
        $vl_cantidad_tonos=sizeof($vp_arreglo_tonos) ;
        $vl_contador=0;
        $vl_lista_tonos="";
        $vl_id_tono="";
        $vl_nombre_tono="";
        $vl_path_tono="";
        $vl_coma_final=",";
        $vl_tono_asignado=0;
      

        while( $vl_contador < $vl_cantidad_tonos)
        {
            $vl_id_tono      =$vp_arreglo_tonos[$vl_contador][0];
            $vl_nombre_tono  =$vp_arreglo_tonos[$vl_contador][1];
            $vl_tono_asignado=$vp_arreglo_tonos[$vl_contador][5];
            //$vl_path_tono   =$vp_arreglo_tonos[$vl_contador][2];
            if ($vg_conf_simular_tonos == true)
            {
                $vl_path_tono   ="$vg_conf_path_tonos/tono".$vl_contador.".mp3";
            }
            else
            {
                $vl_path_tono   =$vg_conf_path_tonos."/".$vp_arreglo_tonos[$vl_contador][2].".mp3";
            }

            $vl_lista_tonos.='{name:"'.$vl_nombre_tono.'",mp3:"'.$vl_path_tono.'",ogg:"'.$vl_path_tono.'",id:"'.$vl_id_tono.'",asignado:"'.$vl_tono_asignado.'"}';

            if ($vl_contador != ($vl_cantidad_tonos -1))
            {
                $vl_lista_tonos.=$vl_coma_final;
            }
            $vl_contador++;
        }
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
    function MTD_ASIGNAR_TONOS_AMIGO($vp_ani, $vp_tono_amigo)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado = $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS_ASIGNADOS_AMIGOS($vp_tono_amigo, $vp_ani);
        if ($vl_resultado == true)
        {
            //ENVIANDO A ASYNC REGISTRO DE ASIGNACION DE
            $vf_reason=22;
            $vf_isquery=0;
            $vl_resultado_regalo= FN_SEND_ASYNC($this->vlc_msisdn,$vp_tono_amigo,$vf_reason,$vf_isquery,$vp_ani);
            return $this->MTD_EDITAR_TONOS_AMIGO($vp_ani);
        }
        else
        {
            return "-1";
        }        
    }
    function MTD_DESASIGNAR_TONOS_AMIGO($vp_ani,$vp_tono_amigo)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado = $obj_usuarios_backtones->MTD_DB_QUITAR_TONOS_ASIGNADOS_AMIGOS($vp_tono_amigo, $vp_ani);
        if ($vl_resultado == true)
        {
            //ENVIANDO A ASYNC REGISTRO DE DESASIGNACION DE TONO
            $vf_reason=23;
            $vf_isquery=0;
            $vl_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_tono_amigo,$vf_reason,$vf_isquery,$vp_ani);
            return $this->MTD_EDITAR_TONOS_AMIGO($vp_ani);
        }
        else
        {
            return "-1";
        }
    }
    function MTD_ACTUALIZAR_DATOS_AMIGO($vp_ani,$vp_nombre_amigo)
    {
        global $vg_db_conexion;
        $vl_resultado = false;
        if ($vp_nombre_amigo != "")
        {
            $vl_sql  = "UPDATE asignados set nombre='$vp_nombre_amigo' where msisdn='".$this->vlc_msisdn."' and ani='$vp_ani' limit 1;";
            FN_NET_LOGGER("Actualizar datos del amigo >  msisdn:$this->vlc_msisdn ani:$vp_ani  nombre:$vp_nombre_amigo, sql:$vl_sql   ",2);
            $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
            if ($vl_resultado == true)
            {
                 return $vl_resultado;
            }
            else
            {
                return "-1";
            }
        }
        else
        {
            return "-1";
        }                
        return $vl_resultado;        
    }
    function MTD_ELIMINAR_AMIGO($vp_ani)
    {
        global $vg_db_conexion;
        $vl_resultado = false;
        $vl_resultado2 = false;
        $vl_sql  = "DELETE FROM asignados  where msisdn='".$this->vlc_msisdn."' and ani='$vp_ani' limit 1;";
        FN_NET_LOGGER("Eliminar amigo >  msisdn:$this->vlc_msisdn ani:$vp_ani   sql:$vl_sql   ",2);
        $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
        $vl_sql  = "select ng.id from grupos as g, numeros_grupos as ng where g.id = ng.groupid and g.msisdn='.$this->vlc_msisdn.' and ng.ani='$vp_ani'";
        $vl_arreglo= array();
        $vl_arreglo = FN_RUN_QUERY($vl_sql,1 ,$vg_db_conexion);
        if ($vl_arreglo)
        {
            $vl_cantidad_registros=0;
            $vl_cantidad_registros= sizeof ($vl_arreglo);
            $vl_contador=0;
            while ($vl_contador < $vl_cantidad_registros)
            {
                $vl_id="";
                $vl_id =  $vl_arreglo[$vl_contador][0];
                if ($vl_id)
                {
                    $vl_sql  = "DELETE FROM numeros_grupos where id=$vl_id";
                    FN_NET_LOGGER("Eliminar amigo de grupo $vl_id > msisdn :$this->vlc_msisdn ani:$vp_ani   sql:$vl_sql   ",2);
                    $vl_resultado2 = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
                }
                $vl_contador++;
            }
        }
        if ($vl_resultado == true)
        {
             return $vl_resultado;
        }
        else
        {
            return "-1";
        }
        
        return $vl_resultado;
    }
    function MTD_EDITAR_TONOS_AMIGO($vp_ani)
    {
        global $vg_db_conexion;                        
        $vl_ani         =$vp_ani;
        $vl_nombre_ani  ="";
        $vl_arreglo_tonos_asignados = array();

        //OBTENER EL NOMBRE DEL AMIGO
        //FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  Obtener el nombre del amigo",2);
        $vl_sql= "SELECT nombre from asignados where msisdn='".$this->vlc_msisdn."' and ani='".$vp_ani."';";
        $vl_arreglo_nombre_ani= array();

        //FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  Query:$vl_sql ",2);
        $vl_arreglo_nombre_ani= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
        $vl_nombre_ani=$vl_arreglo_nombre_ani[0][0];

        //FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  Nombre amigo:$vl_nombre_ani ",2);
        //OBTENER LOS TONOS DISPONIBLES A ASIGNARLE AL  AMIGO
        
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones ->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_arreglo_tonos           = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS();
        $vl_arreglo_tonos_asignados = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS_ASIGNADOS($vl_ani);
        $vl_lista_tonos = "";
        $vl_lista_tonos_formateada="";        
        //123:1:1:0:1|
        //0 msisdn
        //1 flag activo usuario
        //2 flag ?
        //3 adv
        //4 activo/mora
        //BUSCAR TONOS ACTIVOS
        $vl_cantidad_registros = sizeof($vl_arreglo_tonos) -1 ;
        $vl_contador=0;

        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_arreglo_tonos[$vl_contador][4] == 1)
            {
                $vl_lista_tonos.=$vl_arreglo_tonos[$vl_contador][0].",";
            }
            $vl_contador++;
        }
        //OBTENER LA LISTA DE TONOS DISPONIBLES A ASIGNAR AL AMIGO
        $vl_arreglo_tonos_disponibles = array();
        FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  nombre:$vl_nombre_ani, lista tonos: $vl_lista_tonos ",2);
        $vl_arreglo_tonos_disponibles = $this->MTD_DB_LISTAR_TONOS($vl_lista_tonos);

        //VERIFICAR SI TIENE ASIGNADO O NO EL TONO EL USUARIO
        // 
        $vl_contador=0;
        $vl_contador2=0;
        $vl_cantidad_registros2= sizeof($vl_arreglo_tonos_asignados);
        $vl_cantidad_registros = sizeof($vl_arreglo_tonos_disponibles);
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_contador2=0;
            $vl_arreglo_tonos_disponibles[$vl_contador][5]=0;
            while ($vl_contador2 < $vl_cantidad_registros2 )
            {
                //VERIFICAR SI EL ID DEL TONO DISPONIBLE = ID TONO ASIGNADO
                if ($vl_arreglo_tonos_disponibles[$vl_contador][0] == $vl_arreglo_tonos_asignados[$vl_contador2][0])
                {
                    FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  nombre:$vl_nombre_ani, Se detecto tono asignado al usuario:".$vl_arreglo_tonos_disponibles[$vl_contador][0],2);
                    if ($vl_arreglo_tonos_disponibles[$vl_contador][5] == 0)
                    {
                        $vl_arreglo_tonos_disponibles[$vl_contador][5]=1;
                    }
                    
                }                
                $vl_contador2++;
            }
            $vl_contador++;
        }

        
        //FORMATEAR LISTA DE TONOS DISPONIBLES A ASIGNAR AL AMIGO
        FN_NET_LOGGER("Editar tonos del amigo >  msisdn:$this->vlc_msisdn ani:$vl_ani  nombre:$vl_nombre_ani, formatea lista de tonos disponibles",2);
        $vl_lista_tonos_formateada    = $this->MTD_FORMATEAR_LISTA_TONOS($vl_arreglo_tonos_disponibles );
        //FN_NET_LOGGER("lista formateada:[$vl_lista_tonos_formateada]",2);
      
        //MTD_EDITAR_TONOS_AMIGO        
        $vl_template= FN_LEER_TPL('tpl/tpl-administrar-amigos-tonos.html');
        $vl_template= FN_REEMPLAZAR('{tpl-nombre-amigo}', $vl_nombre_ani, $vl_template);
        $vl_template= FN_REEMPLAZAR('{tpl-ani-amigo}', $vl_ani, $vl_template);
        $vl_template= FN_REEMPLAZAR('{tpl-listado-backtones}', $vl_lista_tonos_formateada, $vl_template);
        return $vl_template;

    }  

}
?>
