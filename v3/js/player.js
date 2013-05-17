<!--
var latest_player = null;
var father = null;

function includePlayer(nodo,id,estilos){

    //Primero borro el bloque que quedo almacenado en la variable
    if(latest_player){
        father.removeChild(latest_player);
    }

    latest_player = document.createElement("div");
    latest_player.id = "player";
	latest_player.style.marginLeft = "10px";
	
	
	latest_player.innerHTML = '<div id="in_player"></div>';
	
    
    var current = nodo.parentNode.parentNode;
	
	//current.insertBefore(in_player,current);
	
    father = current.parentNode;
    
    father.insertBefore(latest_player,current);
	
	insertSwf(id,estilos);

}

function includePlayerHome(id,estilos){
	insertSwf(id,estilos);
}

function insertSwf(id,estilos){
	var flashvars = { colors: estilos, id: id };
	var params = { menu: "false", wmode:"transparent" };
	
 
	swfobject.embedSWF("swf/player.swf", "in_player", "550", "32", "9", "",flashvars,params );
	
/*	var FO = { movie:"swf/player.swf", menu:"false", width:"550", height:"32", majorversion:"8", build:"0", xi:"true", wmode:"transparent", flashvars: "colors=" + estilos + "&id=" + id};
	UFO.create(FO, "player");*/
}
-->
