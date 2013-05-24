<?php
function FN_CLEAN_STRING($vp_string)
{
	$search = array("\x00", "\n", "\r", "\\", "'", "\"", "\x1a");
	$replace = array("\\x00", "\\n", "\\r", "\\\\" ,"\'", "\\\"", "\\\x1a");
	
	$vp_string = str_replace ( "<", "&lt;", $vp_string );
	$vp_string = str_replace ( ">", "&gt;", $vp_string );
	$vp_string = str_replace ( "\'", "&#39;", $vp_string );
	$vp_string = str_replace ( '\"', "&quot;", $vp_string );
	$vp_string = str_replace ( "\\\\", "&#92", $vp_string );
	
	if (get_magic_quotes_gpc ())
	{
		$vp_string = stripslashes ( $vp_string );
	}
	
	$vp_string = strip_tags ( $vp_string );


	$vp_string = str_replace($search, $replace, $vp_string);
	//$vp_string = mysql_real_escape_string ( $vp_string );
	
	return $vp_string;
}
?>