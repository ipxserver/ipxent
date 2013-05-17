<?php
class CLASS_ABM_PRODUCTOS
{
    private $vlc_codigo_html;
    private $vlc_nombre_producto;
    private $vlc_descripcion_producto;
    private $vlc_id_producto;
    function __construct ()
    {
        $this->vlc_nombre_producto = "";
        $this->vlc_id_producto = "";
        $this->vlc_descripcion_producto="";
        $this->vlc_codigo_html = "";
        $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_INICIALIZAR_PAGINA ()
    {
        /*
         * TODO: 
         * VALORES DEL FORMULARIO
         * ----------------------
         * [tb_nombre_producto]
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
         * {grilla-datos-categoria}
         * {tb-nombre-categoria}         
         */
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-abm-productos.html');
        /*
         * ================================
         * AGREGAR REGISTROS
         * ================================
         */
        if (isset($_REQUEST['MTD_AGREGAR']))
        {
            $this->MTD_RECIBIR_DATOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_AGREGAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se agrego correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-nombre-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_grilla = $this->MTD_GENERAR_GRILLA();
            $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-productos}', $vl_cod_html_grilla, $vl_cod_html_base);
        }
        /*
         * ================================
         * ACTUALIZAR REGISTROS
         * ================================
         */
        elseif (isset($_REQUEST['MTD_ACTUALIZAR']))
        {
            $this->MTD_RECIBIR_DATOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_ACTUALIZAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se actualizo correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
             $vl_cod_html_base = FN_REEMPLAZAR('{tb-nombre-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
        }
        /*
         * ================================
         * EDITAR REGISTROS
         * ================================
         */
        elseif (isset($_GET['MTD_EDITAR']))
        {
            $this->MTD_RECIBIR_DATOS();
            $vlp_datos_grilla = $this->MTD_DB_LISTAR(true);
            $vlf_id_producto = $vlp_datos_grilla[0][0];
            $vlf_nombre_producto = $vlp_datos_grilla[0][1];
            $vlf_descripcion_producto = $vlp_datos_grilla[0][2];
            $vlf_resultado = "";
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-nombre-producto}', $vlf_nombre_producto, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', $vlf_descripcion_producto, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_ACTUALIZAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Actualizar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{id-producto}', $vlf_id_producto, $vl_cod_html_base);
        }
        elseif (isset($_GET['MTD_ELIMINAR']))
        {
            $this->MTD_RECIBIR_DATOS();
            $vlf_resultado = "";
            if ($this->MTD_DB_ELIMINAR())
            {
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se elimino correctamente el Registro";
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-nombre-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);            
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{id-producto}', '', $vl_cod_html_base);
        }
        else
        {
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-nombre-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{tb-descripcion-producto}', '', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Producto', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', '', $vl_cod_html_base);
        }
        $vl_cod_html_grilla = $this->MTD_GENERAR_GRILLA();
        $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-productos}', $vl_cod_html_grilla, $vl_cod_html_base);
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    function MTD_GENERAR_GRILLA ()
    {
        $vlf_codigo_html_grilla;
        $vlp_datos_grilla = $this->MTD_DB_LISTAR();
        $vp_titulo = array();
        $vp_titulo[0] = "Id";
        $vp_titulo[1] = "Nombre del Producto";
        $vp_css_titulos = array();
        $vp_css_titulos[0] = "columna_id titulo_listado";
        $vp_css_titulos[1] = "columna_fecha titulo_listado";
        $vp_css_filas_datos = array();
        $vp_css_filas_datos[0] = "texto columna_id";
        $vp_css_filas_datos[1] = "texto columna_fecha";
        $vp_css_columnas_datos = array();
        $vp_css_columnas_datos[0] = "columna_id";
        $vp_css_columnas_datos[1] = "columna_fecha";
        $visualizar = true;
        $modificar = true;
        $eliminar = true;
        $lnk_visualizar = "#";
        $lnk_modificar = "index.php?seccion=productos&MTD_EDITAR=true";
        $lnk_eliminar = "index.php?seccion=productos&MTD_ELIMINAR=true";
        $corregir = false;
        $lnk_corregir = "";
        //$vlp_planes_clases
        $vlf_codigo_html_grilla = FN_HTML_ARMAR_GRILLA($vp_titulo, $vlp_datos_grilla, $vp_css_titulos, $vp_css_filas_datos, $vp_css_columnas_datos, $visualizar, $modificar, $eliminar, $lnk_visualizar, $lnk_modificar, $lnk_eliminar, $corregir, $lnk_corregir);
        return $vlf_codigo_html_grilla;
    }
    function MTD_RECIBIR_DATOS ()
    {
        if (isset($_POST['tb_nombre_producto']))
        {
            $this->vlc_nombre_producto = $_POST['tb_nombre_producto'];
        }
    	if (isset($_POST['tb_descripcion_producto']))
        {
            $this->vlc_descripcion_producto = $_POST['tb_descripcion_producto'];
        }
        if (isset($_POST['id_producto']))
        {
            $this->vlc_id_producto = $_POST['id_producto'];
        }
        elseif (isset($_GET['id']))
        {
            $this->vlc_id_producto = $_GET['id'];
        }
    }
    function MTD_DB_LISTAR ($vp_filtrar = false)
    {
        if ($vp_filtrar)
        {
            $vlf_sql = "select id_producto,nombre_producto,descripcion_producto from productos where id_producto =" . $this->vlc_id_producto;
        }
        else
        {
            $vlf_sql = "select id_producto,nombre_producto,descripcion_producto from productos";
        }
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql,3, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "INSERT INTO productos (nombre_producto,descripcion_producto) values ('" . $this->vlc_nombre_producto . "','" . $this->vlc_descripcion_producto . "')";
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_DB_ACTUALIZAR ()
    {
        $resultado = false;
        $vlf_sql = "UPDATE productos set 
        nombre_producto ='" . $this->vlc_nombre_producto . "',
        descripcion_producto ='" . $this->vlc_descripcion_producto. "' 
        where id_producto=" . $this->vlc_id_producto;
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
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>