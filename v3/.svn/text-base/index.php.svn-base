<?php
session_start();
//FUNCIONES GLOBALES
include_once ('includes/WEB_CONF.php');
include_once ('includes/FN_HTML_ARMAR_GRILLA.php');
include_once ('includes/FN_HTML_ARMAR_LISTA.php');
include_once ('includes/FN_HTML_EDITORCONTENIDO.php');
include_once ('includes/FN_CARACTERES_HTML.php');
include_once ('includes/FN_RECIBIR_VARIABLES.php');
include_once ('includes/FN_LEER_TPL.php');
include_once ('includes/FN_REEMPLAZAR.php');
include_once ('includes/FN_STRING_LIMITE.php');
include_once ('includes/FN_NET_SOCKET.php');
include_once ('includes/FN_NET_LOGGER.php');
include_once ('includes/FN_NET_SMS.php');
include_once ('includes/FN_NET_ENVIAR_MAIL.php');
include_once ('includes/FN_COMPRIMIR_HTML.php');
include_once ('includes/FN_DB_CONEXION.php');
include_once ('includes/FN_DB_QUERY.php');
include_once('includes/FN_WEB_ESTRELLAS.php');
include ('class/CLASS_WEB_INDICE.php');

/*
=====================================
CONEXION CON BASE DE DATOS
=====================================
*/
$vg_db_conexion = FN_DB_CONEXION ();

$obj_indice = new CLASS_WEB_INDICE ( );
$vlp_codigo_html = $obj_indice->MTD_RETORNAR_CODIGO_HTML ();
echo $vlp_codigo_html;

?>