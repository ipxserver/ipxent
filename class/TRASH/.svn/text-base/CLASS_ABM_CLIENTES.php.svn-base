<?php
class CLASS_ABM_CLIENTES
{
    private $vlc_codigo_html;
    private $vlc_nombre_holding;
    private $vlc_nombre_empresa;
    private $vlc_nombre_sucursal;
    private $vlc_nombre_cliente;
    private $vlc_apellido_cliente;
    private $vlc_nro_contrato;
    private $vlc_descripcion_contrato;
    private $vlc_tipo_contrato;
    private $vlc_direccion;
    private $vlc_ciudad;
    private $vlc_zona_tecnica;
    private $vlc_zona_comercial;
    private $vlc_zona_geografica;
    private $vlc_pais;
    private $vlc_telefono;
    private $vlc_fax;
    private $vlc_email;
    private $vlc_rubro;
    private $vlc_project_manager;
    private $vlc_tiempo_viaje;
    private $vlc_descripcion_tiempo_viaje;
    private $vlc_fecha_inicio_contrato;
    private $vlc_website;    
    private $vlc_id_cliente;
    function __construct ()
    {
       $this->MTD_LIMPIAR_VARIABLES();
        $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_LIMPIAR_VARIABLES()
    {
    	$this->vlc_codigo_html = "";
        $this->vlc_codigo_html="";
	    $this->vlc_nombre_holding="";
	    $this->vlc_nombre_empresa="";
	    $this->vlc_nombre_sucursal="";
	    $this->vlc_nombre_cliente="";
	    $this->vlc_apellido_cliente="";
	    $this->vlc_nro_contrato="";
	    $this->vlc_descripcion_contrato="";
	    $this->vlc_tipo_contrato="";
	    $this->vlc_direccion="";
	    $this->vlc_ciudad="";
	    $this->vlc_zona_tecnica="";
	    $this->vlc_zona_comercial="";
	    $this->vlc_zona_geografica="";
	    $this->vlc_pais="";
	    $this->vlc_telefono="";
	    $this->vlc_fax="";
	    $this->vlc_email="";
	    $this->vlc_rubro="";
	    $this->vlc_project_manager="";
	    $this->vlc_tiempo_viaje="";
	    $this->vlc_descripcion_tiempo_viaje="";    
	    $this->vlc_id_cliente="";
		$this->vlc_fecha_inicio_contrato="";
		$this->vlc_website="";
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
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-abm-clientes.html');
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
            $this->MTD_LIMPIAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Cliente', $vl_cod_html_base);
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
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Cliente', $vl_cod_html_base);
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
            $vlf_resultado = ""; 
            $this->MTD_RECIBIR_DATOS_DB($vlp_datos_grilla);           
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);   
        	         
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_ACTUALIZAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Actualizar Cliente', $vl_cod_html_base);
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
            $this->MTD_LIMPIAR_VARIABLES();
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Cliente', $vl_cod_html_base);            
            //FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Cliente', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', $vlf_resultado, $vl_cod_html_base);            
        }
        else
        {
        
            $vl_cod_html_base = $this->MTD_APLICAR_TEMPLATE($vl_cod_html_base);
        	$vl_cod_html_base = FN_REEMPLAZAR('{accion-formulario}', 'MTD_AGREGAR', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{titulo-accion-formulario}', 'Agregar Cliente', $vl_cod_html_base);
            $vl_cod_html_base = FN_REEMPLAZAR('{resultado-operacion}', '', $vl_cod_html_base);
        }
        $vl_cod_html_grilla = $this->MTD_GENERAR_GRILLA();
        $vl_cod_html_base = FN_REEMPLAZAR('{grilla-datos-clientes}', $vl_cod_html_grilla, $vl_cod_html_base);
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    function MTD_GENERAR_GRILLA ()
    {
        $vlf_codigo_html_grilla;
        $vlp_datos_grilla = $this->MTD_DB_LISTAR();
        $vp_titulo = array();
        $vp_titulo[0] = "Id";
        $vp_titulo[1] = "Holding";
        $vp_titulo[2] = "Empresa";
        $vp_titulo[3] = "Sucursal";        
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
        $lnk_modificar = "index.php?seccion=clientes&MTD_EDITAR=true";
        $lnk_eliminar = "index.php?seccion=clientes&MTD_ELIMINAR=true";
        $corregir = false;
        $lnk_corregir = "";
        //$vlp_planes_clases
        $vlf_codigo_html_grilla = FN_HTML_ARMAR_GRILLA($vp_titulo, $vlp_datos_grilla, $vp_css_titulos, $vp_css_filas_datos, $vp_css_columnas_datos, $visualizar, $modificar, $eliminar, $lnk_visualizar, $lnk_modificar, $lnk_eliminar, $corregir, $lnk_corregir);
        return $vlf_codigo_html_grilla;
    }
    function MTD_APLICAR_TEMPLATE ($vp_codigo_html)
    {
    	  list($dia,$mes,$anho) = split("/",$this->vlc_fecha_inicio_contrato);
     	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-holding}'	, $this->vlc_nombre_holding	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-empresa}'	, $this->vlc_nombre_empresa	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-sucursal}'	, $this->vlc_nombre_sucursal, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-rubro}'		, $this->vlc_rubro	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nombre-cliente}'	, $this->vlc_nombre_cliente	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-apellido-cliente}'	, $this->vlc_apellido_cliente, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-nro-contrato}'		, $this->vlc_nro_contrato	, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-contrato-dia}'	,$dia, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-contrato-mes}'	,$mes, $vp_codigo_html);
    	$vp_codigo_html = FN_REEMPLAZAR('{tb-fecha-contrato-anho}'	,$anho, $vp_codigo_html);
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
	    $this->vlc_nombre_holding	= FN_RECIBIR_VARIABLES('tb_nombre_holding') ;
	    $this->vlc_nombre_empresa	= FN_RECIBIR_VARIABLES('tb_nombre_empresa');
	    $this->vlc_nombre_sucursal	= FN_RECIBIR_VARIABLES('tb_nombre_sucursal');
	    $this->vlc_nombre_cliente	= FN_RECIBIR_VARIABLES('tb_nombre_cliente');
	    $this->vlc_apellido_cliente	= FN_RECIBIR_VARIABLES('tb_apellido_cliente');
	    $this->vlc_nro_contrato		= FN_RECIBIR_VARIABLES('tb_nro_contrato');
	    $this->vlc_descripcion_contrato	= FN_RECIBIR_VARIABLES('tb_descripcion_contrato');
	    $this->vlc_tipo_contrato	= FN_RECIBIR_VARIABLES('tb_tipo_contrato');
	    $this->vlc_direccion		= FN_RECIBIR_VARIABLES('tb_direccion');
	    $this->vlc_ciudad			= FN_RECIBIR_VARIABLES('tb_ciudad');
	    $this->vlc_zona_tecnica		= FN_RECIBIR_VARIABLES('tb_zona_tecnica');
	    $this->vlc_zona_comercial	= FN_RECIBIR_VARIABLES('tb_zona_comercial');
	    $this->vlc_zona_geografica	= FN_RECIBIR_VARIABLES('tb_zona_geografica');
	    $this->vlc_pais				= FN_RECIBIR_VARIABLES('lst_paises');
	    $this->vlc_telefono			= FN_RECIBIR_VARIABLES('tb_telefono');
	    $this->vlc_fax				= FN_RECIBIR_VARIABLES('tb_fax');
	    $this->vlc_email			= FN_RECIBIR_VARIABLES('tb_email');
	    $this->vlc_rubro			= FN_RECIBIR_VARIABLES('tb_nombre_rubro');
	    $this->vlc_project_manager	= FN_RECIBIR_VARIABLES('tb_project_manager');
	    $this->vlc_tiempo_viaje		= FN_RECIBIR_VARIABLES('lst_horas_visita').":".FN_RECIBIR_VARIABLES('lst_minutos_visita');	       
	    $this->vlc_id_cliente		= FN_RECIBIR_VARIABLES('tb_id_cliente');
		$this->vlc_fecha_inicio_contrato= FN_RECIBIR_VARIABLES('tb-fecha-contrato-anho')."/".FN_RECIBIR_VARIABLES('tb-fecha-contrato-mes')."/".FN_RECIBIR_VARIABLES('tb-fecha-contrato-dia');
		$this->vlc_website			= FN_RECIBIR_VARIABLES('tb_website');
        
        if (isset($_GET['id']))
        {
            $this->vlc_id_cliente = $_GET['id'];
        }
    }
 	function MTD_RECIBIR_DATOS_DB ($vp_arreglo_datos)
    {    	
	    $this->vlc_id_cliente				= $vp_arreglo_datos[0][0];
    	$this->vlc_nombre_holding			= $vp_arreglo_datos[0][1];
	    $this->vlc_nombre_empresa			= $vp_arreglo_datos[0][2];
	    $this->vlc_nombre_sucursal			= $vp_arreglo_datos[0][3];
	    $this->vlc_nombre_cliente			= $vp_arreglo_datos[0][4];
	    $this->vlc_apellido_cliente			= $vp_arreglo_datos[0][5];
	    $this->vlc_nro_contrato				= $vp_arreglo_datos[0][6];
	    $this->vlc_descripcion_contrato		= $vp_arreglo_datos[0][7];	    
	    $this->vlc_direccion				= $vp_arreglo_datos[0][8];
	    $this->vlc_ciudad					= $vp_arreglo_datos[0][9];
	    $this->vlc_zona_tecnica				= $vp_arreglo_datos[0][10];
	    $this->vlc_zona_comercial			= $vp_arreglo_datos[0][11];
	    $this->vlc_zona_geografica			= $vp_arreglo_datos[0][12];
	    $this->vlc_pais						= $vp_arreglo_datos[0][13];
	    $this->vlc_telefono					= $vp_arreglo_datos[0][14];
	    $this->vlc_fax						= $vp_arreglo_datos[0][15];
	    $this->vlc_email					= $vp_arreglo_datos[0][16];
	    $this->vlc_rubro					= $vp_arreglo_datos[0][17];
	    $this->vlc_project_manager			= $vp_arreglo_datos[0][18];
	    $this->vlc_tiempo_viaje				= $vp_arreglo_datos[0][19];
	    $this->vlc_fecha_inicio_contrato	= $vp_arreglo_datos[0][20];
	    $this->vlc_website					= $vp_arreglo_datos[0][21];	                
    }
    function MTD_DB_LISTAR ($vp_filtrar = false)
    {
    	$vlf_sql=" 
    		SELECT
			id_cliente, 
			nombre_holding, 
			nombre_empresa, 
			nombre_sucursal, 
			nombre_cliente, 
			apellido_cliente, 
			nro_contrato, 
			descripcion_contrato, 			
			direccion, 
			ciudad, 
			zonatecnica, 
			zonacomercial, 
			zonageografica, 
			pais, 
			telefono, 
			fax, 
			email, 
			rubro, 
			project_manager, 
			tiempo_viaje, 			
			DATE_FORMAT(fecha_inicio_contrato, '%d/%m/%Y'), 
			website
			FROM clientes ";
        if ($vp_filtrar)
        {
            $vlf_sql .= "where id_cliente=" . $this->vlc_id_cliente;
        }
       
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql,22, $vlg_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_AGREGAR ()
    {
        $resultado = false;
        $vlf_sql = "
        INSERT INTO clientes
    	( 
    	nombre_holding,
    	nombre_empresa, 
    	nombre_sucursal, 
    	nombre_cliente, 
    	apellido_cliente, 
    	nro_contrato, 
    	descripcion_contrato, 
    	tipo_contrato, 
    	direccion, 
    	ciudad, 
    	zonatecnica, 
    	zonacomercial, 
    	zonageografica, 
    	pais, 
    	telefono, 
    	fax, 
    	email, 
    	rubro, 
    	project_manager, 
    	tiempo_viaje, 
    	descripcion_tiempo_viaje, 
    	fecha_inicio_contrato, 
    	website)
		VALUES
    	('".		
		$this->vlc_nombre_holding . "','".
		$this->vlc_nombre_empresa . "','". 
		$this->vlc_nombre_sucursal . "','".
		$this->vlc_nombre_cliente	. "','".
		$this->vlc_apellido_cliente . "','". 
		$this->vlc_nro_contrato . "','". 
		$this->vlc_descripcion_contrato . "','". 
		$this->vlc_tipo_contrato. "','". 
		$this->vlc_direccion. "','". 
		$this->vlc_ciudad. "','". 
		$this->vlc_zona_tecnica. "','". 
		$this->vlc_zona_comercial. "','". 
		$this->vlc_zona_geografica. "','". 
		$this->vlc_pais. "','". 
		$this->vlc_telefono. "','". 
		$this->vlc_fax. "','". 
		$this->vlc_email. "','". 
		$this->vlc_rubro. "','". 
		$this->vlc_project_manager. "','". 
		$this->vlc_tiempo_viaje. "','". 
		$this->vlc_descripcion_tiempo_viaje. "','". 
		$this->vlc_fecha_inicio_contrato. "','". 
		$this->vlc_website . "');";
                
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        //echo "sql: $vlf_sql";        
        return $resultado;
    }
    function MTD_DB_ACTUALIZAR ()
    {
        $resultado = false;
        $vlf_sql = "
        UPDATE
  		clientes
		SET
		nombre_holding 		= '".$this->vlc_nombre_holding."', 
		nombre_empresa 		= '".$this->vlc_nombre_empresa."', 
		nombre_sucursal 	= '".$this->vlc_nombre_sucursal."', 
		nombre_cliente 		= '".$this->vlc_nombre_cliente."', 
		apellido_cliente 	= '".$this->vlc_apellido_cliente."', 
		nro_contrato 		= '".$this->vlc_nro_contrato."', 
		descripcion_contrato= '".$this->vlc_descripcion_contrato."', 
		tipo_contrato 		= '".$this->vlc_tipo_contrato."', 
		direccion 			= '".$this->vlc_direccion."', 
		ciudad 				= '".$this->vlc_ciudad."', 
		zonatecnica 		= '".$this->vlc_zona_tecnica."', 
		zonacomercial 		= '".$this->vlc_zona_comercial."', 
		zonageografica 		= '".$this->vlc_zona_geografica."', 
		pais 				= '".$this->vlc_pais."', 
		telefono 			= '".$this->vlc_telefono."', 
		fax 				= '".$this->vlc_fax."', 
		email 				= '".$this->vlc_email."', 
		rubro 				= '".$this->vlc_rubro."', 
		project_manager 	= '".$this->vlc_project_manager."', 
		tiempo_viaje 		= '".$this->vlc_tiempo_viaje."', 
		descripcion_tiempo_viaje 	= '".$this->vlc_descripcion_tiempo_viaje."', 
		fecha_inicio_contrato 		= '".$this->vlc_fecha_inicio_contrato."', 
		website 			= '".$this->vlc_website."' 
		WHERE
  		id_cliente = ".$this->vlc_id_cliente.";";
                
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);     
       // echo  $vlf_sql;   
        return $resultado;
    }
    function MTD_DB_ELIMINAR ()
    {
        $resultado = false;
        $vlf_sql = "DELETE FROM clientes  where id_cliente =" . $this->vlc_id_cliente;
        $resultado = FN_RUN_NONQUERY($vlf_sql, $vlg_conexion);
        return $resultado;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        return $this->vlc_codigo_html;
    }
}
?>
