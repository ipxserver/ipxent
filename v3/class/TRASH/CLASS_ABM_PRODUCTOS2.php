<?php
class CLASS_ABM_PRODUCTOS
{
    private $vlc_codigo_html;
    private $vlc_id_producto;
    private $vlc_titulo_producto;
    private $vlc_descripcion_producto;    
    function __construct ()
    {
        $this->MTD_INICIALIZAR_VARIABLES();
        $this->MTD_INICIALIZAR_PAGINA();
    }
    /*
***********************************************************************************************
*  MAIN
***********************************************************************************************
*/
    function MTD_INICIALIZAR_VARIABLES ()
    {
        $this->vlc_codigo_html = "";
        $this->vlc_id_producto = "";
        $this->vlc_titulo_producto = " ";
        $this->vlc_descripcion_producto = " ";  
    }
    function MTD_INICIALIZAR_PAGINA ()
    {
        /*
         * TODO: 
         * VALORES DEL FORMULARIO
         * ----------------------
         * [tb_titulo_producto]
         * [id_producto]
         * 
         * ACCIONES DEL FORMULARIO
         * -----------------------
         * frm_insertar
         * frm_actualizar
         * 
         * ETIQUETAS DEL tpl
         * ------------------------
         * {accion-formulario}
         * {titulo-accion-formulario}
         * {grilla-datos-producto}
         * {tb-titulo-producto}         
         */
    
        $this->vlc_codigo_html = "";
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-adm-productos.html');
        /*
         * ================================
         * AGREGAR REGISTROS
         * ================================
         */
        if (isset($_REQUEST['MTD_AGREGAR']))
        {
            $this->MTD_RECIBIR_DATOS_EXTERNOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_AGREGAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se agrego correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $this->MTD_INICIALIZAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $this->MTD_INICIALIZAR_VARIABLES();
        }
        /*
         * ================================
         * ACTUALIZAR REGISTROS
         * ================================
         */
        elseif (isset($_REQUEST['MTD_ACTUALIZAR']))
        {
            $this->MTD_RECIBIR_DATOS_EXTERNOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_ACTUALIZAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se actualizo correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $this->MTD_INICIALIZAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{id-producto}', '', $vl_cod_html_base);
        }
        /*
         * ================================
         * EDITAR REGISTROS
         * ================================
         */
        elseif (isset($_GET['MTD_EDITAR']))
        {
            $this->MTD_RECIBIR_DATOS_EXTERNOS();
            $vlp_arreglo_datos = $this->MTD_DB_LISTAR(true);
            $this->MTD_RECIBIR_DATOS_DB($vlp_arreglo_datos);      
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);     
            $vlf_resultado = "";
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_ACTUALIZAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Actualizar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{id-producto}', $this->vlc_id_producto, $vl_cod_html_base);
        }
        elseif (isset($_GET['MTD_ELIMINAR']))
        {
            $this->MTD_RECIBIR_DATOS_EXTERNOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_ELIMINAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se elimino correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $this->MTD_INICIALIZAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{id-producto}', '', $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', '', $vl_cod_html_base);
        }
        include_once('CLASS_DT_PRODUCTOS_GRILLA.php');
    	$obj_grilla = new CLASS_DT_PRODUCTOS_GRILLA('index.php?admin=productos');    	
        $vl_cod_html_grilla = $obj_grilla->MTD_RETORNAR_CODIGO_HTML();
        $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-producto}', $vl_cod_html_grilla, $vl_cod_html_base);
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    /*
***********************************************************************************************
*  FUNCIONES DE DATOS
***********************************************************************************************
*/
    function MTD_RECIBIR_DATOS_EXTERNOS ()
    {
	include_once('includes/FN_CARACTERES_HTML.php');
        $this->vlc_titulo_producto = " ";
        $this->vlc_id_producto = "";
        $this->vlc_descripcion_producto = " ";
        $this->vlc_modelo_producto = " ";
        $this->vlc_id_categoria = "";
        $this->vlc_id_marca = "";
        $this->vlc_extension = "";
        $this->vlc_precio = "";
        $this->vlc_imagen1 = "";
        $this->vlc_imagen2 = "";
        $this->vlc_imagen3 = "";
        $this->vlc_imagen4 = "";
        $this->vlc_largo = "";
        $this->vlc_alto = "";
        $this->vlc_ancho = "";
        $this->vlc_peso = "";
        $this->vlc_oferta=0;
        $this->vlc_porcentaje_oferta=0;
        $this->vlc_destacado=0;
        if (isset($_POST['tb_titulo_producto']))
        {
            $this->vlc_titulo_producto = $_POST['tb_titulo_producto'];
        }
        if (isset($_POST['id_producto']))
        {
            $this->vlc_id_producto = $_POST['id_producto'];
        }
        elseif (isset($_GET['id']))
        {
            $this->vlc_id_producto = $_GET['id'];
        }
        if (isset($_POST['tb_modelo_producto']))
        {
            $this->vlc_modelo_producto = $_POST['tb_modelo_producto'];
        }
        if (isset($_POST['lst_categorias']))
        {
            $this->vlc_id_categoria = $_POST['lst_categorias'];
        }
        if (isset($_POST['lst_marcas']))
        {
            $this->vlc_id_marca = $_POST['lst_marcas'];
        }
        if (isset($_POST['tb_precio_producto']))
        {
            $this->vlc_precio = $_POST['tb_precio_producto'];
        }
        if (isset($_POST['tb_descripcion_producto']))
        {
            $this->vlc_descripcion_producto = $_POST['tb_descripcion_producto'];
        }
        if (isset($_POST['tb_extension_producto']))
        {
            $this->vlc_extension = $_POST['tb_extension_producto'];
          
	    //$this->vlc_extension = FN_CARACTERES_HTML($this->vlc_extension);
	    //echo "<br> EXTENSION:".$this->vlc_extension ;  
	    //$this->vlc_extension = utf8_encode($this->vlc_extension);
	    
        }
        if (isset($_POST['tb_imagen1']))
        {
            $this->vlc_imagen1 = $_POST['tb_imagen1'];
        }
        if (isset($_POST['tb_imagen2']))
        {
            $this->vlc_imagen2 = $_POST['tb_imagen2'];
        }
        if (isset($_POST['tb_imagen3']))
        {
            $this->vlc_imagen3 = $_POST['tb_imagen3'];
        }
        if (isset($_POST['tb_imagen4']))
        {
            $this->vlc_imagen4 = $_POST['tb_imagen4'];
        }
        if (isset($_POST['tb_imagen_previa1']))
        {
            $this->vlc_imagen_previa1 = $_POST['tb_imagen_previa1'];
        }
        if (isset($_POST['tb_imagen_previa2']))
        {
            $this->vlc_imagen_previa2 = $_POST['tb_imagen_previa2'];
        }
        if (isset($_POST['tb_imagen_previa3']))
        {
            $this->vlc_imagen_previa3 = $_POST['tb_imagen_previa3'];
        }
        if (isset($_POST['tb_imagen_previa4']))
        {
            $this->vlc_imagen_previa4 = $_POST['tb_imagen_previa4'];
        }
        if (isset($_POST['tb_largo']))
        {
            $this->vlc_largo = $_POST['tb_largo'];
        }
        if (isset($_POST['tb_ancho']))
        {
            $this->vlc_ancho = $_POST['tb_ancho'];
        }
        if (isset($_POST['tb_altura']))
        {
            $this->vlc_alto = $_POST['tb_altura'];
        }
        if (isset($_POST['tb_peso']))
        {
            $this->vlc_peso = $_POST['tb_peso'];
        }
    	if (isset($_POST['tb_oferta_producto']))
        {
            $this->vlc_oferta= $_POST['tb_oferta_producto'];
        }
    	if (isset($_POST['tb_porcentaje_oferta']))
        {
            $this->vlc_porcentaje_oferta = $_POST['tb_porcentaje_oferta'];
        }
    	if (isset($_POST['tb_destaque_producto']))
        {
            $this->vlc_destacado = $_POST['tb_destaque_producto'];
        }
        $this->MTD_RECIBIR_IMAGENES();
    }
    function MTD_RECIBIR_IMAGENES ()
    {
        $imagen1 = "";
        $imagen2 = "";
        $imagen3 = "";
        $imagen4 = "";
        
        //-------------------------------------------------------
        //    UPLOAD DE IMAGENES
        //-------------------------------------------------------
        if (isset($_FILES['tb_imagen1']))
        {            
            $imagen1 = $this->MTD_SUBIR_IMAGENES('tb_imagen1');
        }        
        if (isset($_FILES['tb_imagen2']))
        {
            $imagen2 = $this->MTD_SUBIR_IMAGENES('tb_imagen2');
        }
        if (isset($_FILES['tb_imagen3']))
        {
            $imagen3 = $this->MTD_SUBIR_IMAGENES('tb_imagen3');
        }
        if (isset($_FILES['tb_imagen4']))
        {
            $imagen4 = $this->MTD_SUBIR_IMAGENES('tb_imagen4');
        }
        //-------------------------------------------------------
        //    ASIGNACION DE VARIABLES
        //-------------------------------------------------------
        if (isset($imagen1))
        {
            $this->vlc_imagen1 = $imagen1;
        } 
        elseif (isset($this->vlc_imagen_previa1))
        {            
            $this->vlc_imagen1 = $this->vlc_imagen_previa1;        
        }
        if (isset($imagen2))
        {
            $this->vlc_imagen2 = $imagen2;
        }
        elseif (isset($this->vlc_imagen_previa2))
        {            
            $this->vlc_imagen2 = $this->vlc_imagen_previa2;        
        }
        if (isset($imagen3))
        {
            $this->vlc_imagen3 = $imagen3;
        }
        elseif (isset($this->vlc_imagen_previa3))
        {            
            $this->vlc_imagen3 = $this->vlc_imagen_previa3;        
        }
        if (isset($imagen4))
        {
            $this->vlc_imagen4 = $imagen4;
        }
        elseif (isset($this->vlc_imagen_previa4))
        {            
            $this->vlc_imagen4 = $this->vlc_imagen_previa4;        
        }
        
    }
    //---------------------------------------------------
    //             ASIGNAR DATOS LOCALES
    //---------------------------------------------------
    function MTD_RECIBIR_DATOS_DB ($vp_arreglo_datos)
    {
        $this->vlc_id_producto            = $vp_arreglo_datos[0][0];
        $this->vlc_titulo_producto        = $vp_arreglo_datos[0][1];
        $this->vlc_id_marca               = $vp_arreglo_datos[0][2];
        $this->vlc_id_categoria           = $vp_arreglo_datos[0][3];
        $this->vlc_modelo_producto        = $vp_arreglo_datos[0][4];
        $this->vlc_precio                 = $vp_arreglo_datos[0][5];
        $this->vlc_imagen1                = $vp_arreglo_datos[0][6];
        $this->vlc_imagen2                = $vp_arreglo_datos[0][7];
        $this->vlc_imagen3                = $vp_arreglo_datos[0][8];
        $this->vlc_imagen4                = $vp_arreglo_datos[0][9];
        $this->vlc_largo                  = $vp_arreglo_datos[0][10];
        $this->vlc_alto                   = $vp_arreglo_datos[0][11];
        $this->vlc_ancho                  = $vp_arreglo_datos[0][12];
        $this->vlc_peso                   = $vp_arreglo_datos[0][13];
        $this->vlc_descripcion_producto   = $vp_arreglo_datos[0][14];
        $this->vlc_extension              = $vp_arreglo_datos[0][15];
        $this->vlc_oferta 				  = $vp_arreglo_datos[0][16];
        $this->vlc_porcentaje_oferta	  = $vp_arreglo_datos[0][17];
        $this->vlc_destacado			  = $vp_arreglo_datos[0][18];
        
    }
