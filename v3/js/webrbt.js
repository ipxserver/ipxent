function MTD_COMPRAR_BACKTONE()
{
    //myPlayList[i].name
    var vl_id_tono=0;    
    vl_id_tono=$("#tb_id_tono").val();
    $("#loader").fadeIn();
    var parametros = {operacion: "comprar_backtone", tono: vl_id_tono};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {

                if (data == "-1")
                {
                    
                    $("#bt-descargar").HideAllBubblePopups();
                     $("#bt-descargar").SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'azure',
                           innerHtml: 'Atencion, el servicio no se encuentra disponible<br> favor intente mas tarde', mouseOver: 'hide'
                     });
                     $("#bt-descargar").ShowBubblePopup();
                }
                else if (data == "-2")
                {                                       
                    $('#tb_login_pin').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                     });
                     $("#tb_login_pin").ShowBubblePopup();

                }
                else if (data == "-3")
                {
                    $("#bt_comprar").HideAllBubblePopups();
                    $("#bt_comprar").SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: '<img src="estilos/tigo/images/error.png"/>  Usted no posee saldo suficiente <br>para realizar la compra del tono', mouseOver: 'hide'
                     });
                     $("#bt_comprar").ShowBubblePopup();
                     //alert('Ingreso en sin saldo');

                }
                else if (data == "-4")
                {
                    $("#bt-descargar").HideAllBubblePopups();
                    $("#bt-descargar").SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, Usted ya posee el tono seleccionado', mouseOver: 'hide'
                     });
                     $("#bt-descargar").ShowBubblePopup();

                }
                else if (data == "-5")
                {
                    $("#bt_comprar").HideAllBubblePopups();
                    $("#bt-comprar").SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: '<img src="estilos/tigo/images/error.png"/> Favor aguarde 5 segundos <br> para realizar la siguiente operacion', mouseOver: 'hide'
                     });
                     $("#bt-comprar").ShowBubblePopup();

                }
                else if (data != "-1")
                {
                     $.facebox(data);
                }
                 $("#depurador").html(data);
                 $("#loader").fadeOut();
             }       
    });
}

function MTD_REGALAR_BACKTONE_MANUAL()
{
        var vf_ani="";
        var vl_id_tono=0;
        vl_id_tono=$("#tb_id_tono").val();
        vf_ani=$('#facebox input:first').val();
        MTD_REGALAR_BACKTONE(vf_ani,vl_id_tono);       
}
function MTD_REGALAR_BACKTONE(vp_ani, vp_id_tono)
{        
        $("#loader").fadeIn();
        var parametros = {operacion: "regalar_backtone", tono: vp_id_tono, ani: vp_ani};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "-1")
                    {
                         $("#bt-descargar").HideAllBubblePopups();
                         $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'azure',
                               innerHtml: 'Atencion, el servicio no se encuentra disponible<br> favor intente mas tarde', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                    }
                    else if (data == "-2")
                    {
                        $('#tb_login_pin').SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                         });
                         $("#tb_login_pin").ShowBubblePopup();

                    }
                    else if (data == "-3")
                    {
                        $("#bt-descargar").HideAllBubblePopups();
                        $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/>  Usted no posee saldo suficiente <br>para realizar la compra del tono', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                         //alert('Ingreso en sin saldo');

                    }
                    else if (data == "-4")
                    {
                        $("#bt-descargar").HideAllBubblePopups();
                        $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, El usuario seleccionado ya posee el tono seleccionado', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();

                    }
                    else if (data == "-5")
                    {
                        $("#bt_comprar").HideAllBubblePopups();
                        $("#bt-comprar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/> Favor aguarde 5 segundos <br> para realizar la siguiente operacion', mouseOver: 'hide'
                         });
                         $("#bt-comprar").ShowBubblePopup();
                    }
                    else if (data != "-1")
                    {
                         $.facebox(data);
                    }
                     $("#depurador").html(data);
                     $("#loader").fadeOut();
                 }
        });
         $("#loader").fadeOut();
}

