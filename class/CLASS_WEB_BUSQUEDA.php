<?php
class CLASS_WEB_BUSQUEDA
{
    private $vlc_codigo_html;
    private $vlc_codigo_html_resultados;

    private $vlc_texto_busqueda;
    private $vlc_filtro_tipo;
    private $vlc_filtro_precio;
    private $vlc_filtro_genero;
    private $vlc_rango_precios;
    private $vlc_letra_artista;
    private $vlc_id_artista;
    private $vlc_pagina_busqueda;

    function __construct()
    {
        //INICIALIZACION DE VARIABLES
        $this->vlc_codigo_html      ="";
        $this->vlc_codigo_html_resultados="";
        $this->vlc_texto_busqueda   ="";
        $this->vlc_filtro_tipo      ="";
        $this->vlc_filtro_precio    ="";
        $this->vlc_filtro_genero    ="";
        $this->vlc_rango_precios    ="";
        $this->vlc_letra_artista    ="";
        $this->vlc_id_artista       ="";
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-panel-busqueda.html');
        $this->MTD_RECIBIR_VARIABLES();
    }
    public function MTD_REALIZAR_BUSQUEDA()
    {
        global $vg_conf_busqueda_inteligente;
        if (isset($_REQUEST['artista']))
        {
                $this->vlc_codigo_html_resultados= FN_LEER_TPL('tpl/tpl-resultado-busqueda.html');
                $vl_arreglo_datos = $this->MTD_DB_BUSCAR_ARTISTAS();
                $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE_ARTISTAS($vl_arreglo_datos);
                $this->vlc_codigo_html_resultados= FN_REEMPLAZAR('{tpl-tonos}', $vl_lista_tonos,  $this->vlc_codigo_html_resultados);
                $this->vlc_codigo_html_resultados = FN_REEMPLAZAR('{tpl-texto-busqueda}', "",  $this->vlc_codigo_html_resultados);
                return  $this->vlc_codigo_html_resultados;
        }
        else
        {
            $this->vlc_codigo_html_resultados= FN_LEER_TPL('tpl/tpl-resultado-busqueda.html');
            if ($vg_conf_busqueda_inteligente == true)
            {
                $vl_arreglo_datos =    $this->MTD_DB_BUSCAR_TONOS_INTELIGENTE();
            }
            else
            {
                $vl_arreglo_datos = $this->MTD_DB_BUSCAR_TONOS();
            }
            $vl_lista_tonos = $this->MTD_APLICAR_TEMPLATE($vl_arreglo_datos);
            $this->vlc_codigo_html_resultados= FN_REEMPLAZAR('{tpl-tonos}', $vl_lista_tonos,  $this->vlc_codigo_html_resultados);
            return  $this->vlc_codigo_html_resultados;
        }
        
    }

    public function MTD_RETORNAR_BUSQUEDA()
    {
        global $vg_conf_busqueda_inteligente;
        if ($vg_conf_busqueda_inteligente == true)
        {
            $vl_arreglo_datos =    $this->MTD_DB_BUSCAR_TONOS_INTELIGENTE();
        }
        else
        {
            $vl_arreglo_datos = $this->MTD_DB_BUSCAR_TONOS();
        }
        return  $vl_arreglo_datos;
    }


