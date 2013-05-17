<?php
function FN_HTML_ARMAR_LISTA($vp_arreglo_cursos, $vp_titulo_lista, $vp_nombre_lista, $vp_valor_seleccionado, $vp_refrescar_automaticamente = false, $eventos = "OnChange='cambio_lista()")
{
	// ------------------------------------------------------------------------
	

	$indice;
	$longitud;
	
	$inicioHtml;
	$finHtml;
	
	// ------------------------------------------------------------------------
	

	// RESUMEN:
	// =======
	// Inicializar el inicio y fin del HTML ( en este caso para la etiqueta <SELECT> );
	// Adherir las etiquetas <OPTION> al inicio del HTML
	// Armar el resultado final
	

	// DESARROLLO:
	// ==========
	

	// Inicializando el inicio y fin del HTML
	if ($vp_refrescar_automaticamente == true)
	{
		$inicioHtml = "	<fieldset class='sin_bordes'>
		           <legend class='etiqueta_legend etiqueta'>$vp_titulo_lista</legend>
		           <select id='$vp_nombre_lista' name='$vp_nombre_lista' class='elemento_formulario'  $eventos'>
		           <option value='0'>Todos</option>
		           </fieldset>";
	}
	else
	{
		$inicioHtml = "	$vp_titulo_lista &nbsp; <select id='$vp_nombre_lista' name='$vp_nombre_lista' class='elemento_formulario' style='width:150px'>";
	}
	
	// Adhiriendo las etiquetas <OPTION>
	$longitud = sizeof ( $vp_arreglo_cursos );
	
	for($indice = 0; $indice < $longitud; $indice ++)
	{
		$vlf_valor = $vp_arreglo_cursos[$indice][0];
		$vlf_titulo = $vp_arreglo_cursos[$indice][1];
		
		if ($vlf_valor == $vp_valor_seleccionado)
		{
			$seleccion = "selected";
		}
		else
		{
			$seleccion = "";
		}
		$inicioHtml .= "<option value='" . $vlf_valor . "' " . $seleccion . " >" . $vlf_titulo . "</option>";
	
	}
	$inicioHtml .="</select></fieldset>";
	// Armando el resultado final 		
	$html = $inicioHtml . $finHtml;
	
	return ($html);
}
function FN_HTML_ARMAR_LISTA_ST($vp_arreglo_cursos, $vp_titulo_lista, $vp_nombre_lista, $vp_valor_seleccionado, $vp_refrescar_automaticamente = false, $eventos = "OnChange='cambio_lista()",$vp_estilo="medium")
{
	// ------------------------------------------------------------------------
	

	$indice;
	$longitud;
	
	$inicioHtml;
	$finHtml;
	
	// ------------------------------------------------------------------------
	

	// RESUMEN:
	// =======
	// Inicializar el inicio y fin del HTML ( en este caso para la etiqueta <SELECT> );
	// Adherir las etiquetas <OPTION> al inicio del HTML
	// Armar el resultado final
	

	// DESARROLLO:
	// ==========
	

	// Inicializando el inicio y fin del HTML
	if ($vp_refrescar_automaticamente == true)
	{
		$inicioHtml = " <select id='$vp_nombre_lista' name='$vp_nombre_lista' class='element select $vp_estilo'  $eventos'>
		           <option value='0'>Todos</option>";
	}
	else
	{
		$inicioHtml = " <select id='$vp_nombre_lista' name='$vp_nombre_lista' class='element select $vp_estilo' >";
	}
	
	// Adhiriendo las etiquetas <OPTION>
	$longitud = sizeof ( $vp_arreglo_cursos );
	
	for($indice = 0; $indice < $longitud; $indice ++)
	{
		$vlf_valor = $vp_arreglo_cursos[$indice][0];
		$vlf_titulo = $vp_arreglo_cursos[$indice][1];
		
		if ($vlf_valor == $vp_valor_seleccionado)
		{
			$seleccion = "selected";
		}
		else
		{
			$seleccion = "";
		}
		$inicioHtml .= "<option value='" . $vlf_valor . "' " . $seleccion . " >" . $vlf_titulo . "</option>";
	
	}
	$inicioHtml .="</select></fieldset>";
	// Armando el resultado final 		
	$html = $inicioHtml . $finHtml;
	
	return ($html);
}
?>