function MTD_PEDIR_BACKTONE_MANUAL()
{
        var vf_ani="";
        var vl_id_tono=0;
        vl_id_tono=$("#tb_id_tono").val();
        vf_ani=$('#facebox input:first').val();

        MTD_PEDIR_BACKTONE(vf_ani,vl_id_tono);

}
function MTD_PEDIR_BACKTONE(vp_ani, vp_id_tono)
{
         $("#loader").fadeIn();
        var parametros = {operacion: "pedir_backtone", tono: vp_id_tono, ani: vp_ani};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "-1")
                    {
                         $("#bt-descargar").HideAllBubblePopups();
                         $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'azure',
                               innerHtml: 'Atencion, el servicio no se encuentra disponible<br> favor intente mas tarde', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                    }
                    else if (data == "-2")
                    {
                        $('#tb_login_pin').SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar el regalo de un Backtone', mouseOver: 'hide'
                         });
                         $("#tb_login_pin").ShowBubblePopup();

                    }
                    else if (data == "-3")
                    {
                        $("#bt-descargar").HideAllBubblePopups();
                        $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/>  Usted no posee saldo suficiente <br>', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                         //alert('Ingreso en sin saldo');

                    }
                    else if (data == "-4")
                    {
                        $("#bt-descargar").HideAllBubblePopups();
                        $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, usted ya posee el backtone solicitado', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();

                    }
                    else if (data == "-5")
                    {
                        $("#bt_comprar").HideAllBubblePopups();
                        $("#bt-comprar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/> Favor aguarde 5 segundos <br> para realizar la siguiente operacion', mouseOver: 'hide'
                         });
                         $("#bt-comprar").ShowBubblePopup();
                    }
                    else if (data != "-1")
                    {
                         $.facebox(data);
                    }
                     $("#depurador").html(data);
                     $("#loader").fadeOut();
                 }
        });
         $("#loader").fadeOut();
}
function MTD_DESCARGAR_BACKTONE()
{
    var vl_precio=0;
    var vl_id_tono=0;
    vl_precio = $("#tb_precio_descarga").val();
    vl_id_tono= $("#tb_id_tono").val();
    if (vl_precio >=0)
    {
        var contenido_pregunta="";
        var vl_nombre_tono="";
        vl_nombre_tono=$("#tb_nombre_tono").val();
        contenido_pregunta="<div style='height:200px; widht:150px; font-size:12px; color:gray;'> <span> Precio del Ringtone  3.5 Bs.<br>Al momento de la descarga se te cobrar&aacute; un adicional por la navegaci&oacute;n,de acuerdo al tama&ntilde;o del archivo adquirido (Precio del mega 0.5 Bs.)<br> Servicio no disponible para Iphone, <br> Elegiste el ringtone <b>" + vl_nombre_tono + "</b>  <br>Para descargar el Ringtone el equipo debe ser compatible con el formato MP3. Para mayor informaci&oacute;n de tarifas llamar al *611.</span> <br>  <div style='width=100%; text-align:center;'>   <input type='button' style='width:60px;cursor:pointer;' class='estilo-input submit' value='Aceptar' onClick='javascript:MTD_DESCARGAR_BACKTONE2()'/> &nbsp;&nbsp;&nbsp; <input type='button' style='width:60px;cursor:pointer;' class='estilo-input submit' " ;
        contenido_pregunta= contenido_pregunta + 'onClick="javascript:$(document).trigger(';
        contenido_pregunta= contenido_pregunta + "'close.facebox')";
        contenido_pregunta= contenido_pregunta + '"';
        contenido_pregunta= contenido_pregunta + " value='Cancelar' /> </div>";
        $.facebox(contenido_pregunta);
    }
    else
    {
        $("#bt-descargar").HideAllBubblePopups();
        $("#bt-descargar").SetBubblePopupOptions({
               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
               themeName: 'orange',
               innerHtml: '<img src="estilos/tigo/images/error.png"/> El backtone seleccionado no posee la opcion de descargar', mouseOver: 'hide'
         });
         $("#bt-descargar").ShowBubblePopup();
    }
}
function MTD_DESCARGAR_BACKTONE2()
{
    var vl_precio=0;
    var vl_id_tono=0;
    vl_precio = $("#tb_precio_descarga").val();
    vl_id_tono= $("#tb_id_tono").val();
    if (vl_precio >=0)
    {        
        $("#loader").fadeIn();

        var parametros = {operacion: "descargar_backtone", tono: vl_id_tono};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "-1")
                    {
                         $("#bt-descargar").HideAllBubblePopups();
                         $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'azure',
                               innerHtml: 'Atencion, el servicio no se encuentra disponible<br> favor intente mas tarde', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                    }
                    else if (data == "-2")
                    {
                        $('#tb_login_pin').SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la descarga de un Backtone', mouseOver: 'hide'
                         });
                         $("#tb_login_pin").ShowBubblePopup();

                    }
                    else if (data == "-3")
                    {
                        $("#bt-descargar").HideAllBubblePopups();
                        $("#bt-descargar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/>  Usted no posee saldo suficiente <br> para realizar la descarga del backtone', mouseOver: 'hide'
                         });
                         $("#bt-descargar").ShowBubblePopup();
                         //alert('Ingreso en sin saldo');

                    }                   
                    else if (data == "-5")
                    {
                        $("#bt_comprar").HideAllBubblePopups();
                        $("#bt-comprar").SetBubblePopupOptions({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: '<img src="estilos/tigo/images/error.png"/> Favor aguarde 5 segundos <br> para realizar la siguiente operacion', mouseOver: 'hide'
                         });
                         $("#bt-comprar").ShowBubblePopup();
                    }
                    else if (data != "-1")
                    {
                         $.facebox(data);
                    }
                     $("#depurador").html(data);
                     $("#loader").fadeOut();
                 }
        });
         $("#loader").fadeOut();
    }
    else
    {
        $("#bt-descargar").HideAllBubblePopups();
        $("#bt-descargar").SetBubblePopupOptions({
               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
               themeName: 'orange',
               innerHtml: '<img src="estilos/tigo/images/error.png"/> El backtone seleccionado no posee la opcion de descargar', mouseOver: 'hide'
         });
         $("#bt-descargar").ShowBubblePopup();
    }
}
function MTD_CONSULTA_PRECIO(vp_reason)
{  
    var vl_id_tono=0;
    var vl_nombre_tono="";
    vl_id_tono=$("#tb_id_tono").val();

   var parametros = {operacion: "consultar_precio", tono: vl_id_tono, reason: vp_reason};
  // $("#depurador").html(timestamp +"\n Consultando precio: ");

   $.ajax({
    url: "operaciones_ajax.php",
    data: parametros,
    type: "POST",
    cache: false,
    async: true,
    success: function(data) {
        //var contenidoconsulta="";
                if (vp_reason == 2)
                {
                    $("#contenedor-precio-compra").html(data  +" Bs.");
                    $("#tb_precio_compra").val(data);
                }
                else if (vp_reason == 3)
                {
                    $("#tb_precio_regalo").val(data);
                }
                else if (vp_reason == 10)
                {
                    $("#contenedor-precio-renovacion").html(data  +" Bs.");
                    $("#tb_precio_renovacion").val(data);
                }
                else if (vp_reason == 5)
                {                    
                    $("#tb_precio_descarga").val(data);
                    if(data > 0)
                    {
                            $("#bt-descargar").animate( {"opacity" : 1}, 250 );
                    }
                    else
                    {
                            $("#bt-descargar").animate( {"opacity" : .0}, 250 );
                    }
                }
             
       }
    });
}
function MTD_ASIGNA_BACKTONE_ESCUCHADO()
{
   var vl_id_tono=0;   
   vl_id_tono=$("#tb_id_tono").val();
   var parametros = {operacion: "asigna_backtone_escuchado", tono: vl_id_tono};
   $.ajax({
    url: "operaciones_ajax.php",
    data: parametros,
    type: "POST",
    cache: false,
    async: true,
    success: function(data) {
        
             }
    });

}
function MTD_LIMPIAR_CONTENEDORES()
{
     $("#contenedor-precio-renovacion").html("");
     $("#contenedor-precio-compra").html("");
}

