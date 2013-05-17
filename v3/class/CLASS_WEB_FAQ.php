<?php
class CLASS_WEB_FAQ
{
    private $vlc_codigo_html;

    function __construct()
    {
        $this->vlc_codigo_html="";

        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-faq.html');
        $vl_arreglo= array();
        $vl_arreglo = $this->MTD_DB_LISTAR_PREGUNTAS();
        $this->MTD_APLICAR_TEMPLATE($vl_arreglo );
     
    }
    function MTD_APLICAR_TEMPLATE($vp_arreglo)
    {
        $vl_contador=0;        
        $vl_cantidad_registros= sizeof($vp_arreglo );
        $this->vlc_lista_genero="";
        $vl_template=" <li class='listaenlace'>
                        <a href='index.php?seccion=faq'>{tpl-pregunta}</a></span>
                    </li>";
        $vl_preguntas ="";
        while ($vl_contador < $vl_cantidad_registros)
        {            
            $vl_preguntas .=$vl_template;
            $vl_preguntas = FN_REEMPLAZAR("{tpl-pregunta}", $vp_arreglo[$vl_contador][0] , $vl_preguntas);
            $vl_contador++;
        }
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tpl-faq}",$vl_preguntas,$this->vlc_codigo_html );
        //FN_NET_LOGGER("PREGUNTAS :$vl_preguntas ");
    }
    function MTD_APLICAR_TEMPLATE_SECCION($vp_arreglo)
    {
        $vl_contador=0;
        $vl_cantidad_registros= sizeof($vp_arreglo );
        $this->vlc_lista_genero="";
        $vl_template=" <img src='estilos/tigo/images/info2.png' style='float:left; padding:5px;'/>
               <span><b>{tpl-titulo-faq}</b></span> </br>
               <p>
                   {tpl-descripcion-faq}
               </p>
               <br>";
        $vl_preguntas ="";
        while ($vl_contador < $vl_cantidad_registros)
        {
            $vl_preguntas .=$vl_template;
            $vl_preguntas = FN_REEMPLAZAR("{tpl-titulo-faq}", $vp_arreglo[$vl_contador][0] , $vl_preguntas);
            $vl_preguntas = FN_REEMPLAZAR("{tpl-descripcion-faq}", $vp_arreglo[$vl_contador][1] , $vl_preguntas);
            $vl_contador++;
        }
        $this->vlc_codigo_html = FN_REEMPLAZAR("{tpl-faq}",$vl_preguntas,$this->vlc_codigo_html );
        //FN_NET_LOGGER("PREGUNTAS :$vl_preguntas ");
    }
    function MTD_DB_LISTAR_PREGUNTAS()
    {
        global $vg_db_conexion;
        $vlf_sql = "SELECT titulo FROM web_faq where estado=1 order by rand() limit 3 ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 1,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_DB_LISTAR_TODAS_LAS_PREGUNTAS()
    {
        global $vg_db_conexion;
        $vlf_sql = "SELECT titulo,descripcion FROM web_faq where estado=1 order by orden ";
        $vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 2,$vg_db_conexion);
        return $vlf_arreglo_datos;
    }
    function MTD_SECCION_FAQ()
    {
        $this->vlc_codigo_html= FN_LEER_TPL('tpl/tpl-faq-completo.html');
        $vl_arreglo= array();
        $vl_arreglo = $this->MTD_DB_LISTAR_TODAS_LAS_PREGUNTAS();
        $this->MTD_APLICAR_TEMPLATE_SECCION($vl_arreglo );
        return $this->vlc_codigo_html;

    }
    function MTD_RETORNAR_CODIGO_HTML()
    {
        return $this->vlc_codigo_html;
        //return  $this->vlc_codigo_menues;
    }

}

?>