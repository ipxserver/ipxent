<?php
class CLASS_WEB_INDICE
{
    private $vlc_codigo_html;
    
    function __construct ()
    {
        $this->vlc_codigo_html = "";
    
        $this->MTD_INICIALIZAR_PAGINA();
    }
    function MTD_INICIALIZAR_PAGINA ()
    {        
        //FUNCIONES GENERALES        
        include_once('class/CLASS_SESSION.php');
        include_once('class/CLASS_WEB_SELECCION_GENERO.php');
        include_once('class/CLASS_WEB_BUSQUEDA.php');
        include_once('class/CLASS_WEB_LOSTONOSMAS.php');
        include_once('class/CLASS_WEB_BANNER_PRINCIPAL.php');
        include_once('class/CLASS_WEB_ULTIMOS_INGRESOS.php');
        include_once('class/CLASS_WEB_RECOMENDADOS.php');
        include_once('class/CLASS_WEB_LOGIN_USUARIO.php');
        include_once('class/CLASS_WEB_FAQ.php');
        include_once('class/CLASS_WEB_BACKTONES_ESCUCHADOS.php');
        include_once('class/CLASS_WEB_REPRODUCIR_BACKTONES.php');
        include_once ('class/CLASS_SESSION.php');
        

        
        //
        
        //-----------------------------------------
        // INICIALIZACION DE VARIABLES
        //-----------------------------------------
        $vl_cod_html_base = "";
        $vl_cod_html_seccion = "";
        $vl_seccion ="";
        $vl_header="";
        $vl_header_secundario="";
        $vl_titulo_header="Ringback Tone Entel";
        
        //**************************************************
        // OBJETOS COMUNES PARA EL SITIO WEB
        //**************************************************


        /*
        ======================================
        CONTROLA LA SESION DEL USUARIO
        ======================================
         */
        
        $obj_session = new CLASS_SESSION();
        $vlf_session_activada = $obj_session->MTD_START();
        //-----------------------------------------
        // LECTURA DEL TPL BASE
        //-----------------------------------------
        $vl_cod_html_base = FN_LEER_TPL('tpl/tpl-base-web.html');        
        //-----------------------------------------
        // ASIGNACION DE LA SECCION: SELECCION DE GENERO
        //-----------------------------------------
        $obj_web_seleccion_genero = new CLASS_WEB_SELECCION_GENERO( );
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-seleccion-genero}", $obj_web_seleccion_genero->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);