//******************************************************************************
// ADMINISTRACION DE SESSION DE USUARIO
//******************************************************************************
function MTD_CERRAR_SESSION()
{
    var vl_msisdn=0;
    var vl_pin  ="";    
    var parametros = {operacion: "cerrar_session"};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
             //$("#contenedor_debug_login").html(data).fadeIn();
       // $("#contenedor_login").hide();
            $("#contenedor_login").fadeOut();
            $("#contenedor_login").html(data).fadeIn();            
        }
    });
}
function MTD_VALIDAR_USUARIO()
{        
    var vl_msisdn=0;
    var vl_pin  ="";
    vl_msisdn   =$("#tb_login_msisdn").val();
    vl_pin      =$("#tb_login_pin").val();
    //$("#tb_nombre_tono").val(myPlayList[index].name);
    var parametros = {operacion: "login_usuario", msisdn: vl_msisdn, pin: vl_pin};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                    $("#contenedor_login").fadeOut();
                    $("#contenedor_login").html(data).fadeIn();                   
                    //$("#contenedor_login").fade();
                    //$("#contenedor-login-respuesta").html(data).fadeIn();
                }
                else
                {
                    $('#tb_login_pin').SetBubblePopupOptions({
                                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                                   themeName: 'orange',
                                   innerHtml: 'Usuario  o Contraseña incorrecto.', mouseOver: 'hide'
                            });
                    $('#tb_login_pin').ShowBubblePopup();
                }

         }
    });
}
//******************************************************************************
// ADMINISTRACION DE AMIGOS
//******************************************************************************
function MTD_AGREGAR_AMIGOS()
{
    var vl_ani;
    var vl_nombre_ani;
    //$("#tb_nombre_tono").val(myPlayList[index].name);
    vl_ani          =$("#tb_ani").val();
    vl_nombre_ani   =$("#tb_nombre_ani").val();
    var parametros = {operacion: "agregar_amigos", ani: vl_ani, nombre_ani: vl_nombre_ani};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
            if (data != "-1")
            {
                 $("#lista_amigos").html(data);
                 //$("#contenedor_login").html(data).fadeIn();
            }
            else  if (data == "-2")
            {
                MTD_CERRAR_SESSION();
            }
            else
            {
                $('#tb_ani').ShowBubblePopup();
            }
         }
    });
   
}

