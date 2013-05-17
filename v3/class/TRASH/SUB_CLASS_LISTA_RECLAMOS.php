<?php
class SUB_CLASS_LISTA_RECLAMOS
{
    private $vlc_codigo_html;
    private $vlc_codigo_html_reclamo;
    private $vlc_codigo_html_reclamo_contenido;
    private $vlc_cod_html_separacion;
    private $vlc_cantidad_separacion;
    private $vlc_filtro_consulta;
    
    
    function __construct ()
    {
    
		$this->vlc_codigo_html					="";
		$this->vlc_filtro_consulta				="";    
        $this->vlc_codigo_html_reclamo			= FN_LEER_TPL('tpl/tpl-lista-reclamos.html');
        $this->vlc_codigo_html_reclamo_contenido= FN_LEER_TPL('tpl/tpl-lista-reclamos-contenido.html');                
        $this->vlc_cantidad_separacion			= 20;                        
    }
    function MTD_FILTRAR_HOLDING($vp_holding)
    {
    	$this->vlc_filtro_consulta.= " AND c.nombre_holding='$vp_holding' ";    	
    }    
   
    
    function MTD_ENSAMBLA_RECLAMOS()
    {
    	$vlf_fila				=0;
    	$vlf_codigo_html_filas_cargadas="";
    	$vlf_arreglo_reclamos 	= array();
    	$vlf_arreglo_reclamos 	= $this->MTD_DB_LISTA_RECLAMOS();    	
    	$vlf_cantidad_filas 	= sizeof($vlf_arreglo_reclamos);
    	
    
    	
    	while ($vlf_fila < $vlf_cantidad_filas)
    	{
    		$vlf_codigo_html_fila 	= $this->vlc_codigo_html_reclamo_contenido;
	    	 $vlf_id_reclamo				=$vlf_arreglo_reclamos[$vlf_fila][0];
			 $vlf_id_cliente				=$vlf_arreglo_reclamos[$vlf_fila][1];
			 $vlf_fecha_reclamo				=$vlf_arreglo_reclamos[$vlf_fila][2];
			 $vlf_llamo						=$vlf_arreglo_reclamos[$vlf_fila][3];
			 $vlf_staff						=$vlf_arreglo_reclamos[$vlf_fila][4];
			 $vlf_tipo_requerimiento		=$vlf_arreglo_reclamos[$vlf_fila][5];
			 $vlf_resumen_requerimiento		=$vlf_arreglo_reclamos[$vlf_fila][6];
			 $vlf_nombre_producto			=$vlf_arreglo_reclamos[$vlf_fila][7];
			 $vlf_nombre_modulo				=$vlf_arreglo_reclamos[$vlf_fila][8];
			 $vlf_nombre_programa			=$vlf_arreglo_reclamos[$vlf_fila][9];
			 $vlf_tipo_contrato				=$vlf_arreglo_reclamos[$vlf_fila][10];
			 $vlf_status_modulo				=$vlf_arreglo_reclamos[$vlf_fila][11];
			 $vlf_tipo_programa				=$vlf_arreglo_reclamos[$vlf_fila][12];
			 $vlf_estado_comercial			=$vlf_arreglo_reclamos[$vlf_fila][13];
			 $vlf_estado_programa			=$vlf_arreglo_reclamos[$vlf_fila][14];
			 $vlf_nombre_holding			=$vlf_arreglo_reclamos[$vlf_fila][15];
			 $vlf_nombre_empresa			=$vlf_arreglo_reclamos[$vlf_fila][16];
			 $vlf_nombre_sucursal			=$vlf_arreglo_reclamos[$vlf_fila][17];
			 $vlf_nombre_cliente			=$vlf_arreglo_reclamos[$vlf_fila][18];
			 $vlf_apellido_cliente			=$vlf_arreglo_reclamos[$vlf_fila][19];
			 $vlf_nro_contrato				=$vlf_arreglo_reclamos[$vlf_fila][20];
			 $vlf_tipo_contrato				=$vlf_arreglo_reclamos[$vlf_fila][21];
			 $vlf_direccion					=$vlf_arreglo_reclamos[$vlf_fila][22];
			 $vlf_ciudad					=$vlf_arreglo_reclamos[$vlf_fila][23];
			 $vlf_zonatecnica				=$vlf_arreglo_reclamos[$vlf_fila][24];
			 $vlf_zonacomercial				=$vlf_arreglo_reclamos[$vlf_fila][25];
			 $vlf_zonageografica			=$vlf_arreglo_reclamos[$vlf_fila][26];
			 $vlf_telefono					=$vlf_arreglo_reclamos[$vlf_fila][27];
			 $vlf_fax						=$vlf_arreglo_reclamos[$vlf_fila][28];
			 $vlf_email						=$vlf_arreglo_reclamos[$vlf_fila][29];
			 $vlf_project_manager			=$vlf_arreglo_reclamos[$vlf_fila][30];
			 $vlf_tiempo_viaje				=$vlf_arreglo_reclamos[$vlf_fila][31];
			 $vlf_rubro						=$vlf_arreglo_reclamos[$vlf_fila][32];
			 $vlf_website					=$vlf_arreglo_reclamos[$vlf_fila][33];
			 $vlf_status					=$vlf_arreglo_reclamos[$vlf_fila][34];
			 $vlf_prioridad					=$vlf_arreglo_reclamos[$vlf_fila][35];
			 $vlf_delay = '999';
			 $vlf_resumen_reclamo_cerrado="n/n";
			 $vlf_tipo_reclamo_cerrado="n/n";
			 $vlf_dias_abierto="999";
			 //APLICACION DE LOS DATOS EN EL TEMPLATE
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{id-reclamo}"	,$vlf_id_reclamo	,$vlf_codigo_html_fila); 
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{fecha-reclamo}"	,substr($vlf_fecha_reclamo,0,10),$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{llamo-reclamo}"	,$vlf_llamo 		,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{delay}"	,$vlf_delay 		,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{telefono-cliente}",$vlf_telefono	,$vlf_codigo_html_fila);
 			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{direccion-cliente}",$vlf_direccion	,$vlf_codigo_html_fila);
 			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{ciudad-cliente}",$vlf_ciudad	,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{tiempo-viaje-cliente}",$vlf_tiempo_viaje,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{email-cliente}"	,$vlf_email 		,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{web-cliente}"	,$vlf_website 		,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{zona-tecnica-cliente}",$vlf_zonatecnica ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{zona-comercial-cliente}",$vlf_zonacomercial ,$vlf_codigo_html_fila);
			 
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-producto}",$vlf_nombre_producto ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-modulo}",$vlf_nombre_modulo ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-programa}",$vlf_nombre_programa ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{resumen-reclamo-abierto}",$vlf_resumen_requerimiento ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{tipo-reclamo-abierto}",$vlf_tipo_requerimiento ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{resumen-reclamo-cerrado}",$vlf_resumen_reclamo_cerrado ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{tipo-reclamo-cerrado}",$vlf_tipo_reclamo_cerrado ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{status-reclamo}",$vlf_status ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{prioridad-reclamo}",$vlf_prioridad ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{dias-abierto}",$vlf_dias_abierto ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{staff-asignado}",$vlf_staff ,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-holding}",$vlf_nombre_holding,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-empresa}",$vlf_nombre_empresa,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{nombre-sucursal}",$vlf_nombre_sucursal,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{estado-comercial}",$vlf_estado_comercial,$vlf_codigo_html_fila);
			 $vlf_codigo_html_fila= FN_REEMPLAZAR("{estado-programa}",$vlf_estado_programa,$vlf_codigo_html_fila);
			 $vlf_codigo_html_filas_cargadas.=$vlf_codigo_html_fila;        	    	
			 $vlf_fila++;			 
    	}
    	//ASIGNACION DEL CODIGO HTML A LAS VARIABLES DE LA CLASE
   		$this->vlc_codigo_html_reclamo = FN_REEMPLAZAR("{lista-reclamos-contenido}",$vlf_codigo_html_filas_cargadas,$this->vlc_codigo_html_reclamo);
   		$this->vlc_codigo_html  =$this->vlc_codigo_html_reclamo.$this->vlc_cod_html_separacion; 			 
    }
    

    function MTD_DB_LISTA_RECLAMOS()
    {
    	include_once 'includes/FN_DB_SEPARAR_PAGINAS.php';
    	$vlf_pagina=1;
    	$vlf_link_reenvio="";
    	
    	if (isset($_GET['pagina']))
    	{
    		$vlf_pagina = $_GET['pagina'];
    	}
    	$start_from = ($vlf_pagina-1) * $this->vlc_cantidad_separacion;
    	 
    	$vlf_sql_limite=" LIMIT $start_from,". $this->vlc_cantidad_separacion; 
        $vlf_sql="
    	SELECT 
		  r.id_reclamo,
		  r.id_cliente,
		  r.fecha_reclamo,
		  r.llamo,
		  r.staff,
		  r.tipo_requerimiento,
		  r.resumen_requerimiento,
		  r.nombre_producto,
		  r.nombre_modulo,
		  r.nombre_programa,
		  r.tipo_contrato,
		  r.status_modulo,
		  r.tipo_programa,
		  r.estado_comercial,
		  r.estado_programa,
		  c.nombre_holding,
		  c.nombre_empresa,
		  c.nombre_sucursal,
		  c.nombre_cliente,
		  c.apellido_cliente,
		  c.nro_contrato,
		  c.tipo_contrato as tipo_contrato_cliente,
		  c.direccion,
		  c.ciudad,
		  c.zonatecnica,
		  c.zonacomercial,
		  c.zonageografica,
		  c.telefono,
		  c.fax,
		  c.email,
		  c.project_manager,
		  c.tiempo_viaje,
		  c.rubro,
		  c.website,
		  r.status,
		  r.prioridad		  
		FROM
		  reclamos as r
		  INNER JOIN clientes as c ON (r.id_cliente = c.id_cliente)
		WHERE 
		status = 'abierto'
    	";
        
		$vlf_link_reenvio='index.php?seccion=historial-solicitudes';
		//ASIGNACION DEL FILTRO             
        $vlf_sql.= " ".$this->vlc_filtro_consulta;
        //EJECUCION DEL QUERY    
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql.$vlf_sql_limite, 36, $vlg_conexion);
        //echo "<br>SQL QUE CORRIO:".$vlf_sql.$vlf_sql_limite."<hr>";
        //APLICACION DEL CODIGO HTML DE SEPARACION
        $this->vlc_cod_html_separacion=FN_DB_SEPARAR_PAGINAS($vlf_sql,$vlf_pagina,$vlf_link_reenvio,$this->vlc_cantidad_separacion);
        return $vlf_arreglo_datos;    	   
    }
 	function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
}
?>