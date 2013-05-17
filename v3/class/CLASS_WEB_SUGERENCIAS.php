<?php
class CLASS_WEB_SUGERENCIAS
{
    private $vlc_codigo_html;
    private $vlc_msisdn;
    private $vlc_mensaje;
    private $vlc_notificacion;


    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_codigo_html= FN_LEER_TPL("tpl/tpl-sugerencias.html");
        $this->vlc_msisdn="";
        $this->vlc_mensaje="";
        $this->vlc_notificacion="";
                
        $status = "";
        if ($_POST["action"] == "sugerir")
        {
            $this->MTD_ENVIAR_SUGERENCIA();
        }

    }

    function MTD_ENVIAR_SUGERENCIA()
    {
        global $vg_db_conexion;
        $vl_sugerencia="";
        $vl_sugerencia= FN_RECIBIR_VARIABLES("tb_sugerencia","POST");
        FN_NET_LOGGER("Enviando sugerencia :$vl_sugerencia");
        $vl_msisdn="";
        $vl_msisdn= $_SESSION["msisdn"];

        $this->vlc_notificacion="$('#tb_sugerencia').ShowBubblePopup();";
        if ($vl_msisdn)
        {
            FN_NET_ENVIAR_MAIL("Sugerencia desde la WEB ($vl_msisdn) ",$vl_sugerencia);
        }
        else
        {
            FN_NET_ENVIAR_MAIL("Sugerencia desde la WEB ",$vl_sugerencia);
        }

        //NOTIFICACION DE MENSAJE

        $vlf_sql="SELECT nombre, apellido, nickname from usuarios_datos_personales where msisdn='".$_SESSION['msisdn']."'";
        $vlf_datos = FN_RUN_QUERY($vlf_sql,2,$vg_db_conexion);
        $vlf_mensaje="";
        $vlf_nombre="";
        $vlf_apellido="";
        if ($vlf_datos)
        {
            $vlf_nombre     = $vlf_datos[0][0];
            $vlf_apellido   = $vlf_datos[0][1];
        }

        if ($vlf_nombre)
        {
            $vlf_mensaje="Muchas gracias $vlf_nombre $vlf_apellido por tu sugerencia, la estamos procesando";
            FN_NET_SMS($vlf_mensaje, $_SESSION['msisdn']);
        }
        else
        {
            $vlf_mensaje="Muchas gracias ".$_SESSION['msisdn']." por tu sugerencia, la estamos procesando";
            FN_NET_SMS($vlf_mensaje, $_SESSION['msisdn']);
        }
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tpl-notificacion}", $this->vlc_notificacion, $this->vlc_codigo_html);
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>