        //-----------------------------------------
        // ASIGNACION DE LA SECCION: PANEL DE BUSQUEDA
        //-----------------------------------------
        $obj_web_busqueda = new CLASS_WEB_BUSQUEDA( );
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-panel-busqueda}", $obj_web_busqueda->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);

        //-----------------------------------------
        // ASIGNACION DE LA SECCION:  LOS TONOS MAS
        //-----------------------------------------
        $obj_web_lostonosmas = new CLASS_WEB_LOSTONOSMAS( );
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-lista-tonos-losmas}", $obj_web_lostonosmas->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);

        
        //--------------------------------------------
        // ASIGNACION DE LA SECCION: RECOMENDADOS
        //--------------------------------------------
        $obj_web_recomendados = new CLASS_WEB_RECOMENDADOS( );
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-recomendados}", $obj_web_recomendados->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);

        //--------------------------------------------
        // ASIGNACION DE LA SECCION:  LOGIN-USUARIOS
        //--------------------------------------------
        $obj_login_usuarios = new CLASS_WEB_LOGIN_USUARIO($vlf_session_activada);

        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-login-usuarios}", $obj_login_usuarios->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
        
        
        //--------------------------------------------
        // ASIGNACION DE LA SECCION:  FAQ
        //--------------------------------------------
        $obj_faq = new CLASS_WEB_FAQ();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-faq}",$obj_faq->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);

        //------------------------------------------------
        // ASIGNACION DE LA SECCION: BACKTONES ESCUCHADOS
        //------------------------------------------------
        $obj_backtones_escuchados = new CLASS_WEB_BACKTONES_ESCUCHADOS();
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-backtones-escuchados}",$obj_backtones_escuchados->MTD_RETORNAR_CODIGO_HTML(), $vl_cod_html_base);
       
        $vl_error=false;

        //-----------------------------------------
	// ASIGNACION DE LA SECCION: MODULO DE LA SECCION
        //-----------------------------------------
        if (isset($_GET['seccion']))
        {
            $vl_seccion = FN_RECIBIR_VARIABLES('seccion');
        
            switch ($vl_seccion)
            {
                //**************************************************
                //* SECCION : BUSQUEDA
                //**************************************************
                case "busqueda":
                     $vl_cod_html_seccion= $obj_web_busqueda->MTD_REALIZAR_BUSQUEDA();
                break;
                //**************************************************
                //* SECCION : BACKTONES
                //**************************************************
                case "backtones":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    if ((is_numeric(FN_RECIBIR_VARIABLES('backtone'))) && FN_RECIBIR_VARIABLES('backtone') >=1)
                    {
                        $obj_reproducir_backtones   = new CLASS_WEB_REPRODUCIR_BACKTONES();
                        $vl_cod_html_seccion        = $obj_reproducir_backtones->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header                 .= $obj_reproducir_backtones->MTD_REQUERIMIENTOS_ENCABEZADO();
                        $vl_titulo_header           = $obj_reproducir_backtones->MTD_RETORNAR_ENCABEZADO();
                        $vl_header_secundario       = $obj_reproducir_backtones->MTD_REQUERIMIENTOS_ENCABEZADOS_SECUNDARIOS();
                    }
                    else
                    {
                        FN_NET_LOGGER("WEB_INDICE > Error en la recepcion del id del backtone");
                        $vl_error=true;
                    }
                break;
                //**************************************************
                //* SECCION : REPRODUCIR BUSQUEDA DE BACKTONES
                //**************************************************
                case "playlist":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                        include_once('class/CLASS_WEB_REPRODUCIR_BUSQUEDA.php');
                        $obj_reproducir_busqueda    = new CLASS_WEB_REPRODUCIR_BUSQUEDA();
                        $vl_cod_html_seccion        = $obj_reproducir_busqueda->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header                 .= $obj_reproducir_busqueda->MTD_REQUERIMIENTOS_ENCABEZADO();
                        $vl_titulo_header           = $obj_reproducir_busqueda->MTD_RETORNAR_ENCABEZADO();
                        $vl_header_secundario       = $obj_reproducir_busqueda->MTD_REQUERIMIENTOS_ENCABEZADOS_SECUNDARIOS();
                break;
                //**************************************************
                //* SECCION : ADMINISTRAR_AMIGOS
                //**************************************************
                case "amigos":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    if ($vlf_session_activada ==  true)
                    {
                        include_once('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                        $obj_adm_amigos      = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                        $vl_cod_html_seccion = $obj_adm_amigos->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header          .= $obj_adm_amigos->MTD_REQUERIMIENTOS_ENCABEZADO();
                        
                    }
                    else
                    {
                        $vl_error=true;
                        //CONDICION DE ERROR INICIO SIN SESION
                    }
                break;
                //**************************************************
                //* SECCION : ADMINISTRAR_GRUPOS
                //**************************************************
                case "grupos":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    if ($vlf_session_activada ==  true)
                    {                        
                        include_once('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                        $obj_adm_grupos      = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                        $vl_cod_html_seccion = $obj_adm_grupos->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header          .= $obj_adm_grupos->MTD_REQUERIMIENTOS_ENCABEZADO();
                    }
                    else
                    {
                        $vl_error=true;
                        //CONDICION DE ERROR INICIO SIN SESION
                    }
                break;
                 //**************************************************
                //* SECCION : ADMINISTRAR MIS BACKTONES
                //**************************************************
                case "mis_backtones":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    if ($vlf_session_activada ==  true)
                    {
                        include_once('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                        $obj_adm_backtones      = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                        $vl_cod_html_seccion = $obj_adm_backtones->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header          .= $obj_adm_backtones->MTD_REQUERIMIENTOS_ENCABEZADO();
                    }
                    else
                    {
                        $vl_error=true;
                        //CONDICION DE ERROR INICIO SIN SESION
                    }
                break;
                //**************************************************
                //* SECCION : ADMINISTRAR MI PERFIL
                //**************************************************
                case "perfil_usuario":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    if ($vlf_session_activada ==  true)
                    {
                        include_once('class/CLASS_WEB_ADMINISTRAR_PERFIL.php');
                        $obj_adm_perfil      = new CLASS_WEB_ADMINISTRAR_PERFIL();
                        $vl_cod_html_seccion = $obj_adm_perfil->MTD_RETORNAR_CODIGO_HTML();
                        $vl_header          .= $obj_adm_perfil->MTD_REQUERIMIENTOS_ENCABEZADO();
                    }
                    else
                    {
                        $vl_error=true;
                        //CONDICION DE ERROR INICIO SIN SESION
                    }
                break;
                //**************************************************
                //* SECCION : FAQ
                //**************************************************
                case "faq":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    $vl_cod_html_seccion = $obj_faq->MTD_SECCION_FAQ();
                    //$vl_header          .= $obj_adm_perfil->MTD_REQUERIMIENTOS_ENCABEZADO();
                break;
                //**************************************************
                //* SECCION : SUGERENCIAS
                //**************************************************
                case "sugerencias":
                    //CLASS_WEB_REPRODUCIR_BACKTONES
                    include_once('class/CLASS_WEB_SUGERENCIAS.php');
                    $obj_sugerencias = new CLASS_WEB_SUGERENCIAS();
                    $vl_cod_html_seccion = $obj_sugerencias->MTD_RETORNAR_CODIGO_HTML();
                    //$vl_header          .= $obj_adm_perfil->MTD_REQUERIMIENTOS_ENCABEZADO();
                break;
                //**************************************************
                //* SECCION : PROMOCIONES
                //**************************************************
                case "promociones":
                    $vl_cod_html_seccion="{SECCION PROMOCIONES}";
                break;
            default:
                $vl_error =true;
                break;
            }
           
        }
        else
        {
            //**************************************************
            //* SECCION : HOME
            //**************************************************
            $vl_cod_html_seccion ="";

            //--------------------------------------------
            // ASIGNACION DE LA SECCION:  BANNER PRINCIPAL
            //--------------------------------------------
            $obj_banner_principal = new CLASS_WEB_BANNER_PRINCIPAL();
            $vl_cod_html_seccion = $obj_banner_principal->MTD_RETORNAR_CODIGO_HTML();

            //--------------------------------------------
            // ASIGNACION DE LA SECCION:  NUEVOS BACKTONES
            //--------------------------------------------
            $obj_ultimos_ingresos = new CLASS_WEB_ULTIMOS_INGRESOS();
            $vl_cod_html_seccion .= $obj_ultimos_ingresos->MTD_RETORNAR_CODIGO_HTML();
        }

        //CONDICION DE ERROR
        if ($vl_error ==true)
        {
            //**************************************************
            //* SECCION : HOME
            //**************************************************
            $vl_cod_html_seccion ="";

            //--------------------------------------------
            // ASIGNACION DE LA SECCION:  BANNER PRINCIPAL
            //--------------------------------------------
            $obj_banner_principal = new CLASS_WEB_BANNER_PRINCIPAL();
            $vl_cod_html_seccion = $obj_banner_principal->MTD_RETORNAR_CODIGO_HTML();

            //--------------------------------------------
            // ASIGNACION DE LA SECCION:  NUEVOS BACKTONES
            //--------------------------------------------
            $obj_ultimos_ingresos = new CLASS_WEB_ULTIMOS_INGRESOS();
            $vl_cod_html_seccion .= $obj_ultimos_ingresos->MTD_RETORNAR_CODIGO_HTML();

        }
        
        if ($vlf_session_activada == true)
        {
            //LOGIN
        }
        else 
        {           

            //NO LOGIN         
        }
        //

        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-titulo-header}",$vl_titulo_header, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-header}",$vl_header, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-header-secundario}",$vl_header_secundario, $vl_cod_html_base);
        $vl_cod_html_base = FN_REEMPLAZAR("{tpl-contenedor-principal}",$vl_cod_html_seccion, $vl_cod_html_base);

     
        //--------------------------------------------
        // REEMPLAZAZ EL CODIGO CENTRAL CON LA SECCION
        //--------------------------------------------
        //
        //
        //$vl_cod_html_base = FN_REEMPLAZAR('{tpl-contenido-intranet}', $vl_cod_html_seccion, $vl_cod_html_base);
        //-------------------------------------------
        // IMPRIME EL CODIGO HTML
        //-------------------------------------------
        $this->vlc_codigo_html = $vl_cod_html_base;
    }
    function MTD_RETORNAR_CODIGO_HTML ()
    {
        $line  = $this->vlc_codigo_html;     
        //$line = ereg_replace("\t", "", $line);
        //$line = ereg_replace("[\n\r]", "", $line);
        //$line = ereg_replace("\t\t+", "", $line);
        //$this->vlc_codigo_html = FN_REEMPLAZAR("\n","", $this->vlc_codigo_html);
        //$this->vlc_codigo_html = FN_REEMPLAZAR("\t\t+","", $this->vlc_codigo_html);
        //$line = FN_COMPRIMIR_HTML($this->vlc_codigo_html);
        $line = $this->vlc_codigo_html;
        //$line = minimizeHTML($this->vlc_codigo_html);        
//        ob_start( 'ob_gzhandler' );
        $line = utf8_encode($line);
        return $line;
        //return $this->vlc_codigo_html;
    }
    
}
?>
