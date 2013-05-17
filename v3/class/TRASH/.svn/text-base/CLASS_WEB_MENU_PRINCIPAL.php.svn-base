<?php
class CLASS_WEB_MENU_PRINCIPAL
{
    private $vlc_codigo_html;
    private $vlc_codigo_menues;
    function __construct ()
    {
        $this->vlc_codigo_html="";
        $this->vlc_codigo_menues="";
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-menu-principal.html');        
        $this->MTD_INICIALIZA_MENU();
        $this->MTD_IMPLEMENTAR_MENUES();        
        /*
         * TODO:
         * reemplazar los banners
         * {link-baner}
         * {ruta-imagen}
         */        
    }
    function MTD_INICIALIZA_MENU()
    {
        $this->MTD_ASIGNAR_MENU('index.php?seccion=reclamos','Nueva Solicitud');
        $this->MTD_ASIGNAR_MENU('index.php?seccion=historial-solicitudes','Historial Solicitudes Clientes');
       //$this->MTD_ASIGNAR_MENU('index.php?cuenta=ver','Mi Cuenta');
        $this->MTD_ASIGNAR_MENU('index.php','Mis Solicitudes');
        $this->MTD_ASIGNAR_MENU('index.php','Solicitudes Pendientes');
        $this->MTD_ASIGNAR_MENU('index.php','Buscar Requerimiento');
        $this->MTD_ASIGNAR_MENU('index.php','Buscar Cliente');
        $this->MTD_ASIGNAR_MENU('index.php','Reportes<br>');
        $this->MTD_ASIGNAR_MENU('index.php','Panel de Control');
        $this->MTD_ASIGNAR_MENU('index.php','Salir');
        $this->MTD_ASIGNAR_MENU('index.php?seccion=productos','Productos');
        $this->MTD_ASIGNAR_MENU('index.php?seccion=modulos','M&oacute;dulos');
        $this->MTD_ASIGNAR_MENU('index.php?seccion=programas','Programas L&oacute;gicos');
        $this->MTD_ASIGNAR_MENU('index.php?seccion=clientes','Clientes');        
    }
    function MTD_ASIGNAR_MENU($vp_link,$vp_titulo)
    {
     
        $vlf_estructura_menu="<a href='$vp_link'>$vp_titulo</a> &nbsp; | ";
                
        //ASIGNA EL CODIGO A LA VARIABLE DE LA CLASE
        $this->vlc_codigo_menues =  $this->vlc_codigo_menues.$vlf_estructura_menu; 
    }
    function MTD_IMPLEMENTAR_MENUES()
    {
        /*
         * TODO:
         * Remplazar la lista de menues con la asignada en la variable de la clase
         * {lista-menues}
         */
       $this->vlc_codigo_html = FN_REEMPLAZAR("{menu-principal}",$this->vlc_codigo_menues,$this->vlc_codigo_html);                            
    }
    
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    
}
?>