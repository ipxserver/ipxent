<?php
class CLASS_WEB_LOGIN_USUARIO
{
    private $vlc_codigo_html;
    function __construct($vp_session_usuario)
    {
        $this->vlc_codigo_html="";
        $vl_msisdn="";
        if ($vp_session_usuario == true)
        {
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-login-usuario-loggedin.html');
            $vl_msisdn=$_SESSION['msisdn'];
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-msisdn}', $vl_msisdn,  $this->vlc_codigo_html);
        }
        else
        {
            global $vg_conf_cantidad_digitos;
            $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-login-usuario.html');
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-cantidad-caracter-msisdn}', $vg_conf_cantidad_digitos,  $this->vlc_codigo_html);
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-msisdn}', $vl_msisdn,  $this->vlc_codigo_html);
               
        }

    }
    

    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>