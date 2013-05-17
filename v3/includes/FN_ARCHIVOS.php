<?php
/**
 * archivo: FN_ARCHIVO.php
 *
 * Breve descripción: 	
 * 						
 * 					  
 * 
 * Descripción completa:
 * 
 * 
 * LICENCIA: adquirida bajo pago.
 *
 * Fecha de creaci�n: 01/12/2008 8:13
 *  
 * @copyright  2008- Tera S.R.L.
 * @version    0
 */

function esAceptado( $extensionArchivo ){
// ------------------------------------------------------------------------------------------

	$indice;
	
	$longitud;
	
	$aceptado;	
	
	$formatosAceptados;
	
// ----------------------------------------------------------------------------------------

	$formatosAceptados= array( "jpg", "gif", "png" );
		

	$longitud= sizeof( $formatosAceptados );
	
	$aceptado= FALSE;
	
	for( $indice=0; $indice < $longitud; $indice++ ){
		if( $formatosAceptados[ $indice ] == $extensionArchivo ){
			$aceptado= TRUE;
			break;
		}
		
	}
	
	return( $aceptado );
	
}

function asignarNombreArchivo(){
// ----------------------------------------------------------------------------------------
	
	$vector;
	
	$nombreArchivo;
	
// ----------------------------------------------------------------------------------------	
	
	
	$vector= explode( ".", $_FILES["userfile"]["name"] );
	
	
	$nombreArchivo= "NO_PERMITIDO";
		
	if( esAceptado( $vector[1] ) )
		$nombreArchivo= $vector[0].date( "Ymd_hms" ).".".$vector[1];
		
	
	return( $nombreArchivo );
}
?>