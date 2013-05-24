<?php
class CLASS_WEB_REPRODUCIR_BACKTONES
{
    private $vlc_codigo_html;
    private $vlc_id_tono;
    private $vlc_nombre_tono;
    private $vlc_lista_tonos;
    private $vlc_id_artista;
    private $vlc_nombre_artista;
    private $vlc_id_album;
    private $vlc_nombre_album;
    private $vlc_id_genero;
    private $vlc_nombre_genero;
    private $vlc_imagen_portada;
    private $vlc_imagen_facebook;
    private $vlc_titulo_head;
    function __construct()
    {
        $this->vlc_codigo_html  = "";
        $this->vlc_id_tono      = "";
        $this->vlc_nombre_tono  = "";
        $this->vlc_lista_tonos  = "";
        $this->vlc_id_artista   = "";
        $this->vlc_nombre_artista  = "";
        $this->vlc_id_album     = "";
        $this->vlc_nombre_album = "";
        $this->vlc_id_genero    = "";
        $this->vlc_nombre_genero= "";
        $this->vlc_imagen_portada="";
        $this->vlc_imagen_facebook="";
        $vl_lista_tonos         = "";
        $this->vlc_titulo_head  = "";

        global $vg_conf_dominio;

        $vl_arreglo_datos       = array();

        $vl_flag_facebook =  FN_RECIBIR_VARIABLES('f');
        if ($vl_flag_facebook == 1)
        {
            $this->vlc_codigo_html  = FN_LEER_TPL('tpl/tpl-reproducir-backtones-facebook.html');
        }
        else
        {
            $this->vlc_codigo_html  = FN_LEER_TPL('tpl/tpl-reproducir-backtones.html');
        }
        
        //TONOS DESCARGADOS
        $this->vlc_id_tono      = FN_RECIBIR_VARIABLES('backtone');
        
        $vl_arreglo_tonos       = $this->MTD_DB_LISTAR_TONOS();        
        $this->vlc_lista_tonos  = $this->MTD_FORMATEAR_LISTA_TONOS($vl_arreglo_tonos);
        $vl_posicion_escuchar   = $this->MTD_BUSCAR_ITEM_ESCUCHAR($vl_arreglo_tonos);

        //FACEBOOK
        $this->MTD_DB_OBTENER_INFO_TONO();
        $template_portada_album="albumes/".trim($this->vlc_imagen_portada);        
        if ($template_portada_album == "albumes/")
        {
            $template_portada_album="albumes/album-default.jpg";
        }
        $template_imagen_facebook ="albumes/".trim($this->vlc_imagen_facebook);
        if ($template_imagen_facebook == "albumes/")
        {
            $template_imagen_facebook="albumes/th-album-default.jpg";
        }

        $this->vlc_imagen_facebook="$vg_conf_dominio"."/v3/".$template_imagen_facebook;
        $url=urlencode("$vg_conf_dominio/v3/index.php?seccion=backtones&backtone=$this->vlc_id_tono&f=1");
        $this->vlc_titulo_head="Ringback Tone Entel | $this->vlc_nombre_artista - $this->vlc_nombre_tono ";
        $titulo_url="";

        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-listado-backtones}', $this->vlc_lista_tonos, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-id-tema-escuchar}',$vl_posicion_escuchar, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-imagen-album}',$template_portada_album, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-id-artista}',$this->vlc_id_artista, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-id-genero}',$this->vlc_id_genero, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-nombre-artista}',$this->vlc_nombre_artista, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-nombre-genero}',$this->vlc_nombre_genero, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-url}',$url, $this->vlc_codigo_html);
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-titulo-url}',$titulo_url, $this->vlc_codigo_html);

        $template_album="";
        if (($this->vlc_id_album != "") && ($this->vlc_nombre_album != ""))
        {
            $template_album='<span>Album: <a href="index.php?seccion=albumes&album='.$this->vlc_id_album.'">'.$this->vlc_nombre_album.'</a></span><br />';
        }
        $this->vlc_codigo_html  = FN_REEMPLAZAR('{tpl-album}',$template_album, $this->vlc_codigo_html);


    }
    function MTD_DB_OBTENER_INFO_TONO()
    {
        global $vg_db_conexion, $vg_conf_filtro_web ;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path,
	aut.id as id_autor,
	aut.nombre as autor,
	alb.id_album,
	alb.nombre as album,
        alb.imagen_portada,
	gen.id_genero,
	gen.nombre as genero,
        alb.imagen
        FROM tonos as t
	inner join autores as aut ON (t.autor = aut.id)
	left join albumes as alb ON (t.album = alb.id_album)
	left join generos as gen ON (t.genero = gen.id_genero)
        WHERE
	t.id=".$this->vlc_id_tono." $vg_conf_filtro_web ;";
 
       
        $vlf_arreglo_datos          = FN_RUN_QUERY($vlf_sql, 11,$vg_db_conexion);
        $this->vlc_id_artista       = $vlf_arreglo_datos [0][3];
        $this->vlc_nombre_tono      = $vlf_arreglo_datos [0][1];
        $this->vlc_nombre_artista   = $vlf_arreglo_datos [0][4];
        $this->vlc_id_album         = $vlf_arreglo_datos [0][5];
        $this->vlc_nombre_album     = $vlf_arreglo_datos [0][6];
        $this->vlc_imagen_portada   = $vlf_arreglo_datos [0][7];
        $this->vlc_id_genero        = $vlf_arreglo_datos [0][8];
        $this->vlc_nombre_genero    = $vlf_arreglo_datos [0][9];
        $this->vlc_imagen_facebook  = $vlf_arreglo_datos [0][10];
        return true;
    }
    function MTD_DB_LISTAR_TONOS()
    {
        /*
         * TODO: CORREGIR SQL QUE TRAE TONOS MAS DESCARGADOS
         */
        global $vg_db_conexion,$vg_conf_filtro_web;
        $vlf_sql = "SELECT
	t.id ,
	t.nombre,
	t.path
        FROM tonos as t, autores as a
        WHERE
	t.autor = a.id	
	$vg_conf_filtro_web
	AND t.autor = (select autor from tonos where id= ".$this->vlc_id_tono.")
	AND t.album = (select album from tonos where id=".$this->vlc_id_tono.");";
        
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        
        if (!($vlf_arreglo_datos))
        {
            $vlf_sql = "SELECT
            t.id ,
            t.nombre,
            t.path
            FROM tonos as t, autores as a
            WHERE
            t.autor = a.id
            $vg_conf_filtro_web
            AND t.autor = (select autor from tonos where id= ".$this->vlc_id_tono.");";
            //echo "<h1>QUE BOLA:[$vlf_sql]<h1><br>";
            $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 3,$vg_db_conexion);
        }
        return $vlf_arreglo_datos;
    }  
    function MTD_FORMATEAR_LISTA_TONOS($vp_arreglo_tonos)
    {
        global $vg_conf_path_tonos,$vg_conf_simular_tonos;
        $vl_cantidad_tonos=sizeof($vp_arreglo_tonos);
        $vl_contador=0;
        $vl_lista_tonos="";
        $vl_id_tono="";
        $vl_nombre_tono="";
        $vl_path_tono="";
        $vl_coma_final=",";
        //{name:"No  me dejes solo",mp3:"mp3/tono1.mp3",ogg:"mp3/tono1.mp3",id:"116"},
        //{name:"tono19",mp3:"mp3/tono19.mp3",ogg:"mp3/tono19.mp3",id:"134"}

        while( $vl_contador < $vl_cantidad_tonos)
        {
            $vl_id_tono     =$vp_arreglo_tonos[$vl_contador][0];
            $vl_nombre_tono =FN_REEMPLAZAR('"','', $vp_arreglo_tonos[$vl_contador][1]);
            //$vl_path_tono   =$vp_arreglo_tonos[$vl_contador][2];
            if ($vg_conf_simular_tonos == true)
            {
                $vl_path_tono   ="$vg_conf_path_tonos/tono".$vl_contador.".mp3";
            }
            else
            {

                   $vl_path_tono   =$vg_conf_path_tonos."/".$vp_arreglo_tonos[$vl_contador][2].".mp3";
                   //$vl_path_tono = urlencode($vl_path_tono);
            }

            $vl_lista_tonos.='{name:"'.$vl_nombre_tono.'",mp3:"'.$vl_path_tono.'",ogg:"'.$vl_path_tono.'",id:"'.$vl_id_tono.'"}';

            if ($vl_contador != ($vl_cantidad_tonos -1))
            {                
                $vl_lista_tonos.=$vl_coma_final;
            }
            $vl_contador++;
        }       
        return $vl_lista_tonos;
    }
    function MTD_BUSCAR_ITEM_ESCUCHAR($vp_arreglo_tonos)
    {
        $vl_cantidad_tonos=sizeof($vp_arreglo_tonos);
        $vl_contador=0;
        $vl_posicion=0;
        $vl_id_tono =0;
        //{name:"No  me dejes solo",mp3:"mp3/tono1.mp3",ogg:"mp3/tono1.mp3",id:"116"},
        //{name:"tono19",mp3:"mp3/tono19.mp3",ogg:"mp3/tono19.mp3",id:"134"}

        while( $vl_contador < $vl_cantidad_tonos)
        {
            $vl_id_tono     =$vp_arreglo_tonos[$vl_contador][0];                        

            if ($vl_id_tono == $this->vlc_id_tono)
            {
                $vl_posicion=$vl_contador;
                return $vl_posicion;
            }
            $vl_contador++;
        }
        return $vl_posicion;
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }
    function MTD_RETORNAR_ENCABEZADO()
    {
        return $this->vlc_titulo_head;
        //return  $this->vlc_codigo_menues;
    }

    function MTD_REQUERIMIENTOS_ENCABEZADO()
    {
        //$imagen_facebook="http://www.ss7solutions.com/webrbt/webrbt/albumes/album-default.jpg";
        return '
        <link rel="image_src" href="'.$this->vlc_imagen_facebook.'" />
        <meta property="og:title" content="'.$this->vlc_titulo_head.'" />
        <meta property="og:image" content="'.$this->vlc_imagen_facebook.'" />        
        ';
    }
    function MTD_REQUERIMIENTOS_ENCABEZADOS_SECUNDARIOS()
    {
        return '<link href="estilos/tigo/styles/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <link href="estilos/tigo/styles/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
        <link href="estilos/tigo/styles/jquery.ui.stars.css" media="screen" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
        <script type="text/javascript" src="js/facebox.js" ></script>
        <script type="text/javascript" src="js/jquery.ui.stars.js" ></script> ';
    }
}
?>
