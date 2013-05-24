<?php
function FN_REEMPLAZAR($vp_busqueda,$vp_reemplazo,$vp_origen)
{
    $vlf_resultado = str_replace($vp_busqueda,$vp_reemplazo,$vp_origen);
    return $vlf_resultado; 
}
?>