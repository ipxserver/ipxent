<?
function FN_COMPRIMIR_HTML($buffer) {
//    //$busca = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
        $busca = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
	$reemplaza = array('>','<','\\1');
	return preg_replace($busca, $reemplaza, $buffer);
  
    }

function minimizeHTML($buffer) {
    $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
    $replace = array('>','<','\\1');    
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}
?>