function MTD_EDITAR_AMIGO(vp_ani)
{
    var vl_ani;
    
    vl_ani         = vp_ani;
    var parametros = {operacion: "editar_tonos_amigo", ani: vl_ani};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {                    
                     $("#contenedor_administracion_amigos").html(data);
                     
                     
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_ani').ShowBubblePopup();
                }
         }
    });

}
function MTD_ASIGNAR_TONO(vp_tonos_amigo)
{
    var vl_ani;

    vl_ani         = $("#tb_numero_amigo").val();
    var parametros = {operacion: "asignar_tonos_amigo", ani: vl_ani, tono: vp_tonos_amigo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_amigos").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_ani').ShowBubblePopup();
                }
         }
    });

}
function MTD_DESASIGNAR_TONO(vp_tonos_amigo)
{
    var vl_ani;

    vl_ani         = $("#tb_numero_amigo").val();
    var parametros = {operacion: "desasignar_tonos_amigo", ani: vl_ani, tono: vp_tonos_amigo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_amigos").html(data);                  
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_ani').ShowBubblePopup();
                }
         }
    });

}

//
//METODOS ACTUALIZAR DATOS AMIGO
//------------------------------------------------------------------------------
function MTD_ACTUALIZAR_DATOS_AMIGO()
{
    var vl_ani;
    var vl_nombre_ani;
    //$("#tb_nombre_tono").val(myPlayList[index].name);
    vl_ani          =$("#tb_numero_amigo").val();
    vl_nombre_ani   =$("#tb_nombre_ani").val();
    var parametros = {operacion: "actualizar_datos_amigo", ani: vl_ani, nombre_ani: vl_nombre_ani};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#lista_amigos").html(data).fadeIn();
                     //$("#contenedor_login").html(data).fadeIn();
                     $('#tb_nombre_ani').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Datos actualizados exitosamente', mouseOver: 'hide'
                     });
                     $('#tb_nombre_ani').ShowBubblePopup();
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {                    
                    $('#tb_nombre_ani').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error!, <br>favor verifique los datos ingresados', mouseOver: 'hide'
                    });
                    $('#tb_nombre_ani').ShowBubblePopup();
                    
                }
         }
    });

}
//------------------------------------------------------------------------------
//METODOS ELIMINAR AMIGO
//------------------------------------------------------------------------------
function MTD_ELIMINAR_AMIGO()
{
    var vl_ani;
    var vl_nombre_ani;
    //$("#tb_nombre_tono").val(myPlayList[index].name);
    vl_ani          =$("#tb_numero_amigo").val();
    vl_nombre_ani   =$("#tb_nombre_ani").val();
    var parametros = {operacion: "eliminar_amigo", ani: vl_ani, nombre_ani: vl_nombre_ani};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     MTD_VER_LISTA_AMIGOS();
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }                
         }
    });

}
//
//METODOS VER LISTA DE AMIGOS
//------------------------------------------------------------------------------
function MTD_VER_LISTA_AMIGOS()
{    
    //$("#tb_nombre_tono").val(myPlayList[index].name);    
    var parametros = {operacion: "ver_lista_amigos"};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                      $("#contenedor_administracion_amigos").html(data);
                     //$("#contenedor_login").html(data).fadeIn();                     
                }
                else
                {                    

                }
         }
    });

}
//******************************************************************************
// ADMINISTRACION DE GRUPOS
//******************************************************************************
function MTD_AGREGAR_GRUPOS()
{    
    var vl_nombre_grupo;
    vl_nombre_grupo   =$("#tb_nombre_grupo").val();
    var parametros = {operacion: "agregar_grupos",nombre_grupo: vl_nombre_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                    $("#tb_nombre_grupo").val("");
                    $("#lista_grupos").html(data);

                     //$("#contenedor_login").html(data).fadeIn();
                }
                else if(data == "-2")
                {
                    ('#tb_nombre_grupo').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, se ha cerrado su session, favor ingrese nuevamente sus datos', mouseOver: 'hide'
                    });

                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
                else
                {
                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
         }
    });

}
function MTD_EDITAR_GRUPO(vp_id_grupo)
{
    var vl_id_grupo;

    vl_id_grupo         = vp_id_grupo;
    var parametros = {operacion: "editar_tonos_grupo", id_grupo: vl_id_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {                    
                     $("#contenedor_administracion_grupos").html(data);
                }
                else
                {
                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
         }
    });

}
//******************************************************************************
// ADMINISTRACION DE AMIGOS2 DESDE GRUPOS
//******************************************************************************
function MTD_AGREGAR_AMIGOS2()
{
    var vl_ani;
    var vl_nombre_ani;
    var vl_id_grupo;
    vl_id_grupo     = $("#tb_id_grupo").val();    
    vl_ani          =$("#tb_ani").val();
    vl_nombre_ani   =$("#tb_nombre_ani").val();
    var parametros = {operacion: "agregar_amigos_grupos", ani: vl_ani, nombre_ani: vl_nombre_ani, id_grupo: vl_id_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
            if (data != "-1")
            {
                  $("#contenedor_administracion_grupos").html(data);
                 //$("#contenedor_login").html(data).fadeIn();
            }
            else  if (data == "-2")
            {
                MTD_CERRAR_SESSION();
            }
            else
            {
                $('#tb_ani').ShowBubblePopup();
            }
         }
    });

}
function MTD_ASIGNAR_TONO_GRUPO(vp_tonos_grupo)
{
    var vl_id_grupo;
    vl_id_grupo     = $("#tb_id_grupo").val();
    var parametros = {operacion: "asignar_tonos_grupo", id_grupo: vl_id_grupo , tono: vp_tonos_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_grupos").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    ('#tb_nombre_grupo').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error durante la asignacion, intente nuevamente mas tarde!', mouseOver: 'hide'
                    });
                }
         }
    });
}
function MTD_DESASIGNAR_TONO_GRUPO(vp_tonos_grupo)
{
    var vl_id_grupo;
    vl_id_grupo     = $("#tb_id_grupo").val();
    var parametros = {operacion: "desasignar_tonos_grupo", id_grupo: vl_id_grupo , tono: vp_tonos_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_grupos").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                   ('#tb_nombre_grupo').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error durante la asignacion, intente nuevamente mas tarde!', mouseOver: 'hide'
                    });
                }
         }
    });

}
function MTD_ASIGNAR_AMIGO_GRUPO(vp_ani_amigo)
{
    var vl_id_grupo;
    vl_id_grupo     = $("#tb_id_grupo").val();    
    var parametros = {operacion: "asignar_amigo_grupo", id_grupo: vl_id_grupo , ani: vp_ani_amigo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_grupos").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    ('#tb_nombre_grupo').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error durante la asignacion, intente nuevamente mas tarde!', mouseOver: 'hide'
                    });
                }
         }
    });
}
function MTD_DESASIGNAR_AMIGO_GRUPO(vp_ani_amigo)
{
    var vl_id_grupo;
    vl_id_grupo     = $("#tb_id_grupo").val();
    var parametros = {operacion: "desasignar_amigo_grupo", id_grupo: vl_id_grupo , ani: vp_ani_amigo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     $("#contenedor_administracion_grupos").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    ('#tb_nombre_grupo').SetBubblePopupOptions({
                            themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error durante la asignacion, intente nuevamente mas tarde!', mouseOver: 'hide'
                    });
                }
         }
    });
}
//
//METODOS ACTUALIZAR DATOS GRUPO
//------------------------------------------------------------------------------
function MTD_ACTUALIZAR_DATOS_GRUPO()
{
    var vl_id_grupo;
    var vl_nombre_grupo;
    //$("#tb_nombre_tono").val(myPlayList[index].name);
    vl_id_grupo     = $("#tb_id_grupo").val();
    vl_nombre_grupo   =$("#tb_nombre_grupo").val();
    var parametros = {operacion: "actualizar_datos_grupo", id_grupo: vl_id_grupo, nombre_grupo: vl_nombre_grupo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {                     
                     $('#tb_nombre_grupo').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Datos actualizados exitosamente', mouseOver: 'hide'
                     });
                     $('#tb_nombre_grupo').ShowBubblePopup();
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_nombre_grupo').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error!, <br>favor verifique los datos ingresados', mouseOver: 'hide'
                    });
                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
         }
    });

}

