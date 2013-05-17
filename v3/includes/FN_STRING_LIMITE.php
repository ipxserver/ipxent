<?php
//LIMITA LA CANTIDAD DE CARACTERES AGREGANDO UN STRING ..
function FN_STRING_LIMITE($vp_caracter,$vp_limite, $vp_caracter_limitante="..")
{
  $cantidad = 0;
  $cantidad = strlen($vp_caracter);
  if($cantidad >= $vp_limite)
  {
    $vp_caracter= substr($vp_caracter,0,$vp_limite).$vp_caracter_limitante;
  }
  return $vp_caracter;
}

?>
