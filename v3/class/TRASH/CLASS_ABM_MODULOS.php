<?php
class CLASS_ABM_MODULOS
{
    private $vlc_codigo_html;
    private $vlc_nombre_modulo;
    private $vlc_descripcion_modulo;
    private $vlc_id_modulo;
    private $vlc_id_producto;
    function __construct ()
    {
        $this->vlc_nombre_modulo = "";
        $this->vlc_id_modulo = "";
        $this->vlc_id_producto = "";
        $this->vlc_descripcion_modulo="";
        $this->vlc_codigo_html = "";
        $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_INICIALIZAR_PAGINA ()
    {
        /*
         * TODO: 
         * VALORES DEL FORMULARIO
         * ----------------------
         * [tb_nombre_modulo]
         * [id_modulo]
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
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-abm-modulos.html');
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
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Modulo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
           
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
           	$vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Modulo', $vl_cod_html_base);
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
            $vlf_id_modulo = $vlp_datos_grilla[0][0];
            $vlf_nombre_modulo = $vlp_datos_grilla[0][1];
            $vlf_descripcion_modulo = $vlp_datos_grilla[0][2];
            $vlf_resultado = ""; 
            $this->MTD_RECIBIR_DATOS_DB($vlp_datos_grilla);           
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);   
        	         
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_ACTUALIZAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Actualizar Modulo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
         
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
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);            
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Modulo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);            
        }
        else
        {
        
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
        	$vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Modulo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', '', $vl_cod_html_base);
        }
        $vl_cod_html_grilla = $this->MTD_GENERAR_GRILLA();
        $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-modulos}', $vl_cod_html_grilla, $vl_cod_html_base);
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    function MTD_GENERAR_GRILLA ()
    {
        $vlf_codigo_html_grilla;
        $vlp_datos_grilla = $this->MTD_DB_LISTAR();
        $vp_titulo = array();
        $vp_titulo[0] = "Id";
        $vp_titulo[1] = "Nombre del Modulo";
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
        $lnk_modificar = "index.php?seccion=modulos&MTD_EDITAR=true";
        $lnk_eliminar = "index.php?seccion=modulos&MTD_ELIMINAR=true";
        $corregir = false;
        $lnk_corregir = "";
        //$vlp_planes_clases
        $vlf_codigo_html_grilla = FN_HTML_ARMAR_GRILLA($vp_titulo, $vlp_datos_grilla, $vp_css_titulos, $vp_css_filas_datos, $vp_css_columnas_datos, $visualizar, $modificar, $eliminar, $lnk_visualizar, $lnk_modificar, $lnk_eliminar, $corregir, $lnk_corregir);
        return $vlf_codigo_html_grilla;
    }
    function MTD_APLICAR_TEMPLATE ($vp_codigo_html)
    {
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-modulo}', $this->vlc_nombre_modulo, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{tb-descripcion-modulo}', $this->vlc_descripcion_modulo, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{id-modulo}', $this->vlc_id_modulo, $vp_codigo_html);
        //== DATOS DEL PRODUCTO ==//        
        $vp_codigo_html_lista_producto = FN_HTML_ARMAR_LISTA($this->MTD_DB_LISTAR_PRODUCTOS(), 'Productos', 'lst_productos', $this->vlc_id_producto, false, '');
        $vp_codigo_html = FN_REEMPLAZAR('{select-producto}', $vp_codigo_html_lista_producto, $vp_codigo_html);
        return  $vp_codigo_html;
    }
  	function MTD_DB_LISTAR_PRODUCTOS ()
    {
        $vlf_sql = "select id_producto,nombre_producto from productos ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_RECIBIR_DATOS ()
    {
        if (isset($_POST['tb_nombre_modulo']))
        {
            $this->vlc_nombre_modulo = $_POST['tb_nombre_modulo'];
        }
    	if (isset($_POST['tb_descripcion_modulo']))
        {
            $this->vlc_descripcion_modulo = $_POST['tb_descripcion_modulo'];
        }
        if (isset($_POST['id_modulo']))
        {
            $this->vlc_id_modulo = $_POST['id_modulo'];
        }
     	if (isset($_POST['lst_productos']))
        {
            $this->vlc_id_producto = $_POST['lst_productos'];
        }
        elseif (isset($_GET['id']))
        {
            $this->vlc_id_modulo = $_GET['id'];
        }
    }
 	function MTD_RECIBIR_DATOS_DB ($vp_arreglo_datos)
    {
        $this->vlc_id_modulo            = $vp_arreglo_datos[0][0];
        $this->vlc_nombre_modulo		= $vp_arreglo_datos[0][1];
        $this->vlc_descripcion_modulo	= $vp_arreglo_datos[0][2];
        $this->vlc_id_producto          = $vp_arreglo_datos[0][3];        
    }
    function MTD_DB_LISTAR ($vp_filtrar = false)
    {
        if ($vp_filtrar)
        {
            $vlf_sql = "select id_modulo,nombre_modulo,descripcion_modulo,id_producto from modulos where id_modulo =" . $this->vlc_id_modulo;
        }
        else
        {
            $vlf_sql = "select id_modulo,nombre_modulo,descripcion_modulo,id_producto from modulos";
        }
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql,4, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "INSERT INTO modulos (nombre_modulo,descripcion_modulo,id_producto) values ('" . $this->vlc_nombre_modulo . "','" . $this->vlc_descripcion_modulo . "',".$this->vlc_id_producto.")";
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);        
        return $resultado;
    }
    function MTD_DB_ACTUALIZAR ()
    {
        $resultado = false;
        $vlf_sql = "UPDATE modulos set 
        nombre_modulo ='" . $this->vlc_nombre_modulo . "',
        descripcion_modulo ='" . $this->vlc_descripcion_modulo. "',
        id_producto =".$this->vlc_id_producto."          
        where id_modulo=" . $this->vlc_id_modulo;
        
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);        
        return $resultado;
    }
    function MTD_DB_ELIMINAR ()
    {
        $resultado = false;
        $vlf_sql = "DELETE FROM modulos  where id_modulo =" . $this->vlc_id_modulo;
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>