//******************************************************************************
// ADMINISTRACION DE MIS BACKTONES
//******************************************************************************
function MTD_DESACTIVAR_BACKTONE(vp_id_tono)
{
    
    var parametros = {operacion: "desactivar_backtonessssss", id_tono: vp_id_tono};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                     // $("#contenedor_administracion_backtones").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_nombre_grupo').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error!, <br>favor verifique los datos ingresados', mouseOver: 'hide'
                    });
                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
         }
    });

}
function MTD_ACTIVAR_BACKTONE(vp_id_tono)
{

    var parametros = {operacion: "activar_backtone", id_tono: vp_id_tono};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                      $("#contenedor_administracion_backtones").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                    $('#tb_nombre_grupo').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, ocurrio un error!, <br>favor verifique los datos ingresados', mouseOver: 'hide'
                    });
                    $('#tb_nombre_grupo').ShowBubblePopup();
                }
         }
    });

}
function MTD_ASIGNAR_MODALIDAD_BACKTONE(vp_id_modalidad)
{    
    var parametros = {operacion: "asignar_modalidad_backtone", modalidad: vp_id_modalidad};
   
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                      $("#contenedor_administracion_backtones").html(data);
                       $('#btn-rotacion1').HideBubblePopup();
                       $('#btn-rotacion2').HideBubblePopup();
                       $('#btn-rotacion3').HideBubblePopup();
                       $('#btn-rotacion4').HideBubblePopup();
                       $('#btn-rotacion5').HideBubblePopup();
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {
                  
                }
         }
    });
}
function MTD_PREGUNTAR_ELIMINAR_BACKTONE()
{
    var contenido_pregunta="";
    var vl_nombre_tono="";
    vl_nombre_tono=$("#tb_nombre_tono").val();
    contenido_pregunta="<div style='height:70px; widht:60px; font-size:12px; color:gray;'> <span> Esta seguro de eliminar el backtone (<b>"+vl_nombre_tono+"</b>) ? </span> <br>  <div style='width=100%; text-align:center;'>   <input type='button' style='width:50px;cursor:pointer;' class='estilo-input submit' value='Si' onClick='javascript:MTD_ELIMINAR_BACKTONE()'/> &nbsp;&nbsp;&nbsp; <input type='button' style='width:50px;cursor:pointer;' class='estilo-input submit' " ;
    contenido_pregunta= contenido_pregunta + 'onClick="javascript:$(document).trigger(';
    contenido_pregunta= contenido_pregunta + "'close.facebox')";
    contenido_pregunta= contenido_pregunta + '"';
    contenido_pregunta= contenido_pregunta + " value='No' /> </div>";
    $.facebox(contenido_pregunta);

}
function MTD_ELIMINAR_BACKTONE()
{
    var vl_id_tono=0;
    var vl_nombre_tono="";
    vl_id_tono=$("#tb_id_tono").val();
    vl_nombre_tono=$("#tb_nombre_tono").val();
    var parametros = {operacion: "eliminar_backtone", tono: vl_id_tono};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                      $("#contenedor_administracion_backtones").html(data);
                      $(document).trigger('close.facebox');
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {

                }
         }
    });
}
function MTD_ASIGNAR_INTERVALO_HORAS_ROTACION()
{
    var vl_intervalo=0;

    vl_intervalo=$("#intervalo_horas").val();
    var parametros = {operacion: "asignar_intervalo_rotacion", intervalo: vl_intervalo};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                      $("#intervalo_horas").ShowBubblePopup();;
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {

                }
         }
    });
}
function MTD_ASIGNAR_BACKTONE_TURNO(vp_id_tono,vp_turno)
{
    
    var parametros = {operacion: "asignar_backtone_turno", tono: vp_id_tono, turno:vp_turno};
    $.ajax({
        url: "operaciones_ajax.php",
        data: parametros,
        type: "POST",
        cache: false,
        async: true,
        success: function(data) {
                if (data != "-1")
                {
                       $("#contenedor_administracion_backtones").html(data);
                }
                else  if (data == "-2")
                {
                    MTD_CERRAR_SESSION();
                }
                else
                {

                }
         }
    });    
}

