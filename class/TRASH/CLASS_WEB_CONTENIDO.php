<?php
class CLASS_WEB_CONTENIDO
{
    private $vlc_codigo_html;    
    function __construct ()
    {
        $this->vlc_codigo_html="";                
        $this->vlc_codigo_html=  FN_LEER_TPL('tpl/tpl-contenido.html');               
        
        /*
         * TODO:
         * reemplazar los banners
         * {link-baner}
         * {ruta-imagen}
         */        
    }    
    
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    
}
?>