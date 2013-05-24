<?php
class CLASS_WEB_BACKTONES_ESCUCHADOS
{
    private $vlc_codigo_html;
    private $vlc_codigo_html_tonos;

    function __construct()
    {
        $this->vlc_codigo_html="";
        $vl_lista_tonos="";
        $vl_arreglo_datos=array();
        $this->vlc_codigo_html      = FN_LEER_TPL('tpl/tpl-backtones-escuchados.html');
        $this->vlc_codigo_html_tonos= FN_LEER_TPL('tpl/tpl-backtones-escuchados-tonos.html');

        //TONOS DESCARGADOS
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_TONOS();
        if ($vl_arreglo_datos)
        {
            $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos);
        //FN_NET_LOGGER("Backtones Escuchados :>> \n $vl_lista_tonos");
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-tonos}', $vl_lista_tonos, $this->vlc_codigo_html);
        }
        else
        {
            $this->vlc_codigo_html="";
        }
        
       
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {
        // t.id, t.nombre as tono,aut.nombre as autor, alb.imagen


        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        $vl_template_tonos= "";
        $vl_tonos="";
        while ($vl_contador < $vl_cantidad_registros)
        {

            //LIMPIEZA DE VARIABLES

            $vl_template_tonos= "";
            $id_tono=0;
            $tono="";
            $autor="";
            $imagen_album="";
            $vl_posicion= $vl_contador +1;
            //ASIGNACION DE VARIABLES
            $vl_template_tonos= $this->vlc_codigo_html_tonos;
            $id_tono=$vp_arreglo_datos[$vl_contador][0];
            $tono=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][1])));
            $titulo_tono=$tono;

            $autor=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][2])));
            $titulo_autor=$autor;

            $imagen_album=$vp_arreglo_datos[$vl_contador][3];
            if ($imagen_album == "")
            {
                    $imagen_album="th-album-default.jpg";
            }      
            $link_tono="index.php?seccion=backtones&backtone=$id_tono";

            $tono=FN_STRING_LIMITE($tono, 15);
            $autor=FN_STRING_LIMITE($autor, 15);

            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-imagen-album}' , $imagen_album, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-artista}'      , $autor, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-artista}' , $link_tono, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-nombre}'       , $tono, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-ingresar}'   , $link_tono, $vl_template_tonos);
          
            $vl_tonos .= $vl_template_tonos;

            $vl_contador++;
        }
        $vl_tonos .="</div>";
        return $vl_tonos ;
    }
    function MTD_DB_LISTAR_TONOS()
    {
        global $vg_db_conexion;
        $vlf_arreglo_datos=array();
        //$_SESSION['backtones_escuchados'] ="";
        if (isset($_SESSION['backtones_escuchados']))
        {
            if ($_SESSION['backtones_escuchados'] !="")
            {
                $vlf_sql = "SELECT t.id, t.nombre as tono,aut.nombre as autor, alb.imagen
                FROM tonos as t
                inner join autores as aut on ( t.autor = aut.id)
                left join albumes as alb on (t.album = alb.id_album)
                WHERE t.categoria <> 207
                AND FIND_IN_SET(t.id,'".$_SESSION['backtones_escuchados']."') 
                ORDER BY
                FIND_IN_SET(t.id,'".$_SESSION['backtones_escuchados']."')
                    ";
                $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 4,$vg_db_conexion);
               // FN_NET_LOGGER("Backtones Escuchados QUERY: $vlf_sql");
            }
        }
        
        return $vlf_arreglo_datos;
    }

    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>