function MTD_DESASIGNAR_BACKTONE_TURNO(vp_id_tono,vp_turno)
{
    var parametros = {operacion: "desasignar_backtone_turnosssssssss", tono: vp_id_tono, turno:vp_turno};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data != "-1")
                    {
                          // $("#contenedor_administracion_backtones").html(data);
                    }
                    else  if (data == "-2")
                    {
                        MTD_CERRAR_SESSION();
                    }
                    else
                    {

                    }
             }
        });
}
function MTD_ACTIVAR_BACKTONE_TONO_FIJO(vp_id_tono)
{
     var parametros = {operacion: "asignar_backtone_fijo", tono: vp_id_tono};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data != "-1")
                    {
                           $("#contenedor_administracion_backtones").html(data);
                    }
                    else  if (data == "-2")
                    {
                        MTD_CERRAR_SESSION();
                    }
                    else
                    {

                    }
             }
        });
}
function MTD_DESACTIVAR_BACKTONE_TONO_FIJO(vp_id_tono)
{
     var parametros = {operacion: "desasignar_backtone_fijosssssss", tono: vp_id_tono};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data != "-1")
                    {
                           //$("#contenedor_administracion_backtones").html(data);
                    }
                    else  if (data == "-2")
                    {
                        MTD_CERRAR_SESSION();
                    }                   
             }
        });
}
function MTD_ACTUALIZAR_DATOS_PERFIL()
{
    var vl_nombre   ="";
    var vl_apellido ="";
    var vl_nickname ="";
    var vl_sexo     ="";
    var vl_email    ="";

    vl_nombre   =$("#tb_nombre_usuario").val();
    vl_apellido =$("#tb_apellido_usuario").val();
    vl_nickname =$("#tb_nickname").val();
    vl_sexo     =$("#sexo").val();
    vl_email    =$("#tb_email_usuario").val();
    
    var parametros = {operacion: "actualizar_datos_perfil", nombre: vl_nombre, apellido: vl_apellido, nickname: vl_nickname, sexo: vl_sexo, email: vl_email};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "1")
                    {
                          $("#tb_actualizar_datos").ShowBubblePopup();;
                    }
                    else  if (data == "-2")
                    {
                        MTD_CERRAR_SESSION();
                    }                                        
                    else  if (data == "-3")
                    {
                        $("#tb_nickname").ShowBubblePopup();
                    }
                    else  if (data == "-4")
                    {
                        $("#tb_email_usuario").ShowBubblePopup();
                        
                    }
             }
        });
}
function MTD_ACTUALIZAR_PIN()
{
     var vl_msisdn="";
     var vl_pin="";
     vl_msisdn="";
     vl_msisdn= $("#tb_login_msisdn").val();     
     vl_pin= $("#tb_login_pin").val();

     var parametros = {operacion: "actualizar_pin_usuario", msisdn: vl_msisdn, pin: vl_pin}
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "1")
                    {                           
                          $('#tb_login_pin').SetBubblePopupOptions({
                                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                                   themeName: 'orange',
                                   innerHtml: 'En unos instantes recibiras un mensaje con tu pin de acceso.', mouseOver: 'hide'
                            });
                           $('#tb_login_pin').ShowBubblePopup();
                    }
                    else  if (data == "-1")
                    {
                          $('#tb_login_pin').SetBubblePopupOptions({
                                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                                   themeName: 'orange',
                                   innerHtml: 'Atencion, el numero telefonico ingresado no es valido, favor verificar', mouseOver: 'hide'
                            });
                           $('#tb_login_pin').ShowBubblePopup();
                    }
                    else  if (data == "-5")
                    {
                          $('#tb_login_pin').SetBubblePopupOptions({
                                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                                   themeName: 'orange',
                                   innerHtml: 'Atencion, debes esperar 5 minutos para actualizar tu pin nuevamente', mouseOver: 'hide'
                            });
                           $('#tb_login_pin').ShowBubblePopup();
                    }
             }
        });
}
function MTD_LISTAR_AMIGOS(vp_metodo)
{
    //myPlayList[i].name
    var vl_precio=0;
    vl_precio  = $("#tb_precio_regalo").val();    
    if (vl_precio  >= 0)
    {

        var vl_id_tono=0;
        vl_id_tono=$("#tb_id_tono").val();
        var parametros = {operacion: "popup_listar_amigos", tono: vl_id_tono, metodo: vp_metodo};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                    if (data == "-1")
                    {
                        $("#btn_loggin").ShowBubblePopup();
                    }
                    else if (data == "-2")
                    {
                        $('#tb_login_pin').CreateBubblePopup({
                               themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                               themeName: 'orange',
                               innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                         });
                         $("#tb_login_pin").ShowBubblePopup();

                    }
                    else if ((data != "-1"))
                    {
                      $("#depurador").html(data);
                      $.facebox(data);
                    }

                 }
        });
   }
   else
   {       
       if (vp_metodo == 'regalar')
       {
           $('#bt-regalar').SetBubblePopupOptions({
                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                   themeName: 'orange',
                   innerHtml: 'Atencion, el backtone seleccionado no posee la opcion '+ vp_metodo, mouseOver: 'hide'
            });
            $('#bt-regalar').ShowBubblePopup();

       }
       else
       {
            $('#bt-pedir').SetBubblePopupOptions({
                   themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                   themeName: 'orange',
                   innerHtml: 'Atencion, el backtone seleccionado no posee la opcion '+ vp_metodo, mouseOver: 'hide'
            });
            $('#bt-pedir').ShowBubblePopup();
      }

      
   }
}
function MTD_VOTAR_RANKING(vp_ranking)
{
    //myPlayList[i].name
    var vl_id_tono=0;
    var vl_ranking=0;
    vl_id_tono=$("#tb_id_tono").val();
    vl_ranking=vp_ranking;
    
     var parametros = {operacion: "votar_ranking", id_tono: vl_id_tono, ranking: vl_ranking};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                if (data == "-1")
                {
                    
                }
                else if (data == "-2")
                {
                    $('#tb_login_pin').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                     });
                     $("#tb_login_pin").ShowBubblePopup();

                }
                else if ((data == "0"))
                {                    
                  $('#tb_ranking').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Voto registado', mouseOver: 'hide'
                     });
                     $("#tb_ranking").ShowBubblePopup();
                }
                   
             }
        });
}
function MTD_VOTAR_MEGUSTA()
{    
    var vl_id_tono=0;    
    vl_id_tono=$("#tb_id_tono").val();    
     var parametros = {operacion: "votar_megusta", id_tono: vl_id_tono};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                if (data == "-1")
                {

                }
                else if (data == "-2")
                {
                    $('#tb_login_pin').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                     });
                     $("#tb_login_pin").ShowBubblePopup();

                }
                else if (data == "-3")
                {
                    $('#bt-megusta').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Usted ya ha registrado el gusto de este backtone anteriormente', mouseOver: 'hide'
                     });
                     $("#bt-megusta").ShowBubblePopup();

                }
                else if ((data == "0"))
                {
                    /*
                  $('#bt-megusta').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Voto registado', mouseOver: 'hide'
                     });
                     $("#bt-megusta").ShowBubblePopup();
                    */

                   MTD_MOSTRAR_MEGUSTA();
                }

             }
        });
}
function MTD_INGRESAR_COMENTARIO()
{
    var vl_id_tono=0;
    var vl_comentario="";
    vl_id_tono      =$("#tb_tono_comentario").val();
    vl_comentario   =$("#tb_ingresar_comentario").val();

     var parametros = {operacion: "ingresar_comentario", id_tono: vl_id_tono, comentario: vl_comentario};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
                if (data == "-1")
                {

                }
                else if (data == "-2")
                {
                    $('#tb_login_pin').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, debe ingresar en su cuenta <br> para realizar la adquisicion de un Backtone', mouseOver: 'hide'
                     });
                     $("#tb_login_pin").ShowBubblePopup();

                }
                else if (data == "-3")
                {
                    $('#btn_editar_perfil2').SetBubblePopupOptions({
                           themePath: 'estilos/tigo/images/jquerybubblepopup-theme/',
                           themeName: 'orange',
                           innerHtml: 'Atencion, usted debe poseer un nickname para ingresar un comentario, <br> ingrese aqui para actualizar su perfil', mouseOver: 'hide'
                     });
                     $("#btn_editar_perfil2").ShowBubblePopup();

                }
                else if ((data == "0"))
                {
                    $("#tb_ingresar_comentario").val('');
                    $("#contenedor-comentarios").fadeOut();
                    //ACTUALIZAR LA LISTA
                    MTD_MOSTRAR_COMENTARIOS();
                                     
                }

             }
        });
}
function MTD_MOSTRAR_RANKING()
{
    //myPlayList[i].name
    var vl_id_tono=0;    
    vl_id_tono=$("#tb_id_tono").val();
    
     var parametros = {operacion: "mostrar_ranking", id_tono: vl_id_tono};
     $.post("operaciones_ajax.php",parametros, function(data){
                $("#contenedor-ranking").html(data);
     });
     /*
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
         
            }
      });
      */
}
function MTD_MOSTRAR_REPRODUCCIONES()
{
    //myPlayList[i].name
    var vl_id_tono=0;
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_reproducciones", id_tono: vl_id_tono};
     $.post("operaciones_ajax.php",parametros, function(data){
                $("#contenedor-contador-reproducciones").html(data);
     });
     /*
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {
        
            }
      });
      */
}
function MTD_MOSTRAR_MEGUSTA()
{
    //myPlayList[i].name
    var vl_id_tono=0;
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_megusta", id_tono: vl_id_tono};
     
     $.post("operaciones_ajax.php",parametros, function(data){
                $("#contenedor-megusta-resultado").html(data);
     });
     
}
function MTD_MOSTRAR_COMENTARIOS()
{
    //myPlayList[i].name
    var vl_id_tono=0;
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_comentarios", id_tono: vl_id_tono};

     $.post("operaciones_ajax.php",parametros, function(data){
                $("#contenedor-comentarios-contenido").html(data);
     });
}
function MTD_MOSTRAR_TODOS_LOS_COMENTARIOS()
{
    //myPlayList[i].name
    var vl_id_tono=0;
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_todos_los_comentarios", id_tono: vl_id_tono};

     $.post("operaciones_ajax.php",parametros, function(data){
                $.facebox(data);
     });
}
function MTD_DENUNCIAR_COMENTARIO(vp_id_comentario)
{

    var vl_id_comentario="";
    vl_id_comentario= vp_id_comentario;

     var parametros = {operacion: "denunciar_comentario", id_comentario: vl_id_comentario};
        $.ajax({
            url: "operaciones_ajax.php",
            data: parametros,
            type: "POST",
            cache: false,
            async: true,
            success: function(data) {                
                    MTD_MOSTRAR_COMENTARIOS();               
             }
        });
}