    function MTD_DB_BUSCAR_TONOS_INTELIGENTE()
    {
       global $vg_db_conexion,$vg_conf_filtro_web,$vg_conf_busqueda_inteligente_host,$vg_conf_busqueda_inteligente_port;
        $filtro="";
        $filtro2="";

        if ($this->vlc_filtro_tipo == "todo")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=$this->vlc_texto_busqueda;
            }
        }
        elseif ($this->vlc_filtro_tipo == "temas")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=$this->vlc_texto_busqueda;
            }
        }
        elseif ($this->vlc_filtro_tipo == "albumes")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro2.=" AND alb.nombre like '%".$this->vlc_texto_busqueda."%' ";
            }
        }
        elseif ($this->vlc_filtro_tipo == "artistas")
        {
            if ($this->vlc_texto_busqueda !="")
            {
               $filtro.=$this->vlc_texto_busqueda;
            }
        }
        else
        {
            if ($this->vlc_texto_busqueda !="")
            {
               $filtro.=$this->vlc_texto_busqueda;
            }
        }
        //FILTRO DE RANGO DE PRECIOS
        if (($this->vlc_filtro_precio != "todo") && ($this->vlc_filtro_precio != ""))
        {
             //echo "<h1><b>filtroprecio:".$this->vlc_filtro_precio."</b></h1>";
             $vl_arreglo_precios= array();
             $vl_arreglo_precios= explode(';',$this->vlc_filtro_precio );

             $vl_rango_inicial=$vl_arreglo_precios[0];
             $vl_rango_final=$vl_arreglo_precios[1];

             $filtro2.=" AND bil.costo >= $vl_rango_inicial AND bil.costo <= $vl_rango_final ";
        }
        if ($this->vlc_id_artista !="")
        {
            $vlf_sql="select nombre from autores where id=$this->vlc_id_artista";
            $vl_arreglo= FN_RUN_QUERY($vlf_sql, 1,$vg_db_conexion);
            $vl_artista=$vl_arreglo[0][0];
            $filtro.=$vl_artista;
            $filtro2.=" AND t.autor=$this->vlc_id_artista ";
        }        
         //FILTRO DE GENERO
        if ($this->vlc_filtro_genero != "")
        {
            //TODO BUSCAR EL NOMBRE DEL GENERO
            //$vlf_sql="select nombre from generos where id_genero=$this->vlc_filtro_genero";
            //$vl_arreglo= FN_RUN_QUERY($vlf_sql, 1,$vg_db_conexion);
            //$vl_genero=$vl_arreglo[0][0];
            $filtro2.=" AND t.genero =".$this->vlc_filtro_genero. " ";
        }

        //BUSQUEDA POR ARTISTA
        if ($this->vlc_letra_artista != "")
        {
            if ($this->vlc_letra_artista == '0-9')
            {
                $filtro2.=" AND (aut.nombre like '0%' ";
                $filtro2.=" OR aut.nombre like '1%' ";
                $filtro2.=" OR aut.nombre like '2%' ";
                $filtro2.=" OR aut.nombre like '3%' ";
                $filtro2.=" OR aut.nombre like '4%' ";
                $filtro2.=" OR aut.nombre like '5%' ";
                $filtro2.=" OR aut.nombre like '6%' ";
                $filtro2.=" OR aut.nombre like '7%' ";
                $filtro2.=" OR aut.nombre like '8%' ";
                $filtro2.=" OR aut.nombre like '9%' ) ";
            }
            else
            {
                $filtro2.=" AND aut.nombre  like '".$this->vlc_letra_artista."%'";
            }
        }

        $registros_por_pagina=60;
        $this->vlc_pagina_busqueda;
        //=========================================================================================
        // BUSQUEDA INTELIGENTE
        //=========================================================================================
        $vl_resultado=false;
        //$vg_conf_busqueda_inteligente_host,$vg_conf_busqueda_inteligente_port
        FN_NET_LOGGER("Busqueda Inteligente: $filtro");
        $vl_resultado = FN_NET_SOCKET($vg_conf_busqueda_inteligente_host, $vg_conf_busqueda_inteligente_port, $filtro,true, 32768);
        FN_NET_LOGGER("Busqueda Inteligente: Resultado \n $vl_resultado ");
        $vl_arreglo_tonos=array();
        $vl_arreglo_tonos_detalle=array();
        $vl_arreglo_tonos = explode("\n",$vl_resultado);
        $vl_cantidad_registros=sizeof($vl_arreglo_tonos );
        $vl_contador=0;
        $vl_id_tonos_busqueda="";

        FN_NET_LOGGER("Busqueda Inteligente: ser recibieron $vl_cantidad_registros resultados");
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_arreglo_tonos_detalle = explode(";",$vl_arreglo_tonos[$vl_contador]);
            FN_NET_LOGGER("Busqueda Inteligente: resultados >".$vl_arreglo_tonos_detalle[0]);
            $vl_id_tonos_busqueda.= $vl_arreglo_tonos_detalle[0].",";
            $vl_contador++;
        }

        if ($vl_id_tonos_busqueda != -1)
        {
            $vlf_sql = "SELECT t.id, t.nombre as tono,aut.nombre as autor, alb.imagen, round(avg(wr.calificacion),0) as calificacion
                    FROM tonos as t
                    inner join autores as aut on ( t.autor = aut.id)
                    inner join billing as bil on (t.billing = bil.caso  and bil.concepto=2)
                    left join albumes as alb on (t.album = alb.id_album)
                    left join web_tonos_ranking as wr on (t.id = wr.tonos_id)
                    WHERE
                    FIND_IN_SET(t.id, '$vl_id_tonos_busqueda')
                    $filtro2
                    $vg_conf_filtro_web
                    group by t.id, t.nombre ,aut.nombre , alb.imagen
                    order by FIND_IN_SET(t.id, '$vl_id_tonos_busqueda')
                    limit 114";
        }
        else
        {
                $vlf_sql = "SELECT t.id, t.nombre as tono,aut.nombre as autor, alb.imagen, round(avg(wr.calificacion),0) as calificacion
                    FROM tonos as t
                    inner join autores as aut on ( t.autor = aut.id)
                    inner join billing as bil on (t.billing = bil.caso  and bil.concepto=2)
                    left join albumes as alb on (t.album = alb.id_album)
                    left join web_tonos_ranking as wr on (t.id = wr.tonos_id)
                    WHERE
                    t.id > 0
                    $filtro2
                    $vg_conf_filtro_web
                    group by t.id, t.nombre ,aut.nombre , alb.imagen
                    order by t.nombre ,aut.nombre 
                    limit 114";
        }
        //if ($this->vlc_pagina_busqueda >)

        
       FN_NET_LOGGER("Busqueda de Backtones Intel: \n SQL:$vlf_sql ");
        //echo "<textarea style='width:900px;height:100px;'>$vlf_sql</textarea>";

        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 5,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }

    function MTD_DB_BUSCAR_ARTISTAS()
    {
         global $vg_db_conexion, $vg_conf_filtro_web;

         $filtro="";
         $vlf_sql="";
         $vl_arreglo_datos=array();
         if ($this->vlc_letra_artista != "")
         {
                if ($this->vlc_letra_artista == '0-9')
		{
		    $filtro.=" AND (aut.nombre like '0%' ";
		    $filtro.=" OR aut.nombre like '1%' ";
		    $filtro.=" OR aut.nombre like '2%' ";
		    $filtro.=" OR aut.nombre like '3%' ";
		    $filtro.=" OR aut.nombre like '4%' ";
		    $filtro.=" OR aut.nombre like '5%' ";
		    $filtro.=" OR aut.nombre like '6%' ";
		    $filtro.=" OR aut.nombre like '7%' ";
		    $filtro.=" OR aut.nombre like '8%' ";
		    $filtro.=" OR aut.nombre like '9%' ) ";
		}
		else
		{
	            $filtro.=" AND aut.nombre  like '".$this->vlc_letra_artista."%'";
	        }
                $vlf_sql="SELECT aut.id, aut.nombre from autores as aut, tonos as t where
                 aut.id = t.autor $vg_conf_filtro_web  $filtro group by aut.id, aut.nombre order by aut.nombre";
                $vl_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2, $vg_db_conexion);
                FN_NET_LOGGER("MTD_DB_BUSCAR_ARTISTAS > retornando registros:".sizeof($vl_arreglo_datos));
                return $vl_arreglo_datos;
	 }

         return false;
    }
    function MTD_DB_BUSCAR_TONOS()
    {
        global $vg_db_conexion,$vg_conf_filtro_web;
        $filtro="";

       
        FN_NET_LOGGER("DB_BUSCAR_TONOS > Texto Busqueda:".$this->vlc_texto_busqueda,5);

        if ($this->vlc_filtro_tipo == "todo")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=" AND (t.nombre like '%".$this->vlc_texto_busqueda."%'";
                $filtro.=" OR aut.nombre  like '%".$this->vlc_texto_busqueda."%'";
                $filtro.=" OR alb.nombre like '%".$this->vlc_texto_busqueda."%') ";
            }
        }
        elseif ($this->vlc_filtro_tipo == "temas")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=" AND t.nombre like '%".$this->vlc_texto_busqueda."%' ";
            }
        }
        elseif ($this->vlc_filtro_tipo == "albumes")
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=" AND alb.nombre like '%".$this->vlc_texto_busqueda."%' ";
            }
        }
        elseif ($this->vlc_filtro_tipo == "artistas")
        {
            if ($this->vlc_texto_busqueda !="")
            {
               $filtro.=" AND aut.nombre  like '%".$this->vlc_texto_busqueda."%'";
            }
        }
        else
        {
            if ($this->vlc_texto_busqueda !="")
            {
                $filtro.=" AND (t.nombre like '%".$this->vlc_texto_busqueda."%'";
                $filtro.=" OR aut.nombre  like '%".$this->vlc_texto_busqueda."%'";
                $filtro.=" OR alb.nombre like '%".$this->vlc_texto_busqueda."%') ";
            }
        }


        //FILTRO DE RANGO DE PRECIOS
        if ($this->vlc_id_artista !="")
        {
            $filtro.=" AND t.autor=$this->vlc_id_artista ";
        }
        if (($this->vlc_filtro_precio != "todo") && ($this->vlc_filtro_precio != ""))
        {
             //echo "<h1><b>filtroprecio:".$this->vlc_filtro_precio."</b></h1>";
             $vl_arreglo_precios= array();
             $vl_arreglo_precios= explode(';',$this->vlc_filtro_precio );

             $vl_rango_inicial=$vl_arreglo_precios[0];
             $vl_rango_final=$vl_arreglo_precios[1];

             $filtro.=" AND bil.costo >= $vl_rango_inicial AND bil.costo <= $vl_rango_final ";
        }
        //FILTRO DE GENERO
        if ($this->vlc_filtro_genero != "")
        {
            $filtro.=" AND t.genero =".$this->vlc_filtro_genero. " ";
        }

        //BUSQUEDA POR ARTISTA
        if ($this->vlc_letra_artista != "")
        {
		if ($this->vlc_letra_artista == '0-9')
		{
		    $filtro.=" AND (aut.nombre like '0%' ";
		    $filtro.=" OR aut.nombre like '1%' ";
		    $filtro.=" OR aut.nombre like '2%' ";
		    $filtro.=" OR aut.nombre like '3%' ";
		    $filtro.=" OR aut.nombre like '4%' ";
		    $filtro.=" OR aut.nombre like '5%' ";
		    $filtro.=" OR aut.nombre like '6%' ";
		    $filtro.=" OR aut.nombre like '7%' ";
		    $filtro.=" OR aut.nombre like '8%' ";
		    $filtro.=" OR aut.nombre like '9%' ) ";
		}	
		else
		{
	            $filtro.=" AND aut.nombre  like '".$this->vlc_letra_artista."%'";
	        }
	}

        $registros_por_pagina=60;
        $this->vlc_pagina_busqueda;
        //if ($this->vlc_pagina_busqueda >)

