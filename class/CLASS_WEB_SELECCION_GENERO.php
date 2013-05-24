<?php
class CLASS_WEB_SELECCION_GENERO
{
    private $vlc_codigo_html;
    private $vlc_lista_genero;
    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_lista_henero ="";
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-seleccion-genero.html');;

        $vl_arreglo_datos = $this->MTD_DB_LISTAR_GENERO();
        $vlc_lista_genero = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-generos}', $this->vlc_lista_genero, $this->vlc_codigo_html);
        /*
         *
         * <dd><a href='#genero' >Alternative</a></dd>
           <dd><a href='albums.php?accion=genero&idgenero=62' class="light-blue">Bluegrass</a></dd>
         * TODO:
         */
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {

        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        $this->vlc_lista_genero="";

        while ($vl_contador < $vl_cantidad_registros)
        {
            if ($vl_contador % 2 == 0)
            {
                $this->vlc_lista_genero.="<li><a href='index.php?seccion=playlist&genero=".$vp_arreglo_datos[$vl_contador][0]."'>".ucfirst(strtolower($vp_arreglo_datos[$vl_contador][1]))."</a></li>";
            }
            else
            {
                $this->vlc_lista_genero.="<li><a href='index.php?seccion=playlist&genero=".$vp_arreglo_datos[$vl_contador][0]."' class='light-blue'>".ucfirst(strtolower($vp_arreglo_datos[$vl_contador][1]))."</a></li>";
            }

            $vl_contador++;
        }
    }
    function MTD_DB_LISTAR_GENERO ()
    {
        global $vg_db_conexion;
        $vlf_sql = "SELECT id_genero, nombre FROM generos order by nombre ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}

?>