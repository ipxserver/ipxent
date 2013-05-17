<?
function FN_NET_ENVIAR_MAIL($vp_asunto,$vp_contenido)
{
    global $vg_conf_dominio,$vg_conf_email_administrador;
    
	$de='webrbt@'.$vg_conf_dominio;
	$cabeceras="From: ".$de.">\n";
	$tema=$vp_asunto;

	$cabeceras.="MIME-version: 1.0\n";
	$cabeceras.="Content-type: multipart/mixed; ";
	$cabeceras.="boundary=\"Message-Boundary\"\n"; 
	$cabeceras.="Content-transfer-encoding: 7BIT\n";
	
	$body_top = "--Message-Boundary\n";
	$body_top .= "Content-type: text/html; charset=US-ASCII\n";
	$body_top .= "Content-transfer-encoding: 7BIT\n";
	$body_top .= "Content-description: Mail message body\n\n";
	
	$mensaje=$body_top.$vp_contenido;
	FN_NET_LOGGER("Envio de Email a:>$vg_conf_email_administrador \nAsunto: $tema \n Contenido:$mensaje ",5);
	mail($vg_conf_email_administrador, $tema, $mensaje, $cabeceras);
}
?>