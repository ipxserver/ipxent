<?php
class CLASS_WEB_HISTORIAL_RECLAMOS
{
    private $vlc_codigo_html;
    private $vlc_codigo_html_reclamo;
            
    
    
    function __construct ()
    {
        $this->vlc_codigo_html					= FN_LEER_TPL('tpl/tpl-historial-solicitudes.html');
        $this->vlc_codigo_menues="";                    
		$this->MTD_ENSAMBLAR_LISTA();             
    }
    function MTD_ENSAMBLAR_LISTA()
    {
    	//VERIFICA VARIABLES DE FILTRO
   		include_once ('SUB_CLASS_LISTA_RECLAMOS.php');
   		$obj_lista_reclamos = new SUB_CLASS_LISTA_RECLAMOS();
   		$obj_lista_reclamos->MTD_ENSAMBLA_RECLAMOS();
   		$this->vlc_codigo_html_reclamo= $obj_lista_reclamos->MTD_RETORNAR_CODIGO_HTML();
   		$this->vlc_codigo_html = FN_REEMPLAZAR("{listado-reclamos}",$this->vlc_codigo_html_reclamo,$this->vlc_codigo_html);
    
    }

    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    
}
?>