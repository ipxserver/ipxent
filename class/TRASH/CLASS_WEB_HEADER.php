<?php
class CLASS_WEB_HEADER
{
    private $vlc_codigo_html;    
    function __construct ()
    {
        $this->vlc_codigo_html="";        
        $header= rand(1,4);
        $this->vlc_codigo_html= '<div id="header'.$header.'">&nbsp;</div>';               
        
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