/*
        $vlf_sql = "SELECT t.id, t.nombre as tono,aut.nombre as autor, alb.imagen, round(avg(wr.calificacion),0) as calificacion,t.autor as id_autor
                    FROM tonos as t
                    inner join autores as aut on ( t.autor = aut.id)
                    inner join billing as bil on (t.billing = bil.caso  and bil.concepto=2)
                    left join albumes as alb on (t.album = alb.id_album)
                    left join web_tonos_ranking as wr on (t.id = wr.tonos_id)
                    WHERE
                    t.id >0
                    $filtro
                    $vg_conf_filtro_web
                    group by t.id, t.nombre ,aut.nombre , alb.imagen,t.autor
                    order by t.nombre
                    limit 114";
*/

	$vlf_sql = "
select 	t.id, 
	t.nombre as tono,
	a.nombre as autor,
	m.imagen as album,
	round(avg(r.calificacion),0) as calificacion,
	t.autor as id_autor
from tonos t
	left join autores a on (t.autor=a.id)
	left join billing b on (t.billing=b.caso)
	left join albumes m on (t.album=m.id_album)
	left join web_tonos_ranking r on (t.id=r.tonos_id)
where
	t.id
	$filtro
	$vg_conf_filtro_web
	and t.categoria != 207
	and t.interfases&1
