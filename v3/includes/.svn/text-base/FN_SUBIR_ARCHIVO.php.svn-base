<?php
function FN_SUBIR_ARCHIVO($vp_ruta_archivo,$vp_nombre_campo,$vp_id_usuario,$nombre_archivo='')
{
	/*
// ----------------------------------------------------------------------------------------

	$rutaArchivos;	
	
	$resultado;
	
// ----------------------------------------------------------------------------------------
	
	
	$rutaArchivos= "$vp_ruta_archivo";

	@mkdir( $rutaArchivos );
	
	
	echo "<BR>CHECK UP<BR>";
	if (is_uploaded_file($vp_nombre_archivo)) 
	{ 
			
		if( copy($vp_nombre_archivo, $rutaArchivos."/".$vp_nombre_archivo ) )
			$resultado= true;
			
	}
*/
	$resultado= false;
		$target_path =$vp_ruta_archivo;
		if ($nombre_archivo)
		{
			
			$target_path = $target_path.$nombre_archivo;
			
		}
		else 
		{
			$vlf_nombre_archivo=basename( $_FILES[$vp_nombre_campo]['name']);
			$target_path = $target_path ."_".$vp_id_usuario."_".$vlf_nombre_archivo; 
		}
	

		if(move_uploaded_file($_FILES[$vp_nombre_campo]['tmp_name'], $target_path)) 
		{
    		//echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded";
    		$resultado=true;
		} 
		else
		{
    		$resultado=false;
		}
	
	return( $resultado );
}
?>