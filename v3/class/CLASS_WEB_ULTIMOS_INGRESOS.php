<?php
class CLASS_WEB_ULTIMOS_INGRESOS
{
    private $vlc_codigo_html;
    private $vlc_codigo_html_tonos;

    function __construct()
    {
        $this->vlc_codigo_html="";
        $vl_lista_tonos="";
        $vl_arreglo_datos=array();
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-ultimos-ingresados.html');
        $this->vlc_codigo_html_tonos= FN_LEER_TPL('tpl/tpl-ultimos-ingresados-tonos.html');

        //TONOS DESCARGADOS
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_TONOS();
        $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-tonos}', $vl_lista_tonos, $this->vlc_codigo_html);

        /*
         *
         * <dd><a href='#genero' >Alternative</a></dd>
           <dd><a href='albums.php?accion=genero&idgenero=62' class="light-blue">Bluegrass</a></dd>
         * TODO:
         */
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {
        // t.id, t.nombre as tono,aut.nombre as autor, alb.imagen


        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        $vl_template_tonos= "";
        $vl_tonos="<div><div id='contenedor-albumes'>";
        while ($vl_contador < $vl_cantidad_registros)
        {

            //LIMPIEZA DE VARIABLES

            $vl_template_tonos= "";
            $id_tono=0;
            $tono="";
            $autor="";
            $id_autor="";
            $imagen_album="";
            $vl_posicion= $vl_contador +1;
            //ASIGNACION DE VARIABLES
            $vl_template_tonos= $this->vlc_codigo_html_tonos;
            $id_tono=$vp_arreglo_datos[$vl_contador][0];
            $tono=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][1])));
            $id_autor= $vp_arreglo_datos[$vl_contador][4];
            $titulo_tono=$tono;

            $autor=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][2])));            
            $titulo_autor=$autor;

            $imagen_album=$vp_arreglo_datos[$vl_contador][3];
            if ($imagen_album == "")
            {
                    $imagen_album="th-album-default.jpg";
            }
            $path_imagen_album="albumes/".$imagen_album;
            if (is_file($path_imagen_album) == false)
            {
                $imagen_album="th-album-default.jpg";
            }
            $link_reproducir="index.php?seccion=backtones&backtone=$id_tono";
            $link_artista="index.php?seccion=busqueda&autor=$id_autor";
            $link_compra="#link-compra-$id_tono";
            $link_regalo="#link-regalo-$id_tono";
            $link_descarga="#link-desacarga-$id_tono";
            $link_pedir="#link-pedir-$id_tono";
            $link_tono="index.php?seccion=backtones&backtone=$id_tono";
            $vl_template_ranking = FN_WEB_ESTRELLAS($vp_arreglo_datos[$vl_contador][5]);
            $tono=FN_STRING_LIMITE($tono, 26);

            $autor=FN_STRING_LIMITE($autor, 26);

             /*
                {tpl-tono-imagen-album}
                {tpl-tono-artista}
                {tpl-tono-link-artista}
                {tpl-tono-link}
                {tpl-tono-nombre}
                {tpl-tono-link-reproducir}
                {tpl-tono-link-comprar}
                {tpl-tono-link-regalar}
                {tpl-tono-link-pedir}
             */
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-imagen-album}' , $imagen_album, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-artista}'      , $autor, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-artista}' , $link_artista, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-nombre}'       , $tono, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-reproducir}'  , $link_reproducir, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-comprar}' , $link_compra, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-regalar}' , $link_regalo, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-ingresar}'   , $link_tono, $vl_template_tonos);
            $vl_template_tonos= FN_REEMPLAZAR('{tpl-ranking}'   , $vl_template_ranking, $vl_template_tonos);


            if (($vl_contador % 8 == 0) && ( $vl_contador >=1))
            {
                $vl_tonos.="</div><!-- CONTENEDOR !-->
                            </div><!-- ITEMS !-->
                            <div><!-- ITEMS $vl_posicion !-->
                            <div id='contenedor-albumes'>";

            }
            $vl_tonos .= $vl_template_tonos;

            $vl_contador++;
        }
        $vl_tonos .="</div></div>";
        return $vl_tonos ;
    }
    function MTD_DB_LISTAR_TONOS()
    {


        global $vg_db_conexion,$vg_conf_filtro_web;
        
        $vlf_sql = "SELECT 
                    t.id,
                    t.nombre as tono,
                    aut.nombre as autor,
                    alb.imagen,
                    t.autor as id_autor,
                    round(avg(wr.calificacion),0) as calificacion
                    FROM tonos as t
                    inner join autores as aut on ( t.autor = aut.id)
                    left join albumes as alb on (t.album = alb.id_album)
                    left join web_tonos_ranking as wr on (t.id = wr.tonos_id)
                    WHERE
                    t.id > 0
                    $vg_conf_filtro_web
                    group by t.id, t.nombre ,aut.nombre, alb.imagen,t.autor
                    order by t.id desc
                    limit 24";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6,$vg_db_conexion);
        //FN_NET_LOGGER("Ultimos Ingresos: SQL:$vlf_sql ");
        return $vlf_arreglo_datos;
    }    
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>
