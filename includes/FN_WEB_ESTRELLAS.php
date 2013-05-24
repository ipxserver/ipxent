<?php
function FN_WEB_ESTRELLAS($vp_ranking)
{
      //RANKING
            $ranking_usuario = $vp_ranking;
            //$compras_usuario = rand(0,500);
            $vl_contador_ranking=0;
            $template_ranking="";

            if ($ranking_usuario == 0 )
                return "<img src='estilos/tigo/images/star_empty.png'/>";

            //$template_ranking="<textarea style='border:1px solid red; color:black;'> KONHOOO $ranking_usuario * ";
            while ($vl_contador_ranking < $ranking_usuario)
            {
                $template_ranking.="<img src='estilos/tigo/images/star_full.png'/>";
                $vl_contador_ranking= $vl_contador_ranking +1;
            }

            $decimales=0;
            $decimales=$vl_contador_ranking - $ranking_usuario;

            if ($decimales > 0 && $decimales < 1)
            {
                $template_ranking.="<img src='estilos/tigo/images/star_half.png'/>";
                $template_ranking.="<span>$ranking_usuario</span>";
            }
            return $template_ranking;
}

?>