group by t.id,t.nombre,a.nombre,m.imagen,t.autor
order by t.nombre
limit 114;
	";

       	FN_NET_LOGGER("Busqueda de Backtones: \n  SQL:$vlf_sql ");

        //echo "<textarea style='width:900px;height:100px;'>$vlf_sql</textarea>";

        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 6,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }

    function MTD_APLICAR_TEMPLATE($vp_arreglo_datos)
    {
        // t.id, t.nombre as tono,aut.nombre as autor, alb.imagen
        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);
        if ($vp_arreglo_datos)
        {
            $vl_template= FN_LEER_TPL('tpl/tpl-resultado-busqueda-tonos.html');

            $vl_tonos="<div><div id='contenedor-albumes'>";
            while ($vl_contador < $vl_cantidad_registros)
            {

                //LIMPIEZA DE VARIABLES
                $vl_template_tonos= "";
                $id_tono=0;
                $tono="";
                $autor="";
                $imagen_album="";
                $vl_posicion= $vl_contador +1;
                $vl_id_autor=$vp_arreglo_datos[$vl_contador][5];
                //ASIGNACION DE VARIABLES
                $vl_template_tonos= $vl_template;
                $id_tono=$vp_arreglo_datos[$vl_contador][0];
                //$tono=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][1])));
                $tono=trim($vp_arreglo_datos[$vl_contador][1]);
                $titulo_tono=$tono;

                //$autor=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][2])));
                $autor=trim($vp_arreglo_datos[$vl_contador][2]);
                $titulo_autor=$autor;

                $imagen_album=$vp_arreglo_datos[$vl_contador][3];
                if ($imagen_album == "")
                {
                        $imagen_album="th-album-default.jpg";
                }
                $link_reproducir="index.php?seccion=backtones&backtone=$id_tono";
                $link_artista="index.php?seccion=playlist&autor=$vl_id_autor";
                $link_compra="#link-compra-$id_tono";
                $link_regalo="#link-regalo-$id_tono";
                $link_descarga="#link-desacarga-$id_tono";
                $link_pedir="#link-pedir-$id_tono";
                $link_tono="index.php?seccion=backtones&backtone=$id_tono";

                $tono=FN_STRING_LIMITE($tono, 27);
                $autor=FN_STRING_LIMITE($autor, 27);
                $template_ranking=FN_WEB_ESTRELLAS($vp_arreglo_datos[$vl_contador][4]);


                //COMENTARIOS
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-imagen-album}' , $imagen_album, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-artista}'      , $autor, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-artista}' , $link_artista, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-nombre}'       , $tono, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-reproducir}'  , $link_reproducir, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-comprar}' , $link_compra, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-regalar}' , $link_regalo, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-ingresar}'   , $link_tono, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-ranking}'   , $template_ranking, $vl_template_tonos);



                if (($vl_contador % 14 == 0) && ( $vl_contador >=1))
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
        }
        else
        {
            $vl_tonos= FN_LEER_TPL('tpl/tpl-resultados-busqueda-sin-resultados.html');

        }

        return $vl_tonos ;
    }
    function MTD_APLICAR_TEMPLATE_ARTISTAS($vp_arreglo_datos)
    {
        // t.id, t.nombre as tono,aut.nombre as autor, alb.imagen
        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo_datos);

        if ($vp_arreglo_datos)
        {
            $vl_template= FN_LEER_TPL('tpl/tpl-resultado-busqueda-artista-listado.html');

            $vl_tonos="<div><div id='contenedor-albumes'>";
            while ($vl_contador < $vl_cantidad_registros)
            {
                //LIMPIEZA DE VARIABLES
                $vl_template_tonos= "";
                $id_tono=0;
                $tono="";
                $autor="";
                $imagen_album="";
                $vl_posicion= $vl_contador +1;
                $vl_id_autor=$vp_arreglo_datos[$vl_contador][0];

                //ASIGNACION DE VARIABLES
                $vl_template_tonos= $vl_template;
                
                
                //$autor=ucfirst(strtolower(trim($vp_arreglo_datos[$vl_contador][2])));
                $autor=trim($vp_arreglo_datos[$vl_contador][1]);
                $titulo_autor=$autor;
                                
                $link_artista="index.php?seccion=playlist&autor=$vl_id_autor";

                $autor=FN_STRING_LIMITE($autor, 27);                

                //COMENTARIOS


                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-link-artista}' , $link_artista, $vl_template_tonos);
                $vl_template_tonos= FN_REEMPLAZAR('{tpl-tono-artista}'      , $autor, $vl_template_tonos);

                if (($vl_contador % 36 == 0) && ( $vl_contador >=1))
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
        }
        else
        {
            $vl_tonos= FN_LEER_TPL('tpl/tpl-resultados-busqueda-sin-resultados.html');
        }

        FN_NET_LOGGER("Retornando el siguiente resultado \n $vl_tonos");
        return $vl_tonos ;
    }

    function MTD_RECIBIR_VARIABLES()
    {
        $this->vlc_letra_artista    = FN_RECIBIR_VARIABLES('artista');

        if (isset($_GET['search']))
        {
            $this->vlc_texto_busqueda = FN_RECIBIR_VARIABLES('search');
            
        }
        else
        {
            $this->vlc_texto_busqueda   = FN_RECIBIR_VARIABLES('tb_texto_busqueda');
        }
                
        $this->vlc_filtro_tipo      = FN_RECIBIR_VARIABLES('tb_filtro_tipo');        
        $this->vlc_filtro_precio    = FN_RECIBIR_VARIABLES('tb_filtro_precio');
        $this->vlc_filtro_genero    = FN_RECIBIR_VARIABLES('genero');
        $this->vlc_id_artista       = FN_RECIBIR_VARIABLES('autor');

        $this->vlc_pagina_busqueda  = FN_RECIBIR_VARIABLES('pagina');
    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        //SELECCION DE LISTAS
        /*
         * temas
         * albumes
         * artistas
         */
        $vl_seleccion_todo="";//{tbl-selected-todo};
        $vl_seleccion_temas="";//{tbl-selected-temas};
        $vl_seleccion_albumes="";//{tbl-selected-albumes};
        $vl_seleccion_artistas="";//{tbl-selected-artistas};

        if ($this->vlc_filtro_tipo == "todo")
        {
            $vl_seleccion_todo="selected";
        }
        elseif ($this->vlc_filtro_tipo == "temas")
        {
            $vl_seleccion_temas="selected";
        }
        elseif ($this->vlc_filtro_tipo == "albumes")
        {
            $vl_seleccion_albumes="selected";
        }
        elseif ($this->vlc_filtro_tipo == "artistas")
        {
            $vl_seleccion_artistas="selected";
        }
        // LISTA PRECIOS
        //======================================
        $vl_seleccion_precio_todo="";//{tbl-selected-todo};
        $vl_seleccion_precio_0_150="";//{tbl-selected-temas};
        $vl_seleccion_precio_200_250="";//{tbl-selected-albumes};
        $vl_seleccion_precio_300_350="";//{tbl-selected-artistas};

        if ($this->vlc_filtro_precio == "todo")
        {
            $vl_seleccion_precio_todo="selected";
        }
        elseif ($this->vlc_filtro_precio == "0;150")
        {
            $vl_seleccion_precio_0_150="selected";
        }
        elseif ($this->vlc_filtro_precio == "200;250")
        {
            $vl_seleccion_precio_200_250="selected";
        }
        elseif ($this->vlc_filtro_precio == "300;350")
        {
            $vl_seleccion_precio_300_350="selected";
        }
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tbl-selected-precio-todo}",$vl_seleccion_precio_todo,$this->vlc_codigo_html);
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tbl-selected-0-150}",$vl_seleccion_precio_0_150,$this->vlc_codigo_html);
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tbl-selected-200-250}",$vl_seleccion_precio_200_250,$this->vlc_codigo_html);
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tbl-selected-300-350}",$vl_seleccion_precio_300_350,$this->vlc_codigo_html);

        $this->vlc_codigo_html = FN_REEMPLAZAR("{tpl-texto-busqueda}",$this->vlc_texto_busqueda,$this->vlc_codigo_html);
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }


}
?>
