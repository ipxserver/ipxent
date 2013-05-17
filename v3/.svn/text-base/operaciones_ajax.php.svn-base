<?php
session_start();
//FUNCIONES GLOBALES
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
include_once ('includes/FN_COMPRIMIR_HTML.php');
include_once ('includes/FN_DB_CONEXION.php');
include_once ('includes/FN_DB_QUERY.php');
include_once ('includes/FN_NET_ASYNC.php');
include_once ('includes/FN_BILLING_CALCULO_FECHAS.php');

/*
=====================================
CONEXION CON BASE DE DATOS
=====================================
*/
$vg_db_conexion = FN_DB_CONEXION ();
//-----------------------------------------
// INICIALIZACION DE VARIABLES
//-----------------------------------------
$vl_cod_html = "";

$vl_operacion ="";
//-----------------------------------------
// ASIGNACION DE LA SECCION: MODULO DE LA SECCION
//-----------------------------------------

if (isset($_POST['operacion']))
{
    $vl_operacion = FN_RECIBIR_VARIABLES('operacion');
    switch ($vl_operacion)
    {
 
        //**************************************************
        //* SECCION : CONSULTA DE PRECIO DE COMPRA
        //**************************************************
        case "consultar_precio":
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('tono');
              $vf_msisdn  = "";
            if (isset($_SESSION['msisdn']))
            {
                if ($_SESSION['msisdn'] != "")
                {
                    $vf_msisdn  = FN_RECIBIR_VARIABLES('msisdn');
                }
            }
            //$vf_tono    = "116289";
            //$vf_msisdn  = "0981889975";
            $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();          
            $obj_consulta_backtones->MTD_ASIGNAR_MSISDN($vf_msisdn);
            $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
            $vf_reason = FN_RECIBIR_VARIABLES('reason');
            MTD_RETORNAR_HTML($obj_consulta_backtones->MTD_CONSULTAR_PRECIO($vf_reason));            
             //$vl_cod_html_seccion= $obj_web_busqueda->MTD_REALIZAR_BUSQUEDA();
        break;
         //**************************************************
        //* SECCION : ASIGNA ULTIMO BACKTONE ESCUCHADO
        //**************************************************
        case "asigna_backtone_escuchado":
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('tono');
            $vf_msisdn  = "";            
            $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
            $obj_consulta_backtones->MTD_ASIGNAR_MSISDN($vf_msisdn);
            $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
            $obj_consulta_backtones->MTD_ASIGNAR_ULTIMO_BACKTONE_ESCUCHADO();            
             //$vl_cod_html_seccion= $obj_web_busqueda->MTD_REALIZAR_BUSQUEDA();
        break;
        //**************************************************
        //* SECCION : COMPRAR BACKTONE
        //**************************************************
        case "comprar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Comprar Backtone: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono    = FN_RECIBIR_VARIABLES('tono');                                
                FN_NET_LOGGER("Ajax-> Compra de backtone msisdn: $vf_msisdn  tono:$vf_tono",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();                
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_COMPRAR_BACKTONES();
                FN_NET_LOGGER("Ajax-> Comprar Backtone: msisdn:$vf_msisdn ",2);
                MTD_RETORNAR_HTML($vf_resultado);                
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;

        //**************************************************
        //* SECCION : REGALAR BACKTONE
        //**************************************************
        case "regalar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Regalar Backtone: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_ani    = FN_RECIBIR_VARIABLES('ani');
                $vf_tono    = FN_RECIBIR_VARIABLES('tono');
                FN_NET_LOGGER("Ajax-> Regalar de backtone msisdn: $vf_msisdn  tono:$vf_tono",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_REGALAR_BACKTONES($vf_ani, $vf_tono);
                FN_NET_LOGGER("Ajax-> Regalar Backtone: msisdn:$vf_msisdn ",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
        //**************************************************
        //* SECCION : PEDIR BACKTONE
        //**************************************************
        case "pedir_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Pedir Backtone: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_ani    = FN_RECIBIR_VARIABLES('ani');
                $vf_tono   = FN_RECIBIR_VARIABLES('tono');
                FN_NET_LOGGER("Ajax-> Pedir backtone msisdn: $vf_msisdn  tono:$vf_tono ani: $vf_ani",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_PEDIR_BACKTONES($vf_ani, $vf_tono);
                FN_NET_LOGGER("Ajax-> Pedir Backtone: msisdn:$vf_msisdn ",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
        //**************************************************
        //* SECCION : DESCARGAR BACKTONE
        //**************************************************
        case "descargar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Descargar Backtone: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono    = FN_RECIBIR_VARIABLES('tono');
                FN_NET_LOGGER("Ajax-> Descargar de backtone msisdn: $vf_msisdn  tono:$vf_tono",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                FN_NET_LOGGER("Ajax-> Descargar Backtone: msisdn:$vf_msisdn ",2);
                $vf_resultado=$obj_consulta_backtones->MTD_DESCARGAR_BACKTONES();                
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
        //**************************************************
        //* SECCION : LISTAR AMIGOS
        //**************************************************
        case "popup_listar_amigos":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> popup_listar_amigos: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono    = FN_RECIBIR_VARIABLES('tono');
                $vf_metodo    = FN_RECIBIR_VARIABLES('metodo');
                FN_NET_LOGGER("Ajax-> popup_listar_amigos: $vf_msisdn  tono:$vf_tono metodo:$vf_metodo ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                MTD_RETORNAR_HTML($obj_consulta_backtones->MTD_APLICAR_TEMPLATE_POPUP_LISTA_AMIGOS($vf_metodo));
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }


        break;
        //**************************************************
        //* SECCION : LOGIN USUARIO
        //**************************************************
        case "login_usuario":
            include_once('class/CLASS_SESSION.php');
            include_once ('class/CLASS_USUARIOS_BACKTONES.php');
            $vf_pin     = FN_RECIBIR_VARIABLES('pin','POST');
            $vf_msisdn  = FN_RECIBIR_VARIABLES('msisdn','POST');
       
            
            $obj_usuarios_backtones = new CLASS_USUARIOS_BACKTONES();
            $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($vf_msisdn );
            FN_NET_LOGGER("Ajax-> Login Usuario msisdn: $vf_msisdn  pin:$vf_pin > Validar msisdn",2);
            if($obj_usuarios_backtones->MTD_VALIDAR_MSISDN($vf_msisdn) == true)
            {
                FN_NET_LOGGER("Ajax-> Login Usuario msisdn: $vf_msisdn  pin:$vf_pin > msisdn valido, verificando session",2);
                $obj_session = new CLASS_SESSION();
                $vl_resultado_login = false;
                $vl_resultado_login  = $obj_session->check_login($vf_msisdn, $vf_pin);
                $vl_template="";
                if ($vl_resultado_login == true)
                {
                    FN_NET_LOGGER("Ajax-> Login Usuario: msisdn:$vf_msisdn > inicio de sesion exitoso ",2);
                    $vl_template=FN_LEER_TPL("tpl/tpl-login-usuario-loggedin.html");
                    $vl_template = FN_REEMPLAZAR("{tpl-msisdn}",$vf_msisdn, $vl_template );
                    MTD_RETORNAR_HTML ($vl_template) ;
                }
                else
                {
                    MTD_RETORNAR_HTML("-1");
                }
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Login Usuario msisdn: $vf_msisdn  pin:$vf_pin > Msisdn invalido",2);
                MTD_RETORNAR_HTML("-1");
            }
        break;

        //**************************************************
        //* SECCION : LOGIN USUARIO - CERRAR SESSION
        //**************************************************
        case "cerrar_session":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->logout();

            if ($vl_resultado_login == true)
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > Cerrada exitosamente ",2);
                MTD_RETORNAR_HTML (FN_LEER_TPL("tpl/tpl-login-usuario.html"));
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-1");
            }           
        break;
        //**************************************************
        //* SECCION : AMIGOS - AGREGAR AMIGOS
        //**************************************************
        case "agregar_amigos":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }
            FN_NET_LOGGER("Ajax-> Administrar Amigos: msisdn:$vf_msisdn  agregar nuevo amigo ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {

                include_once('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > Agregar nuevos amigos ",2);
                $vl_ani         ="";
                $vl_nombre_ani  ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_nombre_ani  = FN_RECIBIR_VARIABLES('nombre_ani');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = false;
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > ani :$vl_ani nombre: $vl_nombre_ani",5);
                $vl_resultado   = $obj_adm_amigos->MTD_AGREGAR_AMIGOS($vl_ani, $vl_nombre_ani);

                if ($vl_resultado == true )
                {
                    FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > ani :$vl_ani nombre: $vl_nombre_ani > Exitosa mostrando nueva lista de amigos",5);
                    MTD_RETORNAR_HTML($obj_adm_amigos->MTD_LISTA_AMIGOS());
                }
                else
                {
                    MTD_RETORNAR_HTML("-1");
                }                
                
            }
            else
            {
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;

        //**************************************************
        //* SECCION : AMIGOS - EDITAR TONOS DEL AMIGO
        //**************************************************
        case "editar_tonos_amigo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Editar tonos Amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {               
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');                
                FN_NET_LOGGER("Ajax->  Editar tonos Amigos: > msisdn:$vf_msisdn  ",2);

                $vl_ani         ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Editar tonos Amigos: > msisdn:$vf_msisdn > ani :$vl_ani",5);
                $vl_resultado   = $obj_adm_amigos->MTD_EDITAR_TONOS_AMIGO($vl_ani);                               
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Editar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - ASIGNAR - TONO - AMIGO
        //**************************************************
        case "asignar_tonos_amigo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Asignar tonos Amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');                

                $vl_ani         ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_tono_amigo  = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Asignar tonos Amigos: > msisdn:$vf_msisdn > ani :$vl_ani tono:$vl_tono_amigo ",5);
                
                $vl_resultado   = $obj_adm_amigos->MTD_ASIGNAR_TONOS_AMIGO($vl_ani, $vl_tono_amigo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - DESASIGNAR - TONO - AMIGO
        //**************************************************
        case "desasignar_tonos_amigo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');

                $vl_ani         ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_tono_amigo  = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > ani :$vl_ani tono:$vl_tono_amigo ",5);

                $vl_resultado   = $obj_adm_amigos->MTD_DESASIGNAR_TONOS_AMIGO($vl_ani, $vl_tono_amigo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - ACTUALIZAR DATOS
        //**************************************************
        case "actualizar_datos_amigo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Actualizar datos Amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                $vl_ani         ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_nombre_ani  = FN_RECIBIR_VARIABLES('nombre_ani');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Actualizar datos amigos: > msisdn:$vf_msisdn > ani :$vl_ani nombre :$vl_nombre_ani ",5);

                $vl_resultado   = $obj_adm_amigos->MTD_ACTUALIZAR_DATOS_AMIGO($vl_ani, $vl_nombre_ani);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - ELIMINAR
        //**************************************************
        case "eliminar_amigo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Eliminar Amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                $vl_ani         ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_nombre_ani  = FN_RECIBIR_VARIABLES('nombre_ani');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Eliminar amigos: > msisdn:$vf_msisdn > ani :$vl_ani nombre :$vl_nombre_ani ",5);

                $vl_resultado   = $obj_adm_amigos->MTD_ELIMINAR_AMIGO($vl_ani);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - VER LISTA DE AMIGOS
        //**************************************************
        case "ver_lista_amigos":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Ver lista de amigos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                $vl_ani         ="";                
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = "";                
                $vl_resultado   = $obj_adm_amigos->MTD_RETORNAR_CODIGO_HTML();
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Ver lista de amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : GRUPOS - AGREGAR GRUPOS
        //**************************************************
        case "agregar_grupos":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }
            FN_NET_LOGGER("Ajax-> Agregar grupo: msisdn:$vf_msisdn  agregar nuevo grupo ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                FN_NET_LOGGER("Ajax-> : Administrar Grupos: > msisdn:$vf_msisdn > Agregar nuevos grupos ",2);
                $vl_nombre_grupo="";
                $vl_nombre_grupo= FN_RECIBIR_VARIABLES('nombre_grupo');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = false;
                FN_NET_LOGGER("Ajax-> : Agregar grupo: > msisdn:$vf_msisdn > nombre grupo: $vl_nombre_ani",5);
                $vl_resultado   = $obj_adm_grupos->MTD_AGREGAR_GRUPOS($vl_nombre_grupo);

                if ($vl_resultado == true )
                {
                    FN_NET_LOGGER("Ajax-> : Administrar Grupos : > msisdn:$vf_msisdn > nombre: $vl_nombre_ani > Se agrego correctamente el grupo",5);
                    MTD_RETORNAR_HTML($obj_adm_grupos->MTD_LISTA_GRUPOS());
                }
                else
                {
                    MTD_RETORNAR_HTML("-2");
                }                
            }
            else
            {
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : GRUPOS - EDITAR TONOS DEL GRUPO
        //**************************************************
        case "editar_tonos_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Editar tonos Grupos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                FN_NET_LOGGER("Ajax->  Editar tonos Grupos: > msisdn:$vf_msisdn  ",2);

                $vl_id_grupo    ="";
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Editar tonos Grupo: > msisdn:$vf_msisdn > groupid :$vl_id_grupo",5);
                $vl_resultado   = $obj_adm_grupos->MTD_EDITAR_TONOS_GRUPO($vl_id_grupo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Editar tonos Grupos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : GRUPOS - ASIGNAR - TONO - GRUPO
        //**************************************************
        case "asignar_tonos_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Asignar tonos Grupos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $vl_tono_grupo  = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Asignar tonos Grupos: > msisdn:$vf_msisdn > tono:$vl_tono_amigo ",5);

                $vl_resultado   = $obj_adm_grupos->MTD_ASIGNAR_TONOS_GRUPO($vl_id_grupo, $vl_tono_grupo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : AMIGOS - AGREGAR AMIGOS - GRUPOS
        //**************************************************
        case "agregar_amigos_grupos":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }
            FN_NET_LOGGER("Ajax-> Administrar Amigos: msisdn:$vf_msisdn  agregar nuevo amigo ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {

                include_once('class/CLASS_WEB_ADMINISTRAR_AMIGOS.php');
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > Agregar nuevos amigos ",2);
                $vl_ani         ="";
                $vl_nombre_ani  ="";
                $vl_id_grupo    ="";
                $vl_ani         = FN_RECIBIR_VARIABLES('ani');
                $vl_nombre_ani  = FN_RECIBIR_VARIABLES('nombre_ani');
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $obj_adm_amigos = new CLASS_WEB_ADMINISTRAR_AMIGOS();
                $vl_resultado   = false;
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > ani :$vl_ani nombre: $vl_nombre_ani",5);
                $vl_resultado   = $obj_adm_amigos->MTD_AGREGAR_AMIGOS($vl_ani, $vl_nombre_ani);
                

                if ($vl_resultado == true )
                {
                    include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                    $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                    $vl_resultado   = "";
                    FN_NET_LOGGER("Ajax-> Editar tonos Grupo: > msisdn:$vf_msisdn > groupid :$vl_id_grupo",5);
                    $vl_resultado   = $obj_adm_grupos->MTD_EDITAR_TONOS_GRUPO($vl_id_grupo);
                    MTD_RETORNAR_HTML($vl_resultado);
                }
                else
                {
                    MTD_RETORNAR_HTML("-1");
                }

            }
            else
            {
                FN_NET_LOGGER("Ajax-> : Administrar Amigos: > msisdn:$vf_msisdn > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;

        //**************************************************
        //* SECCION : AMIGOS - DESASIGNAR - TONO - AMIGO
        //**************************************************
        case "desasignar_tonos_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Desasignar tonos Grupos: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');               
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $vl_tono_grupo  = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Desasignar tonos Grupos: > msisdn:$vf_msisdn > grupo :$vl_id_grupo tono:$vl_tono_grupo ",5);

                $vl_resultado   = $obj_adm_grupos->MTD_DESASIGNAR_TONOS_GRUPO($vl_id_grupo, $vl_tono_grupo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar tonos Grupos: > msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : GRUPOS - ASIGNAR - AMIGO - GRUPO
        //**************************************************
        case "asignar_amigo_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Asignar Amigo Grupo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $vl_ani_amigo  = FN_RECIBIR_VARIABLES('ani');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Asignar Amigo Grupo: > msisdn:$vf_msisdn > grupo :$vl_id_grupo ani :$vl_ani_amigo ",5);

                $vl_resultado   = $obj_adm_grupos->MTD_ASIGNAR_AMIGO_GRUPO($vl_id_grupo, $vl_ani_amigo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Asignar Amigo Grupo:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : GRUPOS - DESASIGNAR - AMIGO - GRUPO
        //**************************************************
        case "desasignar_amigo_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Desasignar Amigo Grupo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $vl_ani_amigo  = FN_RECIBIR_VARIABLES('ani');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Desasignar Amigo Grupo: > msisdn:$vf_msisdn > grupo :$vl_id_grupo ani :$vl_ani_amigo ",5);

                $vl_resultado   = $obj_adm_grupos->MTD_DESASIGNAR_AMIGO_GRUPO($vl_id_grupo, $vl_ani_amigo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar Amigo Grupo:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
         //**************************************************
        //* SECCION : GRUPOS - ACTUALIZAR DATOS
        //**************************************************
        case "actualizar_datos_grupo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Actualizar datos del Grupo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_GRUPOS.php');
                $vl_id_grupo    = FN_RECIBIR_VARIABLES('id_grupo');
                $vl_nombre_grupo  = FN_RECIBIR_VARIABLES('nombre_grupo');
                $obj_adm_grupos = new CLASS_WEB_ADMINISTRAR_GRUPOS();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Actualizar datos Grupo: > msisdn:$vf_msisdn > grupo :$vl_id_grupo nombre :$vl_nombre_grupo ",5);

                $vl_resultado   = $obj_adm_grupos->MTD_ACTUALIZAR_DATOS_GRUPO($vl_id_grupo, $vl_nombre_grupo);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desasignar Amigo Grupo:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - DESACTIVAR BACKTONE
        //**************************************************
        case "desactivar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Desactivar Backtone: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');                
                $vl_id_tono = FN_RECIBIR_VARIABLES('id_tono');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Desactivar Backtone: > msisdn:$vf_msisdn >  tono:$vl_id_tono  ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_DESACTIVAR_BACKTONE($vl_id_tono);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desactivar Backtone:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - ACTIVAR BACKTONE
        //**************************************************
        case "activar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Activar Backtone: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_id_tono = FN_RECIBIR_VARIABLES('id_tono');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax-> Activar Backtone: > msisdn:$vf_msisdn >  tono:$vl_id_tono  ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_ACTIVAR_BACKTONE($vl_id_tono);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Desactivar Backtone:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - SIGNAR MODALIDAD BACKTONES
        //**************************************************
        case "asignar_modalidad_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Asignar modalidad backtone: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_modalidad = FN_RECIBIR_VARIABLES('modalidad');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Asignar modalidad backtone: > msisdn:$vf_msisdn >  modalidad :$vl_modalidad  ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_ASGINAR_MODALIDAD_BACKTONE($vl_modalidad);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->Asignar modalidad backtone:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES -ELIMINAR BACKTONE
        //**************************************************
        case "eliminar_backtone":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Eliminando backtone: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_id_backtone = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Eliminando backtone:  > msisdn:$vf_msisdn >  tono :$vl_id_backtone  ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_ELIMINAR_BACKTONE($vl_id_backtone);
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->Eliminando backtone:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - Asignar Intervalo de horas rotacion
        //**************************************************
        case "asignar_intervalo_rotacion":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Asignar_intervalo_rotacion: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_intervalo = FN_RECIBIR_VARIABLES('intervalo');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Asignar_intervalo_rotacion:  > msisdn:$vf_msisdn >  tono :$vl_intervalo  ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_DB_ASIGNAR_PARAM($vl_intervalo);
                if ($vl_resultado == true)
                {
                    MTD_RETORNAR_HTML("1");
                }
                else
                {
                    MTD_RETORNAR_HTML("-1");
                }

            }
            else
            {
                FN_NET_LOGGER("Ajax->Asignar_intervalo_rotacion:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;

        //**************************************************
        //* SECCION : MIS BACKTONES - ASIGNAR TURNO
        //**************************************************
        case "asignar_backtone_turno":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Asignar backtone turno: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_id_tono = FN_RECIBIR_VARIABLES('tono');
                $vl_turno = FN_RECIBIR_VARIABLES('turno');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Asignar backtone turno:> msisdn:$vf_msisdn >  tono :$vl_id_tono turno:$vl_turno   ",5);

                $vl_resultado   = $obj_adm_backtones ->MTD_ASIGNAR_BACKTONE_TURNO($vl_id_tono, $vl_turno);
                MTD_RETORNAR_HTML($vl_resultado);

            }
            else
            {
                FN_NET_LOGGER("Ajax->Asignar_intervalo_rotacion:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
         //**************************************************
        //* SECCION : MIS BACKTONES - ASIGNAR TURNO
        //**************************************************
        case "desasignar_backtone_turno":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Desasignar backtone turno: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_id_tono = FN_RECIBIR_VARIABLES('tono');
                $vl_turno = FN_RECIBIR_VARIABLES('turno');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Desasignar backtone turno:> msisdn:$vf_msisdn >  tono :$vl_id_tono turno:$vl_turno   ",5);
                $vl_resultado   = $obj_adm_backtones ->MTD_DESASIGNAR_BACKTONE_TURNO($vl_id_tono, $vl_turno);
                MTD_RETORNAR_HTML($vl_resultado);

            }
            else
            {
                FN_NET_LOGGER("Ajax->Desasignar_intervalo_rotacion:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - ASIGNAR TONO FIJO
        //**************************************************
        case "asignar_backtone_fijo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Asignar backtone fijo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');                
                $vl_id_tono = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Asignar backtone fijo:> msisdn:$vf_msisdn >  tono :$vl_id_tono ",5);
                $obj_adm_backtones ->MTD_DB_ASIGNAR_PARAM("$vl_id_tono");
                $obj_adm_backtones ->MTD_APLICAR_TEMPLATE();
                $vl_resultado   = $obj_adm_backtones ->MTD_RETORNAR_CODIGO_HTML();
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->Desasignar_intervalo_rotacion:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - ASIGNAR TONO FIJO
        //**************************************************
        case "desasignar_backtone_fijo":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Dessignar backtone fijo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_BACKTONES.php');
                $vl_id_tono = FN_RECIBIR_VARIABLES('tono');
                $obj_adm_backtones = new CLASS_WEB_ADMINISTRAR_BACKTONES();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Desasignar backtone fijo:> msisdn:$vf_msisdn >  tono :$vl_id_tono ",5);
                $obj_adm_backtones ->MTD_DB_ASIGNAR_PARAM("");
                $obj_adm_backtones ->MTD_APLICAR_TEMPLATE();
                $vl_resultado   = $obj_adm_backtones ->MTD_RETORNAR_CODIGO_HTML();
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->Desasignar backtone fijo:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : MIS BACKTONES - ACTUALIZAR DATOS
        //**************************************************
        case "actualizar_datos_perfil":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax->Dessignar backtone fijo: msisdn:$vf_msisdn   ",2);

            $obj_session         = new CLASS_SESSION();
            $vl_resultado_login  = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once ('class/CLASS_WEB_ADMINISTRAR_PERFIL.php');
                //actualizar_datos_perfil
                //nombre: vl_nombre, apellido: vl_apellido, nickname: vl_nickname, sexo: vl_sexo, email: vl_email
                $vl_nombre  = FN_RECIBIR_VARIABLES('nombre');
                $vl_apellido= FN_RECIBIR_VARIABLES('apellido');
                $vl_nickname= FN_RECIBIR_VARIABLES('nickname');
                $vl_sexo    = FN_RECIBIR_VARIABLES('sexo');
                $vl_email   = FN_RECIBIR_VARIABLES('email');
                $obj_adm_perfil = new CLASS_WEB_ADMINISTRAR_PERFIL();
                $vl_resultado   = "";
                FN_NET_LOGGER("Ajax->Actualizar datos usuario :> $vl_nombre, $vl_apellido , $vl_nickname , $vl_sexo,$vl_email  ",5);
                $vl_resultado = $obj_adm_perfil->MTD_ACTUALIZAR_DATOS_PERFIL($vl_nombre, $vl_apellido, $vl_nickname, $vl_sexo, $vl_email);                               
                MTD_RETORNAR_HTML($vl_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->Desasignar backtone fijo:> msisdn:$vf_msisdn  > No se encuentra activo el usuario ",2);
                MTD_RETORNAR_HTML("-2");
            }
        break;
        //**************************************************
        //* SECCION : USUARIOS - ACTUALIZAR PIN
        //**************************************************
        case "actualizar_pin_usuario":            

            FN_NET_LOGGER("Ajax->Actualizar pin usuario",2);
            include_once ('class/CLASS_USUARIOS_BACKTONES.php');

            $obj_usuarios_backtones= new CLASS_USUARIOS_BACKTONES();
            $vl_msisdn2  = FN_RECIBIR_VARIABLES('msisdn','POST');
            FN_NET_LOGGER("Ajax->Actualizar pin usuario >recibe el msisdn:$vl_msisdn2 ",5);
            $obj_usuarios_backtones->MTD_ASIGNAR_MSISDN($vl_msisdn2);

            FN_NET_LOGGER("Ajax->Actualizar pin usuario >Asignar nuevo pin a msisdn:$vl_msisdn2 ",5);
            $vl_resultado = $obj_usuarios_backtones->MTD_ACTUALIZAR_PIN_USUARIO($vl_msisdn2  );
            
            MTD_RETORNAR_HTML($vl_resultado);
            
        break;
        //**************************************************
        //* SECCION : BACKTONES - VOTAR RANKING
        //**************************************************
        case "votar_ranking":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Votar Ranking: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
                $vf_ranking = FN_RECIBIR_VARIABLES('ranking');
                FN_NET_LOGGER("Ajax-> Votar Ranking >  msisdn: $vf_msisdn  tono:$vf_tono ranking: $vf_ranking",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_VOTAR_RANKING($vf_ranking);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - VOTAR ME GUSTA
        //**************************************************
        case "votar_megusta":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Votar Me gusta: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
                FN_NET_LOGGER("Ajax-> Votar Me gusta>  msisdn: $vf_msisdn  tono:$vf_tono ranking: $vf_ranking",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_VOTAR_MEGUSTA();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - INGRESAR COMENTARIO
        //**************************************************
        case "ingresar_comentario":
            include_once('class/CLASS_SESSION.php');
            $vf_msisdn="";
            $vf_resultado="";
            if (isset($_SESSION['msisdn']))
            {
                $vf_msisdn=$_SESSION['msisdn'];
            }

            FN_NET_LOGGER("Ajax-> Ingresar comentario: msisdn:$vf_msisdn ",2);
            $obj_session = new CLASS_SESSION();
            $vl_resultado_login = false;
            $vl_resultado_login  = $obj_session->MTD_START();

            if ($vl_resultado_login == true)
            {
                include_once('class/CLASS_OPERACIONES_BACKTONES.php');
                $vf_tono        = FN_RECIBIR_VARIABLES('id_tono');
                $vf_comentario  = FN_RECIBIR_VARIABLES('comentario');
                FN_NET_LOGGER("Ajax-> Ingresar Comentario>  msisdn: $vf_msisdn  tono:$vf_tono comentario: $vf_comentario",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_INGRESAR_COMENTARIO($vf_comentario);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax-> Cerrar session: msisdn:$vf_msisdn > No cerro correctamente",2);
                MTD_RETORNAR_HTML ("-2");
            }
        break;
         //**************************************************
        //* SECCION : BACKTONES - MOSTRAR RANKING
        //**************************************************
        case "mostrar_ranking":            
            $vf_msisdn="";
            $vf_resultado="";
            
            FN_NET_LOGGER("Ajax->Mostrar Ranking:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Ranking > tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_RANKING();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR REPRODUCCIONES
        //**************************************************
        case "mostrar_reproducciones":
            $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Reproducciones:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Ranking > tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_REPRODUCCIONES();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR MEGUSTA
        //**************************************************
        case "mostrar_megusta":
            $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Megusta:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Megusta> tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_MEGUSTA();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR COMENTARIOS
        //**************************************************
        case "mostrar_comentarios":
            $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Comentarios:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Comentarios> tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_COMENTARIOS();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //******************************************************
        //* SECCION : BACKTONES - MOSTRAR TODOS LOS COMENTARIOS
        //******************************************************
        case "mostrar_todos_los_comentarios":
            $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Todos los Comentarios:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Comentarios> tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_TODOS_LOS_COMENTARIOS();
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - DENUNCIAR COMENTARIOS
        //**************************************************
        case "denunciar_comentario":
            $vf_msisdn="";
            $vf_resultado="";
            FN_NET_LOGGER("Ajax->Denunciar Comentarios:  ",2);            
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_id_comentario    = FN_RECIBIR_VARIABLES('id_comentario');
            FN_NET_LOGGER("Ajax-> Denunciar Comentarios> tono:$vf_tono comentario: $vf_id_comentario",2);
            $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();            
            $vf_resultado=$obj_consulta_backtones->MTD_DENUNCIAR_COMENTARIOS($vf_id_comentario);
            MTD_RETORNAR_HTML($vf_resultado);
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR IMAGEN PORTADA
        //**************************************************
        case "mostrar_imagen_portada":
             $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Imagen Portada:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar  Imagen Portada> tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_IMAGENES_ALBUM();
                FN_NET_LOGGER("Ajax-> Mostrar  Imagen Portada> Album:$vf_resultado",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR NOMBRE ARTISTA
        //**************************************************
        case "mostrar_nombre_artista":
             $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Nombre Artista:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar  Nombre Artista > tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_NOMBRE_ARTISTA();
                FN_NET_LOGGER("Ajax-> Mostrar  Imagen Portada> Album:$vf_resultado",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : BACKTONES - MOSTRAR GENERO
        //**************************************************
        case "mostrar_nombre_genero":
             $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Nombre Genero:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar  Nombre Genero > tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_MOSTRAR_NOMBRE_GENERO();
                FN_NET_LOGGER("Ajax-> Mostrar  Genero > $vf_resultado",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
        //**************************************************
        //* SECCION : ACTUALIZAR FACEBOOK
        //**************************************************
        case "mostrar_link_facebook":
             $vf_msisdn="";
            $vf_resultado="";

            FN_NET_LOGGER("Ajax->Mostrar Link Facebook:  ",2);
            $vl_resultado_login = false;
            include_once('class/CLASS_OPERACIONES_BACKTONES.php');
            $vf_tono    = FN_RECIBIR_VARIABLES('id_tono');
            if ($vf_tono > 0)
            {
                FN_NET_LOGGER("Ajax-> Mostrar Link Facebook> tono:$vf_tono ",2);
                $obj_consulta_backtones= new CLASS_OPERACIONES_BACKTONES();
                $obj_consulta_backtones->MTD_ASIGNAR_TONO($vf_tono);
                $vf_resultado=$obj_consulta_backtones->MTD_ACTUALIZAR_LINK_FACEBOOK();
                FN_NET_LOGGER("Ajax-> Mostrar Link Facebook> tono:$vf_tono > $vf_resultado",2);
                MTD_RETORNAR_HTML($vf_resultado);
            }
            else
            {
                FN_NET_LOGGER("Ajax->No hay tono:  ",2);
                MTD_RETORNAR_HTML(" ");
            }
        break;
    }
    
    
}
function MTD_RETORNAR_HTML($vp_html)
{
   echo  FN_COMPRIMIR_HTML($vp_html);
}

?>
