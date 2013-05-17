<?php
 function FN_DB_SEPARAR_PAGINAS($vp_sql,$vp_pagina,$vp_link,$vp_cantidad_separacion) 
    {
    	
    	$vlf_pagina = $vp_pagina;
    	
    	$vlf_sql= "select count(*) from ($vp_sql) as tabla";
    	$vlf_arreglo_datos = FN_RUN_QUERY($vlf_sql, 1, $vlg_conexion);
    	$cantidad_registros =$vlf_arreglo_datos[0][0];
    	
    	$vlf_paginas_totales = ceil($cantidad_registros /  $vp_cantidad_separacion); 
    	$vlf_codigo_html="<TABLE width='100%' border='0' cellspacign=0 cellpadding=0 bgcolor='#ffFFDD'> <TR><TD width='10%' align='right'>Paginas </td><TD align='center'>";
    	if ($vp_pagina > 1)
    	{
    		$vlf_pagina_anterior = $vp_pagina - 1;
    		$vlf_codigo_html.="<a href='$vp_link&pagina=".$vlf_pagina_anterior."'> << Anterior </a> - ";
    	}
    	for ($i=1; $i<=$vlf_paginas_totales; $i++) 
    	{ 
    		if ($i == $vp_pagina)
    		{
				$vlf_codigo_html.="<b>[".$i."]</b> - ";
    		}
    		else
    		{
    			$vlf_codigo_html.="<a href='$vp_link&pagina=".$i."'>".$i."</a> - ";
    		}
			
    	} 
    	if ($vp_pagina < $vlf_paginas_totales )
    	{
    		$vlf_pagina_siguiente = $vp_pagina + 1;
    		$vlf_codigo_html.="<a href='$vp_link&pagina=".$vlf_pagina_siguiente."'> Siguiente >> </a>";
    	}
    	$vlf_codigo_html.="</TD></TR></TABLE>";
    	//echo "<H1> CODIGO_SEPARACION: <BR>".$vlf_codigo_html;
    	return $vlf_codigo_html;   	
    }
?>