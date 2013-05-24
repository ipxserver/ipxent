<?php
class CLASS_WEB_INICIO
{
    private $vlc_codigo_html;
    function __construct ()
    {
       $this->vlc_codigo_html="";
       $this->MTD_INICIALIZAR_PAGINA();       
    }
    function MTD_INICIALIZAR_PAGINA()
    {               
        include_once ('class/CLASS_WEB_MENU_PRINCIPAL.php');        
        //-----------------------------------------
        // INICIALIZACION DE VARIABLES
        //-----------------------------------------
        $vl_cod_html_base = "";
        $vl_cod_html_banner = "";
        //-----------------------------------------
        // LECTURA DEL TPL BASE
        //-----------------------------------------
        $vl_cod_html_base = FN_LEER_TPL('tpl/index.html');
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: MENU PRINCIPAL
        //-----------------------------------------
        $obj_menu_principal = new CLASS_MENU_PRINCIPAL();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-menu-principal}", $obj_menu_principal->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: MENU SECUNDARIO
        //-----------------------------------------
        $obj_menu_secundario = new CLASS_MENU_SECUNDARIO();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-menu-secundario}", $obj_menu_secundario->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: CATEGORIAS
        //-----------------------------------------
        $obj_categorias = new CLASS_CATEGORIAS();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-categorias}", $obj_categorias->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: FABRICANTES
        //-----------------------------------------
        $obj_fabricantes = new CLASS_FABRICANTES();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-fabricantes}", $obj_fabricantes->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: OFERTAS
        //-----------------------------------------
        $obj_ofertas = new CLASS_OFERTAS();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-oferta-productos}", $obj_ofertas->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: PIE DE PAGINA
        //-----------------------------------------
        $vl_cod_html_pie = FN_LEER_TPL('tpl/tbl_pie_pagina.html');
        $vl_cod_html_base = FN_REEMPLAZAR("{pie-de-pagina}", $vl_cod_html_pie, $vl_cod_html_base);
        //-------------------------------------------
        // ASIGNACION DE LA SECCION: PRODUCTO CENTRAL
        //-------------------------------------------
        $obj_productos_central = new CLASS_PRODUCTO_CENTRAL();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-producto-central1}", $obj_productos_central->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-------------------------------------------
        // ASIGNACION DE LA SECCION: PRODUCTO CENTRAL2
        //-------------------------------------------
        $obj_productos_central2 = new CLASS_PRODUCTO_CENTRAL2();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-producto-central2}", $obj_productos_central2->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-------------------------------------------
        // ASIGNACION DE LA SECCION: COMPRAS
        //-------------------------------------------
        $obj_compras = new CLASS_COMPRAS();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-compras}", $obj_compras->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        //-------------------------------------------
        // ASIGNACION DE LA SECCION: MAS VENDIDOS
        //-------------------------------------------
        $obj_mas_vendidos = new CLASS_MAS_VENDIDOS();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-los-mas-vendidos}", $obj_mas_vendidos->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);        
        //-------------------------------------------
        // IMPRIME EL CODIGO HTML
        //-------------------------------------------
        $this->vlc_codigo_html= $vl_cod_html_base;  
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
    }
}
?>