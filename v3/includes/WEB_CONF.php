<?php
$vg_conf_busqueda_inteligente= true;
$vg_conf_busqueda_inteligente_host= "billing";
$vg_conf_busqueda_inteligente_port= "1235";
$vg_conf_dominio= "http://backtones.tigo.com.bo";
$vg_conf_path_tonos= "../tonos";
$vg_conf_simular_tonos= false;
$vg_conf_simbolo_moneda ='Bs.';
$vg_conf_async_server   ='billing';
$vg_conf_async_port     =3334;
$vg_conf_async_from     =2;
$vg_conf_logger_server  ='logger';
$vg_conf_logger_port    =3337;
$vg_conf_logger_from    ='WEB_V3';
$vg_conf_debug_level    =5;
$vg_conf_cantidad_prefijo = 2;// para PY 4 (098x) para BO 2 (74,75,77)
$vg_conf_prefijo        = array("74","75","76","77","78","69");
$vg_conf_cantidad_digitos=8; // para PY 10 | para BO 8
$vg_conf_logger_net     = false;
$vg_conf_min_short_number = 1120;
$vg_conf_max_short_number = 1129;
$vg_conf_filtro_web = "and t.categoria <> 207 and t.interfases &1 ";
$vg_conf_email_administrador=  "bustillosh@tigo.net.bo,silvam@tigo.net.bo,villarroeli@tigo.net.bo,contact.center@tigo.net.bo";
$vg_smpp_host = 'smpp';
$vg_smpp_port = '20053';
$vg_smpp_from = '1111';
$vg_smpp_port_wap = '9070';
$vg_db_host = 'mysql';
$vg_db_name = 'crbt';
$vg_db_user = 'root';
$vg_db_pass= '';
$vg_configuracion_regional= 'BO'; //BO | PY
$vg_feature_giftatune=true;
?>
