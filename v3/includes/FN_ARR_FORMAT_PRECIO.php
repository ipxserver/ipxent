<?php
function FN_ARR_FORMAT_PRECIO($vp_arreglo,$vp_columna)
{
	$cantidad_registros= sizeof($vp_arreglo);
	$contador=0;
	while ($contador < $cantidad_registros)
	{
		$valor_sinformato = $vp_arreglo[$contador][$vp_columna];
		$valor_formateado = number_format($valor_sinformato,0, '', '.');
		//echo "DATO VIEJO [".$valor_sinformato."] -> ".$valor_formateado ;
		$vp_arreglo[$contador][$vp_columna] = $valor_formateado ;
		$contador++;		
	}
	return $vp_arreglo;
}
?>