function MTD_MOSTRAR_IMAGEN_ALBUM()
{
    var vl_id_tono=0;
    var imagen_anterior="";
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_imagen_portada", id_tono: vl_id_tono};

     $.post("operaciones_ajax.php",parametros, function(data){
        imagen_anterior= $('#imagen-cover-album-portada').attr('src');        
        if (imagen_anterior != data)
        {            
         $('#imagen-cover-album-portada').fadeOut(function(){
                var objImagePreloader = new Image();
                objImagePreloader.onload = function() {
                        $('#imagen-cover-album-portada')
                            .removeAttr('src')
                            .attr('src',data)
                            .fadeIn();
                }
                objImagePreloader.src = data;
            });
        }
     });
}

function MTD_MOSTRAR_NOMBRE_ARTISTA()
{
    var vl_id_tono=0;    
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_nombre_artista", id_tono: vl_id_tono};

     $.post("operaciones_ajax.php",parametros, function(data){
        $('#contenido-nombre-artista').html(data);        
     });
}

function MTD_MOSTRAR_NOMBRE_GENERO()
{
    var vl_id_tono=0;    
    vl_id_tono=$("#tb_id_tono").val();

     var parametros = {operacion: "mostrar_nombre_genero", id_tono: vl_id_tono};

     $.post("operaciones_ajax.php",parametros, function(data){
        $('#contenido-nombre-genero').html(data);
     });
}


function MTD_MOSTRAR_LINK_FACEBOOK()
{

   window.open('http://www.facebook.com/sharer.php?u={tpl-url}&t={tpl-titulo-url}','ventanacompartir', 'toolbar=0, status=0, width=550, height=450');
}
function MTD_MOSTRAR_POPUP_FACEBOOK()
{    
    var vl_id_tono=0;
    vl_id_tono=$("#tb_id_tono").val();
    var timestamp = Number(new Date());
    window.open('http://www.facebook.com/sharer.php?u=html%3A%2F%2Fbacktones.tigo.com.bo%2Fv3%2Findex.php%3Fseccion%3Dbacktones%26backtone%3D'+vl_id_tono +'%26f%3D1%26tm%3D'+timestamp+'&t=','ventanacompartir', 'toolbar=0, status=0, width=550, height=450');
}
