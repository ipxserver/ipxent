<?php
class CLASS_WEB_ADMINISTRAR_GRUPOS
{
    private $vlc_codigo_html;
    private $vlc_msisdn;

    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_msisdn="";
        $this->vlc_msisdn= $_SESSION['msisdn'];
        include_once('class/CLASS_USUARIOS_BACKTONES.php');
        $vl_lista_grupos ="";        
        $vl_lista_grupos = $this->MTD_LISTA_GRUPOS();        
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-grupos.html');        
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-grupos}', $vl_lista_grupos, $this->vlc_codigo_html);
    }
    function MTD_DB_LISTAR_GRUPOS()
    {
        global $vg_db_conexion;
        $vlf_sql = "
            SELECT
                g.id    as id_grupo,
                g.nombre as grupo,
                count(ng.ani) 
                FROM grupos as g
                left join numeros_grupos as ng ON (g.id = ng.groupid)
                WHERE
                g.msisdn='".$this->vlc_msisdn."'
                group by g.nombre;";
        //FN_NET_LOGGER("Realiza query de busqueda de grupos:$vlf_sql ");
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_LISTA_GRUPOS()
    {
        $vl_arreglo_datos = array();
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_GRUPOS();
        $vl_cantidad_registros= sizeof($vl_arreglo_datos);
        $vl_contador=0;
        $vl_template="<ul>";
        $vl_nombre_grupo;
        $vl_id_grupo;
        $vl_cantidad_amigos="";
        $vl_imagen_grupo;
        $vl_opciones="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_id_grupo        = $vl_arreglo_datos[$vl_contador][0];
            $vl_nombre_grupo    = $vl_arreglo_datos[$vl_contador][1];
            $vl_cantidad_amigos = $vl_arreglo_datos[$vl_contador][2];

            $vl_template.="<li id='lista-de-grupos' ><a href='#' ";
            $vl_template.="onclick=javascript:MTD_EDITAR_GRUPO('".$vl_id_grupo."')";
            if ($vl_cantidad_amigos > 0)
            {
                $vl_template.="> $vl_nombre_grupo   - ($vl_cantidad_amigos) amigos </a></li>";
            }
            else
            {
                $vl_template.="> $vl_nombre_grupo </a></li>";
            }            
            $vl_contador++;
        }
        $vl_template.="</ul>";   
        return $vl_template;
    }

    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;        
    }

    function MTD_AGREGAR_GRUPOS ($vp_nombre_grupo)
    {
        global $vg_db_conexion, $vg_conf_prefijo,$vg_conf_cantidad_digitos;
        FN_NET_LOGGER("Agregar grupos: > msisdn:$this->vlc_msisdn nombre:$vp_nombre_grupo",2);
        
        $vl_nombre_grupo=trim($vp_nombre_grupo);               
        $vl_arreglo_check=array();
        
        //CHEQUEO DE VALOR NULO
        $vl_sql_check="SELECT nombre from grupos where msisdn='".$this->vlc_msisdn."' and nombre='$vl_nombre_grupo';";

        $vl_arreglo_check=  FN_RUN_QUERY($vl_sql_check, 1, $vg_db_conexion);
        FN_NET_LOGGER("WTF: [".$vl_arreglo_check[0][0]."] =? [".$vl_nombre_grupo."] ",2);
        if ($vl_arreglo_check[0][0] ==$vl_nombre_grupo )
        {
             FN_NET_LOGGER("Agregar grupos: > msisdn:$this->vlc_msisdn   nombre:$vp_nombre_ani , fallo: grupo actualmente ya ingresado",2);
            return false;
        }
        if ($vl_nombre_grupo != "")
        {
            $vl_sql = "INSERT INTO grupos (msisdn, modo,nombre,tonos, param) VALUES ('$this->vlc_msisdn',2,'$vl_nombre_grupo','','')";
            $vl_resultado = false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql ,$vg_db_conexion);
            return $vl_resultado;
        }
        else
        {
            FN_NET_LOGGER("Agregar grupos: > msisdn:$this->vlc_msisdn  nombre:$vp_nombre_ani , fallo: nombre de grupo nulo",2);
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
	AND FIND_IN_SET(t.id,'$vp_lista_tonos');";

        //echo "<h1>QUE BOLA:[$vlf_sql]<h1><br>";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }

    function MTD_FORMATEAR_LISTA_TONOS($vp_arreglo_tonos)
    {
        global $vg_conf_path_tonos,$vg_conf_simular_tonos;
        $vl_cantidad_tonos=sizeof($vp_arreglo_tonos);
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
    function MTD_ASIGNAR_TONOS_GRUPO($vp_id_grupo,$vp_tono_grupo)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado = $obj_usuarios_backtones->MTD_DB_AGREGAR_TONOS_ASIGNADOS($vp_tono_grupo, $vp_id_grupo);
        if ($vl_resultado == true)
        {
            //ENVIANDO A ASYNC REGISTRO DE SASIGNACION DE TONO
            $vf_reason=25;
            $vf_isquery=0;
            $vl_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_tono_grupo,$vf_reason,$vf_isquery);
           return $this->MTD_EDITAR_TONOS_GRUPO($vp_id_grupo);
        }
        else
        {
            return "-1";
        }
    }
    function MTD_DESASIGNAR_TONOS_GRUPO($vp_id_grupo,$vp_tono_grupo)
    {
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);
        $vl_resultado=false;
        $vl_resultado = $obj_usuarios_backtones->MTD_DB_QUITAR_TONOS_ASIGNADOS($vp_tono_grupo, $vp_id_grupo);
        if ($vl_resultado == true)
        {
            //ENVIANDO A ASYNC REGISTRO DE SASIGNACION DE TONO
            $vf_reason=26;
            $vf_isquery=0;
            $vl_resultado_async= FN_SEND_ASYNC($this->vlc_msisdn,$vp_tono_grupo,$vf_reason,$vf_isquery);
           return $this->MTD_EDITAR_TONOS_GRUPO($vp_id_grupo);
        }
        else
        {
            return "-1";
        }
    }
    function MTD_ACTUALIZAR_DATOS_GRUPO($vp_id_grupo,$vp_nombre_grupo)
    {
        global $vg_db_conexion;
        $vl_resultado = false;
        if ($vp_nombre_grupo != "")
        {
            $vl_sql     = "UPDATE grupos set nombre='$vp_nombre_grupo' where msisdn='".$this->vlc_msisdn."' and id=$vp_id_grupo limit 1;";
            FN_NET_LOGGER("Actualizar datos del grupo >  msisdn:$this->vlc_msisdn id:$vp_id_grupo  nombre:$vp_nombre_grupo, sql:$vl_sql   ",2);
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
    function MTD_EDITAR_TONOS_GRUPO($vp_id_grupo)
    {
        global $vg_db_conexion;
        $vl_id_grupo    =$vp_id_grupo;
        $vl_nombre_grupo  ="";
        $vl_arreglo_tonos_asignados = array();

        //OBTENER EL NOMBRE DEL GRUPO
        FN_NET_LOGGER("Editar tonos del grupo >  msisdn:$this->vlc_msisdn grupo: $vp_id_grupo > Obtener el nombre del grupo",2);
        $vl_sql= "SELECT nombre from grupos where msisdn='".$this->vlc_msisdn."' and id=".$vl_id_grupo.";";
        $vl_arreglo_nombre_grupo= array();       
        $vl_arreglo_nombre_grupo= FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
        $vl_nombre_grupo=$vl_arreglo_nombre_grupo[0][0];
        
        //OBTENER LOS TONOS DISPONIBLES A ASIGNARLE AL  GRUPO
        $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
        $obj_usuarios_backtones ->MTD_ASIGNAR_MSISDN($this->vlc_msisdn);

        //LISTA TODOS LOS TONOS ASIGNADOS AL USUARIO
        $vl_arreglo_tonos           = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS();

        //LISTA TODOS LOS TONOS ASIGNADOS AL GRUPO
        $vl_arreglo_tonos_asignados = $obj_usuarios_backtones->MTD_DB_LISTAR_TONOS_GRUPOS($vl_id_grupo);
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

        //OBTENER LA LISTA DE TONOS DISPONIBLES A ASIGNAR AL GRUPO
        $vl_arreglo_tonos_disponibles = array();
        FN_NET_LOGGER("Editar tonos del grupo >  msisdn:$this->vlc_msisdn grupo:$vl_nombre_grupo , lista tonos: $vl_lista_tonos ",2);
        $vl_arreglo_tonos_disponibles = $this->MTD_DB_LISTAR_TONOS($vl_lista_tonos);

        //VERIFICAR SI TIENE ASIGNADO O NO EL TONO EL GRUPO
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
                    //FN_NET_LOGGER("Editar tonos del grupo >  msisdn:$this->vlc_msisdn  nombre:$vl_nombre_grupo, Se detecto tono asignado al usuario:".$vl_arreglo_tonos_disponibles[$vl_contador][0],2);
                    if ($vl_arreglo_tonos_disponibles[$vl_contador][5] == 0)
                    {
                        $vl_arreglo_tonos_disponibles[$vl_contador][5]=1;
                    }

                }
                $vl_contador2++;
            }
            $vl_contador++;
        }


        //FORMATEAR LISTA DE TONOS DISPONIBLES A ASIGNAR AL GRUPO
        FN_NET_LOGGER("Editar tonos del grupo >  msisdn:$this->vlc_msisdn nombre:$vl_nombre_grupo, formatea lista de tonos disponibles",2);
        $vl_lista_tonos_formateada    = $this->MTD_FORMATEAR_LISTA_TONOS($vl_arreglo_tonos_disponibles );
        //FN_NET_LOGGER("lista formateada:[$vl_lista_tonos_formateada]",2);

        //MTD_EDITAR_TONOS_GRUPO
        //
        $vl_lista_amigos="";
        $vl_lista_amigos = $this->MTD_LISTA_AMIGOS($vl_id_grupo);
        $vl_template= FN_LEER_TPL('tpl/tpl-administrar-grupos-tonos.html');
        $vl_template= FN_REEMPLAZAR('{tpl-nombre-grupo}', $vl_nombre_grupo, $vl_template);
        $vl_template= FN_REEMPLAZAR('{tpl-id-grupo}', $vl_id_grupo, $vl_template);
        $vl_template= FN_REEMPLAZAR('{tpl-listado-backtones}', $vl_lista_tonos_formateada, $vl_template);
        $vl_template= FN_REEMPLAZAR('{tpl-lista-amigos}', $vl_lista_amigos, $vl_template);
        return $vl_template;

    }
    function MTD_DB_LISTAR_AMIGOS($vp_id_grupo)
    {
        global $vg_db_conexion;
       /* $vlf_sql = "
             SELECT
                a.msisdn,
                a.ani,
                a.tonos,
                a.modo,
                a.param,
                a.nombre,
                (CASE WHEN ng.groupid > 0 THEN 1 else 0 end) as asignado
                FROM asignados as a
                left join numeros_grupos as ng ON (a.ani = ng.ani AND ng.groupid = $vp_id_grupo)
                WHERE
                msisdn='".$this->vlc_msisdn."' order by asignado desc;";
        *
        */
        $vlf_sql="
            SELECT
                a.msisdn,
                a.ani,
                a.tonos,
                a.modo,
                a.param,
                a.nombre,
                (CASE WHEN ng.groupid > 0 THEN 1 else 0 end) as asignado
                FROM asignados as a
                left join numeros_grupos as ng ON (a.ani = ng.ani AND ng.groupid = $vp_id_grupo)
                WHERE
                msisdn='".$this->vlc_msisdn."'
            UNION
            SELECT '".$this->vlc_msisdn."' as msisdn,
            ng2.ani, '' as tonos,
            2 as modo,
            '' as param,
            ng2.nombre,
            1 as asignado
            from numeros_grupos as ng2 where ng2.groupid=$vp_id_grupo and ng2.ani not in (
                            SELECT
                            a3.ani
                            FROM asignados as a3
                            left join numeros_grupos as ng3 ON (a3.ani = ng3.ani AND ng3.groupid = $vp_id_grupo)
                            WHERE
                            a3.msisdn='".$this->vlc_msisdn."')
            ORDER BY asignado desc, nombre; ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 7,$vg_db_conexion);
        //FN_NET_LOGGER("Grupos: Lista amigos sql: \n $vlf_sql");
        return $vlf_arreglo_datos;
    }
    function MTD_LISTA_AMIGOS($vp_id_grupo)
    {
        $vl_arreglo_datos = array();
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_AMIGOS($vp_id_grupo);
        $vl_cantidad_registros= sizeof($vl_arreglo_datos);
        $vl_contador=0;
        $vl_template="<ul>";
        $vl_nombre_amigo;
        $vl_numero_amigo;
        $vl_imagen_amigo;
        $vl_opciones="";
        $vl_asignado = "";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_numero_amigo = $vl_arreglo_datos[$vl_contador][1];
            $vl_nombre_amigo = $vl_arreglo_datos[$vl_contador][5];
            $vl_asignado     = $vl_arreglo_datos[$vl_contador][6];


            $vl_template.="<li id='lista-de-amigos2'><div style='float:left; margin-left:15px; vertical-align:middle;line-height:18px;width:70%;'><a href='#' ";
            $vl_template.="";
            $vl_template.="> [$vl_numero_amigo] - $vl_nombre_amigo </a></div>";
            if ($vl_asignado  == 1)
            {
                $vl_template.="<span id='btn-desasignar-backtone' style='margin-right:10px;'onClick='javascript:MTD_DESASIGNAR_AMIGO_GRUPO($vl_numero_amigo)' title='Desasignar amigo al grupo'  /></span>";
            }
            else
            {
                $vl_template.="<span id='btn-asignar-backtone' style='margin-right:10px;'onClick='javascript:MTD_ASIGNAR_AMIGO_GRUPO($vl_numero_amigo)' title='Asignar amigo al grupo'  /></span>";
            }
            
            $vl_template.="</li>";
            $vl_contador++;
        }
        $vl_template.="</ul>";
        /*
                            <ul>
                            </ul>

         *
         */

        //FN_NET_LOGGER("Grupos: Lista amigos \n $vl_template");
        return $vl_template;
    }
    function MTD_ASIGNAR_AMIGO_GRUPO($vp_id_grupo, $vp_ani_grupo)
    {
 
        global $vg_db_conexion,$vg_configuracion_regional;
        $vl_id_grupo        = $vp_id_grupo;

        if ($vg_configuracion_regional == 'PY')
        {
            $vl_ani_grupo       = "0".trim($vp_ani_grupo);
        }
        else
        {
            $vl_ani_grupo       = trim($vp_ani_grupo);
        }
        $vl_arreglo_datos   = array();
        $vl_template        ="";
        
        //VERIFICAR SI NO EXISTE EN OTRO GRUPO
        FN_NET_LOGGER("Asignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo",2);
        $vl_sql ="SELECT ani from numeros_grupos where groupid != $vl_id_grupo";
        $vl_arreglo_datos   = FN_RUN_QUERY($vl_sql , 1,$vg_db_conexion);
        
        if($vl_arreglo_datos[0][0] != $vl_ani_grupo)
        {
            //INSERTAR EL AMIGO
            FN_NET_LOGGER("Asignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo > Ingresar el amigo al grupo",2);
            $vl_sql   ="INSERT INTO numeros_grupos (groupid,ani,nombre) values ($vl_id_grupo,'$vl_ani_grupo','$vl_ani_amigo')";

            $vl_resultado=false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
            if ($vl_resultado == true)
            {
                FN_NET_LOGGER("Asignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo > Asignacion exitosa del amigo al grupo",2);
                $vl_template = $this->MTD_EDITAR_TONOS_GRUPO($vl_id_grupo);
                return $vl_template;
            }
            else
            {
                return "-1";
            }
            
        }
        else
        {
            FN_NET_LOGGER("Asignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo > Error, el amigo ya pertence a otro grupo",2);
            return "-1";
        }
    }
    function MTD_DESASIGNAR_AMIGO_GRUPO($vp_id_grupo, $vp_ani_grupo)
    {
        /*
         * TODO         
         *
         * ELIMINAR
         *
         */
        global $vg_db_conexion,$vg_configuracion_regional;
        $vl_id_grupo        = $vp_id_grupo;
        if ($vg_configuracion_regional == 'PY')
        {
            $vl_ani_grupo       = "0".trim($vp_ani_grupo);
        }
        else
        {
            $vl_ani_grupo       = trim($vp_ani_grupo);
        }
        
        $vl_arreglo_datos   = array();
        $vl_template        ="";
        
            //INSERTAR EL AMIGO
            FN_NET_LOGGER("Desasignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo > Quitar el amigo del grupo",2);
            $vl_sql   ="Delete from numeros_grupos where groupid=$vl_id_grupo and ani='$vl_ani_grupo'";

            $vl_resultado=false;
            $vl_resultado = FN_RUN_NONQUERY($vl_sql,$vg_db_conexion);
            if ($vl_resultado == true)
            {
                FN_NET_LOGGER("Desasignar amigo a grupo: $vl_id_grupo ani: $vl_ani_grupo > Desasignacion exitosa del amigo del grupo",2);
                $vl_template = $this->MTD_EDITAR_TONOS_GRUPO($vl_id_grupo);
                return $vl_template;
            }
            else
            {
                return "-1";
            }
      
    }
}
?>