<?php
function FN_HTML_EDITORCONTENIDO($vp_nombre_campo, $vp_texto_inicial, $vp_nombre_formulario)
{
	$html = "<script language='JavaScript' type='text/javascript'>
							
							function submitForm() {
								//make sure hidden and iframe values are in sync before submitting form
								//to sync only 1 rte, use updateRTE(rte)
								//to sync all rtes, use updateRTEs
								updateRTE('$vp_nombre_campo');
								
								//updateRTEs();
								//alert('$vp_nombre_campo = ' + document.$vp_nombre_formulario.$vp_nombre_campo.value);
								//alert('rte2 = ' + document.RTEDemo.rte2.value);
								//alert('rte3 = ' + document.RTEDemo.rte3.value);
								
								//change the following line to true to submit form
								return true;
							}
							
							
							initRTE('images/', 'css/', 'rte.css');							
							</script>
							<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
							
							<script language='JavaScript' type='text/javascript'>
							writeRichText('$vp_nombre_campo','$vp_texto_inicial',250, 200, true, false);
							</script>
							";
	return $html;
}
?>