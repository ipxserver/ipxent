<?php
function FN_RUN_QUERY($vp_query, $vp_cant_columnas)
	{
		//========================================
		// CONEXION BASE DE DATOS
		//========================================s
		
		//$vlf_conexion = pg_connect ( $this->vlc_db_string_conexion );
		//$vp_datos = new array();

                $vl_tiempo_inicial= microtime();
                
		$resultado_consulta = mysql_query( $vp_query);
		
                $vl_tiempo_final= microtime();
                $vl_resultado= ($vl_tiempo_final- $vl_tiempo_inicial);               
                if($vl_resultado >= 2)
                {
                    FN_NET_LOGGER("FN_RUN_QUERY >SLOW QUERY : Duracion $vl_resultado  seg. \n $vp_query ");
                }

                
		if (!$resultado_consulta)
		{
			 FN_NET_LOGGER("FN_RUN_QUERY > ERROR SQL:$vp_query -> ".mysql_error());
			//echo "[0] Error en la consulta sql: ".$vp_query;
			exit ();
		}
                if (mysql_error())
		{
			FN_NET_LOGGER("FN_RUN_QUERY > ERROR SQL:$vp_query -> ".mysql_error());
			$estado_consulta = false;
		}
		
		$cantidad_filas = mysql_num_rows ( $resultado_consulta );

		if ($cantidad_filas == 0)
		{
			//echo "No se encontro ningun registro \n";
			//exit ();
			return 0;
		}
		else
		{
			//ASIGNACION DE DATOS
			
			for($fila = 0; $fila < $cantidad_filas; $fila ++)
			{
				
				for($columna = 0; $columna < $vp_cant_columnas; $columna ++)
				{
					
					$vp_datos[$fila][$columna] = mysql_result ( $resultado_consulta, $fila, $columna );
									
				}
			}
		}
		mysql_free_result ( $resultado_consulta );		
		return $vp_datos;
	}
	function FN_RUN_NONQUERY($vp_query)
	{

		$estado_consulta = false;
	
		$resultado_consulta = mysql_query ( $vp_query);
		
		if (mysql_error())
		{
			FN_NET_LOGGER("FN_RUN_NONQUERY > ERROR en la consulta sql:$vp_query -> ".mysql_error());
			$estado_consulta = false;			
		}
		else
		{
			$estado_consulta = true;		   
		}
	    
		
		return $estado_consulta;
	}
	function FN_DB_ULTIMO_REGISTRO()
	{
		$valor = mysql_insert_id();
		return $valor;
	}
?>