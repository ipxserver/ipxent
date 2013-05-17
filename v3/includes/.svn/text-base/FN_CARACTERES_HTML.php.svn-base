<?php
function FN_CARACTERES_HTML($texto)
{
      $texto = htmlentities($texto, ENT_NOQUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades
      $texto = htmlspecialchars_decode($texto, ENT_NOQUOTES); // Dejar <, & y > como estaban
      return $texto;
}
if ( !function_exists('htmlspecialchars_decode') )
  {
      function htmlspecialchars_decode($text)
      {
          return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
      }
  }
?>