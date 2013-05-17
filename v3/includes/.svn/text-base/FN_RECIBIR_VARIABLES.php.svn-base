<?php
function FN_RECIBIR_VARIABLES($vp_nombre_variable,$vp_metodo = 'REQUEST',$vp_limpieza = true)
{
	$vlf_contenido_variable = "";
	if ($vp_metodo == 'POST')
	{
		if (isset($_POST[$vp_nombre_variable]))
		{
			$vlf_contenido_variable =$_POST[$vp_nombre_variable]; 
		}
	}
	elseif ($vp_metodo == 'GET')
	{
		if (isset($_GET[$vp_nombre_variable]))
		{
			$vlf_contenido_variable =$_GET[$vp_nombre_variable]; 
		}
	}
	elseif ($vp_metodo == 'REQUEST')
	{
		if (isset($_REQUEST[$vp_nombre_variable]))
		{
			$vlf_contenido_variable =$_REQUEST[$vp_nombre_variable]; 
		}
	}	
	
	if ($vp_limpieza == true)
	{
		$vlf_contenido_variable2 = mysql_real_escape_string ( $vlf_contenido_variable );

	}	
	//echo "<DIV align='left'>$vp_nombre_variable -> [$vlf_contenido_variable2]</div>";
	return $vlf_contenido_variable2 ;
	
}
?>