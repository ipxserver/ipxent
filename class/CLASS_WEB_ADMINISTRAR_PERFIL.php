<?php
class CLASS_WEB_ADMINISTRAR_PERFIL
{
    private $vlc_codigo_html;
    private $vlc_msisdn;
    private $vlc_imagen_usuario;

    function __construct()
    {
        $this->vlc_codigo_html="";
        $this->vlc_msisdn="";
        $this->vlc_imagen_usuario="";
        $this->vlc_msisdn= $_SESSION['msisdn'];        
        FN_NET_LOGGER("Inicio de administracion perfil");
        $vlp_archivo= "recurso";


        $status = "";
        if ($_POST["action"] == "upload")
        {
            $this->MTD_ACTUALIZAR_IMAGEN();
        }
           
    }
  
    function MTD_ACTUALIZAR_IMAGEN()
    {
        global $vg_db_conexion;

        $vl_resultado=false;
        FN_NET_LOGGER("Actualizar Imagen > msisdn: ".$this->vlc_msisdn);
        $vl_resultado= $this->MTD_RECIBIR_IMAGEN();
        if ($vl_resultado == true)
        {
              //VERIFICA SI EXISTE EL REGISTRO
              $vl_sql="select msisdn from usuarios_datos_personales where msisdn = '".$this->vlc_msisdn."'";
              $vl_arreglo=array();
              $vl_arreglo =FN_RUN_QUERY($vl_sql, 1,$vg_db_conexion);
              if ($vl_arreglo)
              {
                  $vl_sql= "UPDATE usuarios_datos_personales set imagen='".$this->vlc_imagen_usuario."' where msisdn='".$this->vlc_msisdn."'";
                  FN_NET_LOGGER("Actualizar Imagen > msisdn: ".$this->vlc_msisdn," sql:$vl_sql");
                  $vl_resultado=false;
                  $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
              }
              else
              {
                  $vl_sql= "INSERT INTO usuarios_datos_personales (msisdn,imagen) values('".$this->vlc_msisdn."','".$this->vlc_imagen_usuario."');";
                  FN_NET_LOGGER("Agregar Imagen > msisdn: ".$this->vlc_msisdn," sql:$vl_sql");
                  $vl_resultado=false;
                  $vl_resultado = FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
              }

        }
        else
        {
            FN_NET_LOGGER("Actualizar Imagen > msisdn: ".$this->vlc_msisdn," Error al subir el archivo:$vl_resultado ");
        }

    }
    function MTD_RECIBIR_IMAGEN()
    {     
        $imagen_path = "imagenes/temp/";
        $target_path = "";
        FN_NET_LOGGER("RECIBIR_IMAGEN> msisdn:".$this->vlc_msisdn."");
        $target_path = $imagen_path . basename( $_FILES['tb_imagen_usuario']['name']);

        if(move_uploaded_file($_FILES['tb_imagen_usuario']['tmp_name'], $target_path)) {
            FN_NET_LOGGER("RECIBIR_IMAGEN> msisdn:".$this->vlc_msisdn." archivo".  basename( $_FILES['tb_imagen_usuario']['name'])." uploaded");
            include ('class/CLASS_THUMBNAIL.php');
            $thumb=new Thumbnail($target_path);	        // set source image file

            //$thumb->size_width(48);				    // set width for thumbnail, or
            //$thumb->size_height(*);				    // set height for thumbnail, or
            $thumb->size_auto(50);			// set the biggest width or height for thumbnail

            $thumb->quality=100;                        //default 75 , only for JPG format
            $thumb->output_format='JPG';               // JPG | PNG
            $thumb->jpeg_progressive=1;                // set progressive JPEG : 0 = no , 1 = yes
            //$thumb->allow_enlarge=false;               // allow to enlarge the thumbnail
            //$thumb->CalculateQFactor(10000);           // Calculate JPEG quality factor for a specific size in bytes
            //$thumb->bicubic_resample=true;             // [OPTIONAL] set resample algorithm to bicubic

            /*
            //$thumb->img_watermark='watermark.png';	    // [OPTIONAL] set watermark source file, only PNG format [RECOMENDED ONLY WITH GD 2 ]
            //$thumb->img_watermark_Valing='TOP';   	    // [OPTIONAL] set watermark vertical position, TOP | CENTER | BOTTOM
            //$thumb->img_watermark_Haling='LEFT';   	    // [OPTIONAL] set watermark horizonatal position, LEFT | CENTER | RIGHT

            //$thumb->txt_watermark='Watermark text';	    // [OPTIONAL] set watermark text [RECOMENDED ONLY WITH GD 2 ]
            //$thumb->txt_watermark_color='000000';	    // [OPTIONAL] set watermark text color , RGB Hexadecimal[RECOMENDED ONLY WITH GD 2 ]
            //$thumb->txt_watermark_font=1;	            // [OPTIONAL] set watermark text font: 1,2,3,4,5
            //$thumb->txt_watermark_Valing='TOP';   	    // [OPTIONAL] set watermark text vertical position, TOP | CENTER | BOTTOM
            //$thumb->txt_watermark_Haling='LEFT';       // [OPTIONAL] set watermark text horizonatal position, LEFT | CENTER | RIGHT
            $thumb->txt_watermark_Hmargin=10;          // [OPTIONAL] set watermark text horizonatal margin in pixels
            $thumb->txt_watermark_Vmargin=10;           // [OPTIONAL] set watermark text vertical margin in pixels
             *
             */
            $thumb->process();   				        // generate image

            //$thumb->show();
            //				        // show your thumbnail, or

            $imagen_path = "imagenes/usuarios/";
            $estampa= time();
            $archivo_upload= $this->vlc_msisdn."-".$estampa.".jpg";
            $archivo_final=$imagen_path.$archivo_upload;
            
            //ELIMINAR EL ARCHIVO FINAL (SI EXISTE PREVIAMENTE)
            $vl_arreglo_datos= $this->MTD_DB_LISTAR_DATOS_PERSONALES();
            $vl_imagen = $vl_arreglo_datos[0][6];
            $archivo_borrar=$imagen_path.$vl_imagen;
            FN_NET_LOGGER("RECIBIR_IMAGEN> msisdn:".$this->vlc_msisdn." Borrando archivo:$archivo_borrar");
            unlink($archivo_borrar);
            $thumb->save($archivo_final);			    // save your thumbnail to file, or

            FN_NET_LOGGER("RECIBIR_IMAGEN> msisdn:".$this->vlc_msisdn." Archivo final:$archivo_final ");
            $image = $thumb->dump();                    // get the image
            //
            //
            //ELIMINAR EL ARCHIVO TEMPORAL
            unlink($target_path);
            $this->vlc_imagen_usuario=$archivo_upload;
            return true;
        } 
        else
        {
            FN_NET_LOGGER("RECIBIR_IMAGEN> msisdn:".$this->vlc_msisdn." archivo, error al subir el archivo ");            
        }
        
       
        //echo ($thumb->error_msg);                   // print Error Mensage
        
    }
    function MTD_APLICAR_TEMPLATE()
    {
        $vl_arreglo_datos= $this->MTD_DB_LISTAR_DATOS_PERSONALES();        
        $vl_nombre  = $vl_arreglo_datos[0][1];
        $vl_apellido= $vl_arreglo_datos[0][2];
        $vl_email   = $vl_arreglo_datos[0][3];
        $vl_sexo    = $vl_arreglo_datos[0][4];
        $vl_nickname= $vl_arreglo_datos[0][5];
        $vl_imagen  = $vl_arreglo_datos[0][6];

        if ($vl_imagen =="")
        {
            $vl_imagen ="imagenes/usuarios/imagen-usuarios-default.png";
        }
        else
        {
            $vl_imagen ="imagenes/usuarios/".$vl_arreglo_datos[0][6];
        }


       

        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-administrar-perfil.html');

        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-nombre}', $vl_nombre, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-apellido}', $vl_apellido, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-email}', $vl_email, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-imagen-usuario}', $vl_imagen, $this->vlc_codigo_html);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-nickname}', $vl_nickname, $this->vlc_codigo_html);
        if ($vl_sexo == "M")
        {
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-masculino}', 'checked', $this->vlc_codigo_html);
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-femenino}',  '', $this->vlc_codigo_html);
        }
        elseif ($vl_sexo == "F")
        {
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-femenino}', 'checked', $this->vlc_codigo_html);
            $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-masculino}','', $this->vlc_codigo_html);
        }                
        
    }
    function MTD_DB_LISTAR_DATOS_PERSONALES()
    {
        global $vg_db_conexion;
        $vlf_sql = "
        SELECT
            msisdn,
            nombre,
            apellido,
            email,
            sexo,
            nickname,
            imagen
        FROM usuarios_datos_personales
        WHERE 	msisdn='".$this->vlc_msisdn."';";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql,7,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_CHECK_NICKNAME($vp_nickname)
    {
        global $vg_db_conexion;
        $vlf_sql = "
        SELECT
            nickname,
            msisdn
        FROM usuarios_datos_personales
        WHERE 	nickname='$vp_nickname' and msisdn <>'".$this->vlc_msisdn."';";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2,$vg_db_conexion);
        if (!($vlf_arreglo_datos))
        {
            FN_NET_LOGGER("DB_CHECK_NICKNAME > nickname: $vp_nickname  msisdn: $this->vlc_msisdn > OK no se encontro otro nickname igual");
            return true;
        }
        else
        {
            FN_NET_LOGGER("DB_CHECK_NICKNAME > nickname: $vp_nickname  msisdn: $this->vlc_msisdn > Atencion se encontro otro nickname igual");
            return false;
        }
    }
    
    function MTD_RETORNAR_CODIGO_HTML()
    {
        if ($this->vlc_codigo_html == "")
        {
            $this->MTD_APLICAR_TEMPLATE();
        }
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    
    function MTD_REQUERIMIENTOS_ENCABEZADO()
    {
        return '
        <link href="estilos/tigo/styles/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <link href="estilos/tigo/styles/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/facebox.js" ></script>
        ';

    }        
    function MTD_ACTUALIZAR_DATOS_PERFIL($vp_nombre, $vp_apellido, $vp_nickname, $vp_sexo, $vp_email)
    {
        global $vg_db_conexion;
        $vl_resultado = false;
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_DATOS_PERSONALES();
        if ($this->MTD_DB_CHECK_NICKNAME($vp_nickname) == true)
        {
            if ($vp_email != "")
            {
                if($this->FN_CHECK_EMAIL($vp_email) == false)
                {
                    FN_NET_LOGGER("ACTUALIZAR_DATOS_PERFIL > Email invalido: $vp_email ");
                    return "-4";
                }
            }
            if (!$vl_arreglo_datos)
            {
                $vl_sql="INSERT INTO usuarios_datos_personales
                    (msisdn,nombre,apellido,nickname,sexo,email) VALUES
                    ('".$this->vlc_msisdn."','$vp_nombre','$vp_apellido','$vp_nickname','$vp_sexo','$vp_email');";
                FN_NET_LOGGER("ACTUALIZAR_DATOS_PERFIL > sql:$vl_sql ");
                $vl_resultado= FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
                return $vl_resultado;
            }
            else
            {
                $vl_sql="UPDATE usuarios_datos_personales
                    set
                        nombre= '$vp_nombre',
                        apellido= '$vp_apellido',
                        nickname='$vp_nickname',
                        sexo='$vp_sexo',
                        email='$vp_email'
                        WHERE
                        msisdn='".$this->vlc_msisdn."'";
                FN_NET_LOGGER("ACTUALIZAR_DATOS_PERFIL > sql:$vl_sql ");
                $vl_resultado= FN_RUN_NONQUERY($vl_sql, $vg_db_conexion);
                return $vl_resultado;
            }

        }
        else
        {
            return "-3";
        }
        
    }
    function FN_CHECK_EMAIL($vp_email)
    {
        /*
        $email = $vp_email;
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])
            ↪*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",  $email)){
            list($username,$domain)=split('@',$email);
            if(!checkdnsrr($domain,'MX')) {
              return false;
            }
            return true;
            }
            
         *
         *
         */
       if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $vp_email))
       {
           return true;
       }
       else
       {
          return false;
       }
    
    }
}
?>