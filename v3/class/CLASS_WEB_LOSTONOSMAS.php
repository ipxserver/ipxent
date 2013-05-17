<?php
class CLASS_WEB_LOSTONOSMAS
{
    private $vlc_codigo_html;
 
    function __construct()
    {
        $this->vlc_codigo_html="";
        $vl_lista_tonos="";
        $vl_arreglo_datos=array();
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-lista-tonos-losmas.html');

        //TONOS DESCARGADOS
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_TONOSMAS_DESCARGADOS();
        $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos,"votados");
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-tono-mas-descargados}', $vl_lista_tonos, $this->vlc_codigo_html);

        //TONOS RANKEADOS
        $vl_lista_tonos="";
        $vl_arreglo_datos=array();
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_TONOSMAS_RANKEADOS();
        $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos,"rankeados");
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-tono-mas-rankeados}', $vl_lista_tonos, $this->vlc_codigo_html);

        //TONOS COMPRADOS
        $vl_lista_tonos="";
        $vl_arreglo_datos=array();
        $vl_arreglo_datos = $this->MTD_DB_LISTAR_TONOSMAS_COMPRADOS();
        $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos,"comprados");
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-lista-tono-mas-comprados}', $vl_lista_tonos, $this->vlc_codigo_html);
        /*
         *
         * <dd><a href='#genero' >Alternative</a></dd>
           <dd><a href='albums.php?accion=genero&idgenero=62' class="light-blue">Bluegrass</a></dd>
         * TODO:
         */
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos,$vp_clase)
    {

        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        $vl_lista_tonos="";
        $tono="";
        $autor="";
        $id_autor="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            //Hot Tottie - Usher</a></span></li>
            $id_tono=$vp_arreglo_datos[$vl_contador][0];
            $id_autor=$vp_arreglo_datos[$vl_contador][3];
            $tono=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][1])));
            $titulo_tono=$tono;


            $autor=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][2])));
            $titulo_autor=$autor;

            $tono=FN_STRING_LIMITE($tono, 25);
            $autor=FN_STRING_LIMITE($autor, 25);

            $vl_lista_tonos.="<li class='listatonos-losmas-$vp_clase'>
                <div class='".$vp_clase."'></div>
                <div class='tonos-losmas' >
                <a href='index.php?seccion=backtones&backtone=$id_tono' title='$titulo_tono' class='tonos'>$tono</a>
                <a href='index.php?seccion=playlist&autor=$id_autor' class='artist' title='$titulo_autor'>".$autor."</a>
                 </div>
                 
                </li>";
            //$vl_lista_tonos.="<dd><a href='#link-genero-id".$vp_arreglo_datos[$vl_contador][0]."'>".ucfirst(strtolower($vp_arreglo_datos[$vl_contador][1]))."</a></dd>";
            $vl_contador++;
        }
        return $vl_lista_tonos;
    }
    function MTD_DB_LISTAR_TONOSMAS_DESCARGADOS()
    {
        /*
         * TODO: CORREGIR SQL QUE TRAE TONOS MAS DESCARGADOS
         */
        global $vg_db_conexion,$vg_conf_filtro_web;
         $vlf_sql = "SELECT t.id, t.nombre,a.nombre, t.autor, avg(wr.calificacion),count(wr.msisdn)
            FROM tonos as t, autores as a,web_tonos_ranking as wr
            where
            t.autor = a.id
            AND t.id = wr.tonos_id
            $vg_conf_filtro_web            
            group by t.id, t.nombre,a.nombre
            order by  avg(wr.calificacion) desc, count(wr.msisdn) desc
            limit 10 ";
           // FN_NET_LOGGER("Los mas Rankeados> SQL: $vlf_sql ");
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR_TONOSMAS_RANKEADOS()
    {
        /*
         * TODO: CORREGIR SQL QUE TRAE TONOS MAS DESCARGADOS
         */
        global $vg_db_conexion,$vg_conf_filtro_web;
         $vlf_sql = "SELECT t.id, t.nombre,a.nombre,t.autor
            FROM tonos as t, autores as a
            where
            t.autor = a.id
            AND t.categoria <> 207
            order by rand()
            limit 10 ";
        
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 4,$vg_db_conexion);
  
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR_TONOSMAS_COMPRADOS()
    {
        /*
         * TODO: CORREGIR SQL QUE TRAE TONOS MAS DESCARGADOS
         */
        global $vg_db_conexion,$vg_conf_filtro_web;
        $vlf_sql = "SELECT t.id, t.nombre,a.nombre,t.autor
            FROM tonos as t, autores as a, top10 as top
            where
            t.autor = a.id
            AND t.id = top.id
            $vg_conf_filtro_web            
            limit 10 ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 4,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>
