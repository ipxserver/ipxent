<?php
FUNCTION FN_LEER_TPL ($vp_tpl)
{
    //$ourFileName = "testFile.txt";
    $vlf_datos_tpl = fopen($vp_tpl, 'r') or die("No se puede leer el tpl: $vp_tpl");
    $vlf_codigo_html=""; 
    while (! feof($vlf_datos_tpl))
    {
        $vlf_codigo_html= $vlf_codigo_html.fgets($vlf_datos_tpl);           
    }
    fclose($vlf_datos_tpl);    
    return $vlf_codigo_html;
}
?>