/*
***********************************************************************************************
*  OPERACIONES DE DATOS EN LA BASE DE DATOS
***********************************************************************************************
*/
    function MTD_DB_LISTAR_CATEGORIAS ()
    {
        $vlf_sql = "select id_categoria,nombre_categoria from categorias ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR_MARCAS ()
    {
        $vlf_sql = "select id_marca,nombre_marca from marcas";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR ($vp_filtrar = false)
    {
    	include_once('includes/FN_ARR_FORMAT_PRECIO.php');
        $vlf_arreglo_datos = array();
        if ($vp_filtrar)
        {
            $vlf_sql = "SELECT  
            p.id_producto,
            p.titulo_producto,
            p.id_marca,
            p.id_categoria,
            p.modelo,
            p.precio,
            p.imagen1,
            p.imagen2,
            p.imagen3,
            p.imagen4,
            p.largo,
            p.alto,
            p.ancho,
            p.peso,
            p.descripcion,
            p.extenso,
            p.oferta,
            p.porcentaje_oferta,
            p.destacado,            
            m.nombre_marca,
            c.nombre_categoria                  
            FROM productos as p, marcas as m, categorias as c
            where p.id_marca = m.id_marca
            AND p.id_categoria = c.id_categoria
            AND p.id_producto =" . $this->vlc_id_producto;
            $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 19, $vlg_conexion);            
        }
        else
        {
            $vlf_sql = "
            SELECT  
            p.id_producto,
            p.titulo_producto,
            m.nombre_marca,
            c.nombre_categoria,
            p.modelo,
            p.precio,
            p.imagen1 
            from productos as p, marcas as m, categorias as c
            where p.id_marca = m.id_marca
            AND p.id_categoria = c.id_categoria";
            $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6, $vlg_conexion);            
        }
        $vlf_arreglo_datos=FN_ARR_FORMAT_PRECIO($vlf_arreglo_datos,5);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "INSERT INTO productos (        
        id_categoria,
        id_marca,
        titulo_producto,
        modelo,
        descripcion,
        extenso,
        imagen1,
        imagen2,
        imagen3,
        imagen4,
        largo,
        ancho,
        alto,
        peso,
        precio,
        oferta,
        porcentaje_oferta,
        destacado) 
        VALUES
        (
         " . $this->vlc_id_categoria . ",
         " . $this->vlc_id_marca . ",
        '" . $this->vlc_titulo_producto . "',
        '" . $this->vlc_modelo_producto . "',
        '" . $this->vlc_descripcion_producto . "',
        '" . $this->vlc_extension . "',
        '" . $this->vlc_imagen1 . "',
        '" . $this->vlc_imagen2 . "',
        '" . $this->vlc_imagen3 . "',
        '" . $this->vlc_imagen4 . "',
        '" . $this->vlc_largo . "',
        '" . $this->vlc_ancho . "',
        '" . $this->vlc_alto . "',
        '" . $this->vlc_peso . "',
        " . $this->vlc_precio . ",
        " . $this->vlc_oferta . ",
        " . $this->vlc_porcentaje_oferta . ",
        " . $this->vlc_destacado . ");";
       	//echo "SQL: $vlf_sql";
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_DB_ACTUALIZAR ()
    {
        $resultado = false;
        $vlf_sql = "UPDATE productos set
        id_categoria 	= " . $this->vlc_id_categoria. "
        ,id_marca	= " . $this->vlc_id_marca . "
        ,titulo_producto ='" . $this->vlc_titulo_producto . "'                
        ,modelo		='" . $this->vlc_modelo_producto . "'
        ,descripcion	='" . $this->vlc_descripcion_producto . "'
        ,extenso	='" . $this->vlc_extension. "'
        ,imagen1	='" . $this->vlc_imagen1 . "'
        ,imagen2	='" . $this->vlc_imagen2 . "'
        ,imagen3	='" . $this->vlc_imagen3  . "'
        ,imagen4	='" . $this->vlc_imagen4. "'
        ,largo		='" . $this->vlc_largo. "'
        ,ancho		='" . $this->vlc_ancho. "'
        ,alto		='" . $this->vlc_alto. "'
        ,peso		='" . $this->vlc_peso. "'
        ,precio		= " . $this->vlc_precio. "        
        ,oferta		= " . $this->vlc_oferta. "
        ,porcentaje_oferta		= " . $this->vlc_porcentaje_oferta. "
        ,destacado	= " . $this->vlc_destacado. "
        where id_producto=" . $this->vlc_id_producto;
        //echo "SQL: $vlf_sql";
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_DB_ELIMINAR ()
    {
        $resultado = false;
        $vlf_sql = "DELETE FROM productos  where id_producto =" . $this->vlc_id_producto;
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    /*
***********************************************************************************************
*  FUNCIONES COMPLEMENTARIAS
***********************************************************************************************
*/
    //---------------------------------------------------
    //             APLICAR TEMPLATES
    //---------------------------------------------------        
    function MTD_APLICAR_TEMPLATE ($vp_codigo_html)
    {
        $vl_cod_html_base = $vp_codigo_html;
        //== TEMPLATES //
        $vl_cod_html_productos = FN_LEER_TPL('tpl/tpl-adm-productos-datos.html');
        $vl_cod_html_extension = FN_LEER_TPL('tpl/tpl-adm-productos-extension.html');
        $vl_cod_html_imagenes = FN_LEER_TPL('tpl/tpl-adm-productos-imagenes.html');
        $vl_cod_html_entrega = FN_LEER_TPL('tpl/tpl-adm-productos-entrega.html');
        $vl_cod_html_oferta = FN_LEER_TPL('tpl/tpl-adm-productos-ofertas.html');
        //== APLICACION DEL TEMPLATE ==  //                        
        $vl_cod_html_base = FN_REEMPLAZAR('{tab-datos}', $vl_cod_html_productos, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tab-extension}', $vl_cod_html_extension, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tab-imagenes}', $vl_cod_html_imagenes, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tab-datos-entrega}', $vl_cod_html_entrega, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tab-oferta-destaque}', $vl_cod_html_oferta , $vl_cod_html_base);
        //== DATOS DEL PRODUCTO ==//                  
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-titulo-producto}', $this->vlc_titulo_producto, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-modelo-producto}', $this->vlc_modelo_producto, $vl_cod_html_base);
        $vlf_precio = FN_REEMPLAZAR('.','', $this->vlc_precio);
               
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-precio-producto}', $vlf_precio, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', $this->vlc_descripcion_producto, $vl_cod_html_base);
        //== DATOS DEL PRODUCTO ==//
        $vl_cod_html_lista_categoria = FN_HTML_ARMAR_LISTA($this->MTD_DB_LISTAR_CATEGORIAS(), 'Categorias', 'lst_categorias', $this->vlc_id_categoria, false, '');
        $vl_cod_html_base = FN_REEMPLAZAR('{select-categorias}', $vl_cod_html_lista_categoria, $vl_cod_html_base);
        $vl_cod_html_lista_marcas = FN_HTML_ARMAR_LISTA($this->MTD_DB_LISTAR_MARCAS(), 'Marcas', 'lst_marcas', $this->vlc_id_marca, false, '');
        $vl_cod_html_base = FN_REEMPLAZAR('{select-marcas}', $vl_cod_html_lista_marcas, $vl_cod_html_base);
        //== EXTENSION DEL PRODUCTO==//
        $vl_cod_html_base = FN_REEMPLAZAR('{editor-contenidos}', $this->MTD_MOSTRAR_EDITOR_CONTENIDO(), $vl_cod_html_base);
        //== IMAGENES DEL PRODUCTO==//            
        $vl_cod_html_base = FN_REEMPLAZAR('{imagen-previa1}', $this->vlc_imagen1, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{imagen-previa2}', $this->vlc_imagen2, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{imagen-previa3}', $this->vlc_imagen3, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{imagen-previa4}', $this->vlc_imagen4, $vl_cod_html_base);
        //== IMAGENES DEL PRODUCTO==//
        $vlf_no_imagen_producto = "add_image.png";
        $vlf_ruta_imagenes = "imagenes/productos/th_";
        $vlf_camino_imagenes1 = "<img src='" . $vlf_ruta_imagenes;
        $vlf_camino_imagenes2 = "'/>";
        if ($this->vlc_imagen1)
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen1}', $vlf_camino_imagenes1 . $this->vlc_imagen1 . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen1}', $vlf_camino_imagenes1 . $vlf_no_imagen_producto . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        if ($this->vlc_imagen2)
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen2}', $vlf_camino_imagenes1 . $this->vlc_imagen2 . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen2}', $vlf_camino_imagenes1 . $vlf_no_imagen_producto . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        if ($this->vlc_imagen3)
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen3}', $vlf_camino_imagenes1 . $this->vlc_imagen3 . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen3}', $vlf_camino_imagenes1 . $vlf_no_imagen_producto . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        if ($this->vlc_imagen4)
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen4}', $vlf_camino_imagenes1 . $this->vlc_imagen4 . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{imagen4}', $vlf_camino_imagenes1 . $vlf_no_imagen_producto . $vlf_camino_imagenes2, $vl_cod_html_base);
        }
        //== ENTREGA DEL PRODUCTO==//            
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-largo-producto}', $this->vlc_largo, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-ancho-producto}', $this->vlc_ancho, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-altura-producto}', $this->vlc_alto, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-peso-producto}', $this->vlc_peso, $vl_cod_html_base);
        //== OFERTA - DESTAQUE==//
        if ($this->vlc_oferta)
        {
        	$vl_cod_html_base = FN_REEMPLAZAR('{tb-oferta-producto}','checked', $vl_cod_html_base);
        }
        else 
        {
        	$vl_cod_html_base = FN_REEMPLAZAR('{tb-oferta-producto}','', $vl_cod_html_base);
        }
        $vl_cod_html_base = FN_REEMPLAZAR('{tb-porcentaje-oferta}',$this->vlc_porcentaje_oferta,$vl_cod_html_base);
    	if ($this->vlc_destacado)
        {
        	$vl_cod_html_base = FN_REEMPLAZAR('{tb-destaque-producto}','checked', $vl_cod_html_base);
        }
        else 
        {
        	$vl_cod_html_base = FN_REEMPLAZAR('{tb-destaque-producto}','', $vl_cod_html_base);
        }
    	
        return $vl_cod_html_base;
    }
    function MTD_MOSTRAR_EDITOR_CONTENIDO ()
    {
	$vd_contenido = $this->vlc_extension;
	$vd_contenido= str_replace("\n","",$vd_contenido);
	$vd_contenido= str_replace("\r","",$vd_contenido);
        $vlf_contenido_html_contenido = FN_HTML_EDITORCONTENIDO('tb_extension_producto',$vd_contenido , 'formulario');
        return $vlf_contenido_html_contenido;
    }
    
    //---------------------------------------------------
    //             SUBIR IMAGENES
    //---------------------------------------------------    
    function MTD_SUBIR_IMAGENES ($vp_nombre_campo)
    {
        $resultado = false;
        //RECEPCION DEL ARCHIVO LEVANTADO
        if ((isset($_FILES[$vp_nombre_campo]) && ($_FILES[$vp_nombre_campo]['size'] != 0)))
        {
            if ($_FILES[$vp_nombre_campo]['size'] == 0)
            {
                echo "Error: El archivo tiene 0 bytes";
                exit();
            }
            if (($_FILES[$vp_nombre_campo]['type'] != "image/pjpeg") && ($_FILES[$vp_nombre_campo]['type'] != "image/jpeg") && ($_FILES[$vp_nombre_campo]['type'] != "image/png"))
            {
                echo "Error: El archivo no es formato JPG o PNG";
                exit();
            }
            //Primera Imagen-----------------------------------------------------------------------------------------
            $tiempo = mktime();
            $ruta = "imagenes/productos/";
            $archivo = $tiempo . ".jpg";
            $nombrearch = $ruta . $archivo;
            //echo "<BR> NOMBRE ARCHIVO:$nombrearch";
            include_once ('class/CLASS_THUMBNAIL.php');
            move_uploaded_file($_FILES[$vp_nombre_campo]['tmp_name'], $nombrearch);
            $thumb = new Thumbnail($nombrearch); // Contructor and set source image file
            $chk_primero = false;
            $chk_segundo = false;
            //Primera Imagen (Thumbnail)
            $thumb->size_auto(120); // [OPTIONAL] set the biggest width or height for thumbnail
            $thumb->quality = 75; // [OPTIONAL] default 75 , only for JPG format
            $thumb->output_format = 'JPG'; // [OPTIONAL] JPG | PNG
            $thumb->jpeg_progressive = 1; // [OPTIONAL] set progressive JPEG : 0 = no , 1 = yes
            $thumb->process(); // generate image
            //echo "thumb 1 OK";
            $nthumb = $ruta . "th_" . $archivo;
            $thumb->save("$nthumb"); // save your thumbnail to file, or
            echo ($thumb->error_msg); // print Error Mensage
            //Segunda Imagen-----------------------------------------------------------------------------------------
            $thumb2 = new Thumbnail("$nombrearch"); // Contructor and set source image file
            $x = @getimagesize($nombrearch);
            // image width
            $image_with = $x[0];
            // image height
            $image_height = $x[1];
            $factor = $image_with / $image_height;
            if (($factor > 1.2) && ($factor < 1.5))
            {
                $thumb2->size_width(600);
            }
            else 
                if (($factor < 0.8) && ($factor > 0.5))
                {
                    $thumb2->size_height(550);
                }
                else
                {
                    $thumb2->size_auto(550);
                }
            // $thumb2->size_auto(300);                                      // [OPTIONAL] set the biggest width or height for thumbnail
            //$thumb2->size_height(230);   
            $thumb2->size_auto(550); // set height for thumbnail, or
            $thumb2->quality = 80; // [OPTIONAL] default 75 , only for JPG format
            $thumb2->output_format = 'JPG'; // [OPTIONAL] JPG | PNG
            $thumb2->jpeg_progressive = 1;
            // $thumb2->img_watermark='../lib/watermark.png';      //
            //$thumb2->img_watermark_Valing='BOTTOM';         // [OPTIONAL] set watermark vertical position, TOP | CENTER | BOTTON
            //$thumb2->img_watermark_Haling='RIGHT';        // [OPTIONAL] set watermark horizonatal position, LEFT | CENTER | RIGHT
            $thumb2->process(); // generate image
            $nimage = $ruta . "pic_" . $archivo;
            ;
            $thumb2->save($nimage); // save your thumbnail to file, or
            echo ($thumb2->error_msg); // print Error Mensage
            //-------------------------------------------------------------
            //Segunda Imagen-----------------------------------------------------------------------------------------
            $thumb3 = new Thumbnail("$nombrearch"); // Contructor and set source image file
            $x = @getimagesize($nombrearch);
            // image width
            $image_with = $x[0];
            // image height
            $image_height = $x[1];
            $factor = $image_with / $image_height;
            if (($factor > 1.2) && ($factor < 1.5))
            {
                $thumb3->size_width(300);
            }
            else 
                if (($factor < 0.8) && ($factor > 0.5))
                {
                    $thumb3->size_height(300);
                }
                else
                {
                    $thumb3->size_auto(300);
                }
            // $thumb2->size_auto(300);                                      // [OPTIONAL] set the biggest width or height for thumbnail
            //$thumb2->size_height(230);   
            $thumb3->size_auto(300); // set height for thumbnail, or
            $thumb3->quality = 80; // [OPTIONAL] default 75 , only for JPG format
            $thumb3->output_format = 'JPG'; // [OPTIONAL] JPG | PNG
            $thumb3->jpeg_progressive = 1;
            // $thumb2->img_watermark='../lib/watermark.png';      //
            //$thumb2->img_watermark_Valing='BOTTOM';         // [OPTIONAL] set watermark vertical position, TOP | CENTER | BOTTON
            //$thumb2->img_watermark_Haling='RIGHT';        // [OPTIONAL] set watermark horizonatal position, LEFT | CENTER | RIGHT
            $thumb3->process(); // generate image
            $nimage = $ruta . "mid_" . $archivo;
            ;
            $thumb3->save($nimage); // save your thumbnail to file, or
            echo ($thumb2->error_msg); // print Error Mensage
            //echo "thumb 2 OK";
            unlink($nombrearch);
        }
        elseif ($_FILES[$vp_nombre_campo]['error'] == UPLOAD_ERR_FORM_SIZE)
        {
            echo "<BR> Error, el archivo es demasiado grande";
        }
        return $archivo;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>