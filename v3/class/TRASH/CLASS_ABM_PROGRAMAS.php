<?php
class CLASS_ABM_PROGRAMAS
{
    private $vlc_codigo_html;
    private $vlc_nombre_programa;
    private $vlc_descripcion_programa;
    private $vlc_tipo_programa;
    private $vlc_sub_modulo;
    private $vlc_id_programa;
    private $vlc_id_modulo;
    function __construct ()
    {
        $this->vlc_nombre_programa = "";
        $this->vlc_id_programa = "";
        $this->vlc_id_modulo = "";
        $this->vlc_descripcion_programa="";
        $this->vlc_tipo_programa="";
        $this->vlc_codigo_html = "";
        $this->vlc_sub_modulo="";
        $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_INICIALIZAR_PAGINA ()
    {
        /*
         * TODO: 
         * VALORES DEL FORMULARIO
         * ----------------------
         * [tb_nombre_programa]
         * [id_programa]
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
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-abm-programas.html');
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
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Programa', $vl_cod_html_base);
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
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Programa', $vl_cod_html_base);
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
            $vlf_id_programa = $vlp_datos_grilla[0][0];
            $vlf_nombre_programa = $vlp_datos_grilla[0][1];
            $vlf_descripcion_programa = $vlp_datos_grilla[0][2];
            $vlf_tipo_programa = $vlp_datos_grilla[0][3];
            $vlf_resultado = ""; 
            $this->MTD_RECIBIR_DATOS_DB($vlp_datos_grilla);           
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);   
        	         
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_ACTUALIZAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Actualizar Programa', $vl_cod_html_base);
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
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Programa', $vl_cod_html_base);
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
        $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-programas}', $vl_cod_html_grilla, $vl_cod_html_base);
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    function MTD_GENERAR_GRILLA ()
    {
        $vlf_codigo_html_grilla;
        $vlp_datos_grilla = $this->MTD_DB_LISTAR();
        $vp_titulo = array();
        $vp_titulo[0] = "Id";
        $vp_titulo[1] = "Nombre";
        $vp_titulo[2] = "Modulo";
        $vp_titulo[3] = "Producto";
        $vp_css_titulos = array();
        $vp_css_titulos[0] = "columna_id titulo_listado";
        $vp_css_titulos[1] = "columna_fecha titulo_listado";
        $vp_css_titulos[2] = "columna_fecha titulo_listado";
        $vp_css_titulos[3] = "columna_fecha titulo_listado";
        $vp_css_filas_datos = array();
        $vp_css_filas_datos[0] = "texto columna_id";
        $vp_css_filas_datos[1] = "texto columna_fecha";
        $vp_css_filas_datos[2] = "texto columna_fecha";
        $vp_css_filas_datos[3] = "texto columna_fecha";
        $vp_css_columnas_datos = array();
        $vp_css_columnas_datos[0] = "columna_id";
        $vp_css_columnas_datos[1] = "columna_fecha";
        $vp_css_columnas_datos[2] = "columna_fecha";
        $vp_css_columnas_datos[3] = "columna_fecha";
        $visualizar = true;
        $modificar = true;
        $eliminar = true;
        $lnk_visualizar = "#";
        $lnk_modificar = "index.php?seccion=programas&MTD_EDITAR=true";
        $lnk_eliminar = "index.php?seccion=programas&MTD_ELIMINAR=true";
        $corregir = false;
        $lnk_corregir = "";
        //$vlp_planes_clases
        $vlf_codigo_html_grilla = FN_HTML_ARMAR_GRILLA($vp_titulo, $vlp_datos_grilla, $vp_css_titulos, $vp_css_filas_datos, $vp_css_columnas_datos, $visualizar, $modificar, $eliminar, $lnk_visualizar, $lnk_modificar, $lnk_eliminar, $corregir, $lnk_corregir);
        return $vlf_codigo_html_grilla;
    }
    function MTD_APLICAR_TEMPLATE ($vp_codigo_html)
    {
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-programa}', $this->vlc_nombre_programa, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{tb-descripcion-programa}', $this->vlc_descripcion_programa, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{id-programa}', $this->vlc_id_programa, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{tb-tipo-programa}', $this->vlc_tipo_programa, $vp_codigo_html);
        //== DATOS DEL MODULO ==//        
        $vp_codigo_html_lista_modulo = FN_HTML_ARMAR_LISTA_ST($this->MTD_DB_LISTAR_MODULOS(), '', 'lst_modulos', $this->vlc_id_modulo, false, '');
        $vp_codigo_html = FN_REEMPLAZAR('{select-modulo}', $vp_codigo_html_lista_modulo, $vp_codigo_html);
        
    
          //== DATOS DEL SUB MODULO==//        
        $vp_codigo_html_lista_sub_modulo = FN_HTML_ARMAR_LISTA_ST($this->MTD_DB_LISTAR_SUBMODULOS(), '', 'lst_sub_modulos', $this->vlc_sub_modulo, false, '');
        $vp_codigo_html = FN_REEMPLAZAR('{select-sub-modulo}', $vp_codigo_html_lista_sub_modulo, $vp_codigo_html);
        return  $vp_codigo_html;
    }
    function MTD_DB_LISTAR_SUBMODULOS()
    {
    	$arreglo_submodulos= array();
    	$arreglo_submodulos[0][0]="Tablas";
    	$arreglo_submodulos[1][0]="Informes";
    	$arreglo_submodulos[2][0]="Rutinas";
    	$arreglo_submodulos[0][1]="Tablas";
    	$arreglo_submodulos[1][1]="Informes";
    	$arreglo_submodulos[2][1]="Rutinas";
    	return $arreglo_submodulos;    	    
    }
    
  	function MTD_DB_LISTAR_MODULOS()
    {
        $vlf_sql = "select m.id_modulo,p.nombre_producto, m.nombre_modulo  from productos as p, modulos as m where m.id_producto = p.id_producto order by p.nombre_producto AND m.nombre_modulo ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3, $vlg_conexion);
        $vlf_arreglo_datos_final = array();
        $contador=0;
        $cantidad_registros = sizeof ($vlf_arreglo_datos);
        while ($contador < $cantidad_registros)
        {
        	$vlf_arreglo_datos_final[$contador][0]= $vlf_arreglo_datos[$contador][0];
        	$vlf_arreglo_datos_final[$contador][1]= $vlf_arreglo_datos[$contador][1] . " -> " . $vlf_arreglo_datos[$contador][2];
        	$contador++;
        }
       // print_r($vlf_arreglo_datos_final);
        return $vlf_arreglo_datos_final;
    }
    function MTD_RECIBIR_DATOS ()
    {
    	$this->vlc_nombre_programa = FN_RECIBIR_VARIABLES('tb_nombre_programa','POST');
    	$this->vlc_descripcion_programa = FN_RECIBIR_VARIABLES('tb_descripcion_programa','POST');
    	$this->vlc_id_programa = FN_RECIBIR_VARIABLES('id_programa','POST');
    	$this->vlc_id_modulo = FN_RECIBIR_VARIABLES('lst_modulos','POST');
    	$this->vlc_tipo_programa = FN_RECIBIR_VARIABLES('tb_tipo_programa','POST');
    	$this->vlc_sub_modulo = FN_RECIBIR_VARIABLES('lst_sub_modulos','POST');
    
        if (isset($_GET['id']))
        {
            $this->vlc_id_programa = $_GET['id'];
        }
    }
 	function MTD_RECIBIR_DATOS_DB ($vp_arreglo_datos)
    {
        $this->vlc_id_programa          = $vp_arreglo_datos[0][0];
        $this->vlc_nombre_programa		= $vp_arreglo_datos[0][1];
        $this->vlc_descripcion_programa	= $vp_arreglo_datos[0][4];
        $this->vlc_id_modulo          	= $vp_arreglo_datos[0][3];
        $this->vlc_tipo_programa 		= $vp_arreglo_datos[0][6];
        $this->vlc_sub_modulo 			= $vp_arreglo_datos[0][7];        
        
    }
    function MTD_DB_LISTAR ($vp_filtrar = false)
    {
        if ($vp_filtrar)
        {
            $vlf_sql = "select p.id_programa,p.nombre_programa, m.nombre_modulo, pr.nombre_producto,descripcion_programa,m.id_modulo,p.tipo_programa,p.sub_modulo 
			from programas as p, modulos as m, productos as pr 
			where p.id_modulo = m.id_modulo 
			and m.id_producto = pr.id_producto 
			and p.id_programa =" . $this->vlc_id_programa;
            //echo "sql: $vlf_sql";
        }
        else
        {
        	$vlf_sql = "select p.id_programa,p.nombre_programa, m.nombre_modulo, pr.nombre_producto,descripcion_programa,m.id_modulo,p.tipo_programa ,p.sub_modulo
			from programas as p, modulos as m, productos as pr 
			where p.id_modulo = m.id_modulo 
			and m.id_producto = pr.id_producto"; 
			
            //$vlf_sql = "select id_programa,nombre_programa,descripcion_programa,id_modulo,tipo_programa from programas";
        }
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql,8, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "INSERT INTO programas (nombre_programa,descripcion_programa,id_modulo,tipo_programa,sub_modulo) 
        values ('" . $this->vlc_nombre_programa . "','" . 
        $this->vlc_descripcion_programa . "',".
        $this->vlc_id_modulo.",'".
        $this->vlc_tipo_programa."','".
        $this->vlc_sub_modulo."')";
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        echo "sql: $vlf_sql";        
        return $resultado;
    }
    function MTD_DB_ACTUALIZAR ()
    {
        $resultado = false;
        $vlf_sql = "UPDATE programas set 
        nombre_programa ='" . $this->vlc_nombre_programa . "',
        descripcion_programa ='" . $this->vlc_descripcion_programa. "',
        id_modulo =".$this->vlc_id_modulo.",
        tipo_programa ='".$this->vlc_tipo_programa."',
        sub_modulo ='".$this->vlc_sub_modulo."'           
        where id_programa=" . $this->vlc_id_programa;        
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);        
        return $resultado;
    }
    function MTD_DB_ELIMINAR ()
    {
        $resultado = false;
        $vlf_sql = "DELETE FROM programas  where id_programa =" . $this->vlc_id_programa;
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>