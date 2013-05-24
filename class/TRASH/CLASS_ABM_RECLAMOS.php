<?php
class CLASS_ABM_RECLAMOS
{
    private $vlc_codigo_html;
    private $vlc_id_reclamo;
    private $vlc_id_cliente;
    private $vlc_id_usuario;
    private $vlc_llamo;
    private $vlc_fecha_reclamo;
    private $vlc_staff;
    private $vlc_tipo_requerimiento;
    private $vlc_resumen_requerimiento;
    private $vlc_detalle_requerimiento;
    private $vlc_nombre_producto;
    private $vlc_nombre_modulo;
    private $vlc_nombre_programa;
    private $vlc_tipo_contrato;
    private $vlc_status_modulo;        
    private $vlc_tipo_programa;
    private $vlc_estado_comercial;
    private $vlc_estado_programa;
    private $vlc_imagen1;
    private $vlc_imagen2;
    private $vlc_imagen3;
    private $vlc_id_producto;
    private $vlc_id_modulo;
    private $vlc_id_programa;
    
    function __construct ()
    {
       $this->MTD_LIMPIAR_VARIABLES();
       $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_LIMPIAR_VARIABLES()
    {
    	$this->vlc_codigo_html="";
	    $this->vlc_id_cliente="";
	    $this->vlc_id_usuario="";
	    $this->vlc_llamo="";
	    $this->vlc_fecha_reclamo="";
	    $this->vlc_staff="";
	    $this->vlc_tipo_requerimiento="";
	    $this->vlc_resumen_requerimiento="";
	    $this->vlc_detalle_requerimiento="";
	    $this->vlc_nombre_producto="";
	    $this->vlc_nombre_modulo="";
	    $this->vlc_nombre_programa="";
	    $this->vlc_tipo_contrato="";
	    $this->vlc_status_modulo="";        
	    $this->vlc_tipo_programa="";
	    $this->vlc_estado_comercial="";
	    $this->vlc_estado_programa="";
	    $this->vlc_imagen1="";
	    $this->vlc_imagen2="";
	    $this->vlc_imagen3="";
	    $this->vlc_id_producto="";
	    $this->vlc_id_modulo="";
	    $this->vlc_id_programa="";
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
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-abm-reclamos.html');
        $vl_cod_html_base2 = FN_LEER_TPL('tpl/tpl-abm-reclamos-aviso.html');
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
            	$this->vlc_id_reclamo = FN_DB_ULTIMO_REGISTRO();
            	$vl_cod_html_base = $vl_cod_html_base2;
                $vlf_resultado = "<img src='imagenes/resultado_ok.png'> Se agrego correctamente el Registro";
                
            }
            else
            {
                $vlf_resultado = "<img src='imagenes/resultado_mal.png'> Atencion Ocurrio un error durante la operacion, verifique los datos";
            }
            $this->MTD_LIMPIAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Reclamo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);
           
        }        
        else
        {
        
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
        	$vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Reclamo', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', '', $vl_cod_html_base);
        }        
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
	
    function MTD_APLICAR_TEMPLATE ($vp_codigo_html)
    {
    	$fecha="";
    	$fecha= date("d/m/y");	
    	  list($dia,$mes,$anho) = split("/",$fecha);
    	  
    	//== DATOS DEL PRODUCTO ==//        
        $vp_codigo_html_lista_categoria = FN_HTML_ARMAR_LISTA_ST($this->MTD_DB_LISTAR_CATEGORIAS(), 'Categoria de Reclamos', 'tb_tipo_requerimiento', $this->vlc_tipo_requerimiento, false, '');
        $vp_codigo_html = FN_REEMPLAZAR('{lista-categoria-reclamos}', $vp_codigo_html_lista_categoria, $vp_codigo_html);
        $vp_codigo_html = FN_REEMPLAZAR('{id-reclamo}', $this->vlc_id_reclamo, $vp_codigo_html);
         
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-reclamo-dia}'	,$dia, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-reclamo-dia}'	,$dia, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-reclamo-mes}'	,$mes, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-reclamo-anho}'	,$anho, $vp_codigo_html);
    	
     	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-holding}'	, $this->vlc_nombre_holding	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-empresa}'	, $this->vlc_nombre_empresa	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-sucursal}'	, $this->vlc_nombre_sucursal, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-rubro}'		, $this->vlc_rubro	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-cliente}'	, $this->vlc_nombre_cliente	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-apellido-cliente}'	, $this->vlc_apellido_cliente, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nro-contrato}'		, $this->vlc_nro_contrato	, $vp_codigo_html);
    	
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-descripcion-contrato}'	,$this->vlc_descripcion_contrato	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-direccion}'		, $this->vlc_direccion	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-zona-tecnica}'		, $this->vlc_zona_tecnica	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-zona-geografica}'	, $this->vlc_zona_geografica, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-telefono}'			, $this->vlc_telefono, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fax}'				, $this->vlc_fax	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-zona-comercial}'	, $this->vlc_zona_comercial , $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-email}'			, $this->vlc_email , $vp_codigo_html);
		$vp_codigo_html = FN_REEMPLAZAR('{tb-website}'			, $this->vlc_website , $vp_codigo_html);
		$vp_codigo_html = FN_REEMPLAZAR('{tb-project-manager}'	, $this->vlc_project_manager , $vp_codigo_html);
		$vp_codigo_html = FN_REEMPLAZAR('{tb-ciudad}'			, $this->vlc_ciudad , $vp_codigo_html);    	
		$vp_codigo_html = FN_REEMPLAZAR('{tb-id-cliente}'			, $this->vlc_id_cliente , $vp_codigo_html);
    	
        //== LISTA DE PAISES ==//
      
		$arreglo_paises = $this->MTD_DB_LISTAR_PAISES();		        
        $vp_codigo_html_lista_paises = FN_HTML_ARMAR_LISTA_ST($arreglo_paises, '', 'lst_paises', $this->vlc_pais, false, '');
        $vp_codigo_html = FN_REEMPLAZAR('{select-pais}', $vp_codigo_html_lista_paises, $vp_codigo_html);

        //== LISTA DE HORAS ==//
        list($hora,$minuto) = split(":",$this->vlc_tiempo_viaje);
		$arreglo_horas = $this->MTD_DB_LISTAR_HORAS();		        
        $vp_codigo_html_lista_horas = FN_HTML_ARMAR_LISTA_ST($arreglo_horas, '', 'lst_horas_visita', $hora, false, '','small');
        $vp_codigo_html = FN_REEMPLAZAR('{select-hora}', $vp_codigo_html_lista_horas, $vp_codigo_html);
        
        $arreglo_minutos = $this->MTD_DB_LISTAR_MINUTOS();		        
        $vp_codigo_html_lista_minutos = FN_HTML_ARMAR_LISTA_ST($arreglo_minutos, '', 'lst_minutos_visita', $minuto, false, '','small');
        $vp_codigo_html = FN_REEMPLAZAR('{select-minuto}', $vp_codigo_html_lista_minutos, $vp_codigo_html);
        
        return  $vp_codigo_html;
        
    }
	function MTD_DB_LISTAR_HORAS()
    {
    	$arreglo_horas= array();
    	$contador=0;
    	while ($contador < 25)
    	{
    		$arreglo_horas[$contador][0]=$contador;
    		$arreglo_horas[$contador][1]=$contador;
    		$contador++;
    	}    	    	    
    	return $arreglo_horas;    	    
    } 
	function MTD_DB_LISTAR_CATEGORIAS ()
    {
        $vlf_sql = "select nombre_categoria_reclamo,nombre_categoria_reclamo from categorias_reclamos";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR_MINUTOS()
    {
    	$arreglo_minutos= array();
    	$arreglo_minutos[0][0]="00";
    	$arreglo_minutos[1][0]="15";
    	$arreglo_minutos[2][0]="30";
    	$arreglo_minutos[3][0]="45";
    	$arreglo_minutos[0][1]="00";
    	$arreglo_minutos[1][1]="15";
    	$arreglo_minutos[2][1]="30";
    	$arreglo_minutos[3][1]="45";
    	return $arreglo_minutos;
    }
    function MTD_DB_LISTAR_PAISES()
    {
    	$arreglo_paises= array();
    	$arreglo_paises[0][0]="ARGENTINA";
    	$arreglo_paises[1][0]="BOLIVIA";
    	$arreglo_paises[2][0]="BRASIL";
    	$arreglo_paises[3][0]="URUGUAY";
    	$arreglo_paises[4][0]="PARAGUAY";
    	$arreglo_paises[5][0]="PERU";
    	$arreglo_paises[6][0]="CHILE";
    	$arreglo_paises[7][0]="VENEZUELA";
    	$arreglo_paises[8][0]="NICARAGUA";
    	
    	$arreglo_paises[0][1]="ARGENTINA";
    	$arreglo_paises[1][1]="BOLIVIA";
    	$arreglo_paises[2][1]="BRASIL";
    	$arreglo_paises[3][1]="URUGUAY";
    	$arreglo_paises[4][1]="PARAGUAY";
    	$arreglo_paises[5][1]="PERU";
    	$arreglo_paises[6][1]="CHILE";
    	$arreglo_paises[7][1]="VENEZUELA";
    	$arreglo_paises[8][1]="NICARAGUA";
    	
    	return $arreglo_paises;
    	    	
    }
    /*
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
	*/
    function MTD_RECIBIR_DATOS ()
    { 
    	  	        		
		$this->vlc_id_cliente			=FN_RECIBIR_VARIABLES('tb_id_cliente') ;
		$this->vlc_id_usuario			=FN_RECIBIR_VARIABLES('tb_id_usuario') ;
		$this->vlc_llamo				=FN_RECIBIR_VARIABLES('tb_llamo') ;
		$this->vlc_fecha_reclamo		=FN_RECIBIR_VARIABLES('tb-fecha-reclamo-anho')."/".FN_RECIBIR_VARIABLES('tb-fecha-reclamo-mes')."/".FN_RECIBIR_VARIABLES('tb-fecha-reclamo-dia') ;
		$this->vlc_staff				=FN_RECIBIR_VARIABLES('tb_staff') ;
		$this->vlc_tipo_requerimiento	=FN_RECIBIR_VARIABLES('tb_tipo_requerimiento') ;
		$this->vlc_resumen_requerimiento=FN_RECIBIR_VARIABLES('tb_resumen_requerimiento') ;
		$this->vlc_detalle_requerimiento=FN_RECIBIR_VARIABLES('tb_detalle_requerimiento') ;
		$this->vlc_nombre_producto		=FN_RECIBIR_VARIABLES('tb_nombre_producto') ;
		$this->vlc_nombre_modulo		=FN_RECIBIR_VARIABLES('tb_nombre_modulo') ;
		$this->vlc_nombre_programa		=FN_RECIBIR_VARIABLES('tb_nombre_programa') ;
		$this->vlc_tipo_contrato		=FN_RECIBIR_VARIABLES('tb_tipo_contrato') ;
		$this->vlc_status_modulo		=FN_RECIBIR_VARIABLES('tb_status_modulo') ;        
		$this->vlc_tipo_programa		=FN_RECIBIR_VARIABLES('tb_tipo_programa') ;
		$this->vlc_estado_comercial		=FN_RECIBIR_VARIABLES('tb_estado_comercial') ;
		$this->vlc_estado_programa		=FN_RECIBIR_VARIABLES('tb_estado_programa') ;
		$this->vlc_imagen1				=FN_RECIBIR_VARIABLES('tb_imagen1') ;
		$this->vlc_imagen2				=FN_RECIBIR_VARIABLES('tb_imagen2') ;
		$this->vlc_imagen3				=FN_RECIBIR_VARIABLES('tb_imagen3') ;
		$this->vlc_id_producto			=FN_RECIBIR_VARIABLES('tb_id_producto') ;
		$this->vlc_id_modulo			=FN_RECIBIR_VARIABLES('tb_id_modulo') ;
		$this->vlc_id_programa			=FN_RECIBIR_VARIABLES('tb_id_programa') ;
		       
        if (isset($_GET['id']))
        {
            $this->vlc_id_cliente = $_GET['id'];
        }
       
        
    }
 	function MTD_RECIBIR_DATOS_DB ($vp_arreglo_datos)
    {    	
	     
    }
    
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "
        INSERT INTO reclamos
		    (		
		id_cliente, 
		id_usuario, 
		llamo, 
		fecha_reclamo, 
		staff, 
		tipo_requerimiento, 
		resumen_requerimiento, 
		detalle_requerimiento, 
		nombre_producto, 
		nombre_modulo, 
		nombre_programa, 
		tipo_contrato, 
		status_modulo, 
		tipo_programa, 
		estado_comercial, 
		estado_programa, 
		imagen1, 
		imagen2, 
		imagen3, 
		id_producto, 
		id_modulo, 
		id_programa, 
		id_producto_contratado)
		VALUES
		(" 
		.$this->vlc_id_cliente."," 
		.$this->vlc_id_usuario.",'" 
		.$this->vlc_llamo."','" 
		.$this->vlc_fecha_reclamo."','" 
		.$this->vlc_staff."','" 
		.$this->vlc_tipo_requerimiento."','" 
		.$this->vlc_resumen_requerimiento."','" 
		.$this->vlc_detalle_requerimiento."','" 
		.$this->vlc_nombre_producto."','" 
		.$this->vlc_nombre_modulo."','" 
		.$this->vlc_nombre_programa."','" 
		.$this->vlc_tipo_contrato."','" 
		.$this->vlc_status_modulo."','" 
		.$this->vlc_tipo_programa."','" 
		.$this->vlc_estado_comercial."','" 
		.$this->vlc_estado_programa."','" 
		.$this->vlc_imagen1."','" 
		.$this->vlc_imagen2."','" 
		.$this->vlc_imagen3."'," 
		.$this->vlc_id_producto."," 
		.$this->vlc_id_modulo."," 
		.$this->vlc_id_programa.",0)";
		        
    	//		
		//$this->vlc_nombre_holding . "','".
		            
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        //echo "sql: $vlf_sql";        
        return $resultado;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>
