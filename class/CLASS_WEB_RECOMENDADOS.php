<?php
class CLASS_WEB_RECOMENDADOS
{
    private $vlc_codigo_html;    
    function __construct()
    {
        $this->vlc_codigo_html="";
        $vl_lista_recomendados ="";
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-recomendados.html');;

        $vl_arreglo_datos = $this->MTD_DB_LISTAR_RECOMENDADOS();
        $vl_lista_recomendados = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos);
        $this->vlc_codigo_html= FN_REEMPLAZAR('{tpl-tonos-recomendados}',$vl_lista_recomendados, $this->vlc_codigo_html);
        
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {

        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        
        $template="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $id_tono            =$vp_arreglo_datos[$vl_contador][0];
            $titulo_nombre_tono =$vp_arreglo_datos[$vl_contador][1];
            $nombre_tono        =FN_STRING_LIMITE($titulo_nombre_tono,25);
            $id_autor           =$vp_arreglo_datos[$vl_contador][2];
            $titulo_nombre_autor =$vp_arreglo_datos[$vl_contador][3];
            $nombre_autor        =FN_STRING_LIMITE($titulo_nombre_autor,25);
            $id_categoria       =$vp_arreglo_datos[$vl_contador][4];
            $nombre_categoria   =$vp_arreglo_datos[$vl_contador][5];
            $link_tono          ="index.php?seccion=backtones&backtone=$id_tono";


            $estilo="";
            if ($vl_contador % 2 == 0)
            {
                $estilo="class='light-blue'";
            }
            $template.="
             <dd $estilo>
                <ul>
                        <li><a href='$link_tono' title='$titulo_nombre_tono'>$nombre_tono</a></li>
                        <li><span><a href='index.php?seccion=playlist&autor=$id_autor' class='artist' title='$titulo_nombre_autor'>$nombre_autor</a></span></li>
                </ul>
            </dd>";
            $vl_contador++;
        }
        return $template;
    }
    function MTD_DB_LISTAR_RECOMENDADOS ()
    {
        global $vg_db_conexion;
        $vlf_sql = "
            SELECT
                b.id     as id_tono,
                b.nombre as nombre_tono,
                c.id     as id_autor,
                c.nombre as nombre_autor,
                d.id as id_categoria,
                d.nombre as nombre_categoria
            FROM recomendados a, tonos b, autores c,categorias d
            WHERE
                a.id=b.id
                AND b.autor=c.id
                AND b.categoria=d.id
                AND b.interfases&1
            ORDER BY rand()";
	$vlf_sql = "
select 	t.id as id_tono,
	t.nombre as nombre_tono,
	a.id as id_autor,
	a.nombre as nombre_autor,
	c.id as id_categoria,
	c.nombre as nombre_categoria
from recomendados r, tonos t
left join autores a on (a.id=t.autor)
left join categorias c on (c.id=t.categoria)
where r.id=t.id and t.interfases&1
ORDER BY rand()";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}
?>
