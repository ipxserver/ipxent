<?php
function FN_DB_CONEXION()
{
   global $vg_db_host,$vg_db_user,$vg_db_pass,$vg_db_name ;
   //FN_NET_LOGGER("FN_DB_CONEXION > host:$vg_db_host user: $vg_db_user db:$vg_db_name ");
   if (!($vlf_conexion=mysql_connect($vg_db_host,$vg_db_user,$vg_db_pass)))
   {
      //echo "Error conectando a la base de datos.";
      FN_NET_LOGGER("FN_DB_CONEXION > host:$vg_db_host user: $vg_db_user > fallo el intento de conexion");
      exit();
   }
   if (!mysql_select_db($vg_db_name ,$vlf_conexion))
   {
      //echo "Error seleccionando la base de datos.";
      FN_NET_LOGGER("FN_DB_CONEXION > host:$vg_db_host user: $vg_db_user > fallo el intento de conexion");
      exit();
   }
   return $vlf_conexion; 	
}
?>