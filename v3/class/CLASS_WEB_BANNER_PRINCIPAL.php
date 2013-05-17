<?php
class CLASS_WEB_BANNER_PRINCIPAL
{
    private $vlc_codigo_html;    
    function __construct()
    {
        $this->vlc_codigo_html="";
       
        $vl_banner_slider= array();
        //imagen
        //titulo
        //subtitulo
        //contenido
        //enlace        
        $vl_arreglo_banners = array();
        //LISTA REGISTROS SI HAY EN FLASH
        $vl_arreglo_banners = $this->MTD_DB_LISTAR_BANNERS_FLASH();
        if ($vl_arreglo_banners)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-banner-flash.html');
            $this->vlc_codigo_html= FN_REEMPLAZAR("{tpl-flash}", $vl_arreglo_banners[0][0], $this->vlc_codigo_html);
        }
        else
        {
            //SINO FLASH ENTONCES LISTA LOS 4 BANNERS QUE SE ENCUENTREN ACTIVOS
            $vl_arreglo_banners= $this->MTD_DB_LISTAR_BANNERS();
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-banner-slider.html');
            $this->MTD_APLICAR_TEMPLATE($vl_arreglo_banners);
        }       
    }
    function MTD_DB_LISTAR_BANNERS()
    {
        global $vg_db_conexion;
        $vl_sql="SELECT imagen,titulo,subtitulo,contenido,enlace from web_banner where estado=1 and is_flash=0
            ORDER BY orden asc, id_banner desc  limit 4";
        $vl_arreglo= array();
        //FN_NET_LOGGER("Listar Banners > Query: $vl_sql");
        $vl_arreglo= FN_RUN_QUERY($vl_sql,5,$vg_db_conexion);
        return $vl_arreglo;
    }
    function MTD_DB_LISTAR_BANNERS_FLASH()
    {
        global $vg_db_conexion;
        $vl_sql="SELECT imagen from web_banner where estado=1 and is_flash=1
            ORDER BY  id_banner desc  limit 1";
        $vl_arreglo= array();
        //FN_NET_LOGGER("Listar Banners > Query: $vl_sql");
        $vl_arreglo= FN_RUN_QUERY($vl_sql,1,$vg_db_conexion);
        return $vl_arreglo;
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {

        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        $this->vlc_lista_genero="";
        $vl_template="";
        $vl_template=$this->vlc_codigo_html;
        $vl_banner=0;

        while ($vl_contador < $vl_cantidad_registros)
        {
            
            //{tpl-banner-imagen-1}
            //{tpl-banner-titulo-1}
            //{tpl-banner-subtitulo-1}
            //{tpl-banner-contenido-1}
            //{tpl-banner-link-1}

            $imagen=$vp_arreglo_datos[$vl_contador][0];
            $titulo=FN_STRING_LIMITE($vp_arreglo_datos[$vl_contador][1],25);
            $left_titulo=FN_STRING_LIMITE($vp_arreglo_datos[$vl_contador][1],15);
            $subtitulo= FN_STRING_LIMITE($vp_arreglo_datos[$vl_contador][2],18);
            $contenido=FN_STRING_LIMITE($vp_arreglo_datos[$vl_contador][3],100);
            $link=$vp_arreglo_datos[$vl_contador][4];
            $vl_banner=$vl_contador +1;

            $vl_template= FN_REEMPLAZAR("{tpl-banner-imagen-$vl_banner}",$imagen , $vl_template);
            $vl_template= FN_REEMPLAZAR("{tpl-banner-titulo-$vl_banner}", $titulo, $vl_template);
            $vl_template= FN_REEMPLAZAR("{tpl-banner-left-titulo-$vl_banner}", $left_titulo, $vl_template);
            $vl_template= FN_REEMPLAZAR("{tpl-banner-subtitulo-$vl_banner}",$subtitulo , $vl_template);
            $vl_template= FN_REEMPLAZAR("{tpl-banner-contenido-$vl_banner}",$contenido , $vl_template);
            $vl_template= FN_REEMPLAZAR("{tpl-banner-link-$vl_banner}",$link , $vl_template);
            
            $vl_contador++;
        }
        $this->vlc_codigo_html= $vl_template;
        return true;
    }    
    function MTD_RETORNAR_CODIGO_HTML()
    {
       return $this->vlc_codigo_html;

        //return  $this->vlc_codigo_menues;
    }

}
?>