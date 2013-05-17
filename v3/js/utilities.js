<!--
//Define según el foco, el texto que aparece en el campo de envío de mail para news letter: "Ingresa tu E-mail aquí"
var textonews = null;
	
	function fieldMail(input,estado,msg){
		if(estado){
			
			textonews = msg;
			
			if(input.value == textonews){
				input.value = "";
			}
		}else{
			//alert(msg);
			if(input.value == ""){
				input.value = textonews;
			}
		}
	}
	
//Cambia el action del formulario para que envíe los campos vía post al php correpondiete

	function selectSearch(type){
		
		var form = document.getElementById("search");
		var findtype = document.getElementById("find-type");
//	alert(form.action);	

	document.search.action= findtype.options[findtype.selectedIndex].value + ".php";


//alert(form.action);		
		/*for(var i=0; i<findtype.length;i++){
			if(findtype.options[i].selected){
				form.action = findtype.options[i].value + ".php";
				//alert(form.action);
			}
		}
		*/
	}
	
//Selecciona uno de los tres discos de un album triple

	function changeDisc(id,amount){
		for(i=1; i<=amount; i++){
			document.getElementById("l" + i).className  = "unselected-link";
			document.getElementById("disc" + i).className  = "hidden-disc";
		}		
		document.getElementById("l" + id).className  = "selected-link";
		document.getElementById("disc" + id).className  = "show-disc";
	}
	
//Funciones para el Light Box


	function showBlack(){
		var navegador = navigator.appName;
		/*if(navegador == "Microsoft Internet Explorer"){
			document.getElementById('lightbox').style.marginTop = "0px";
			window.scrollTo(0);
		}else{
			document.getElementById('lightbox').style.marginTop = window.pageYOffset + "px";
		}*/
		if(document.all){
			document.getElementById('lightbox').style.marginTop = window.document.documentElement.scrollTop + "px";
		}else{
			document.getElementById('lightbox').style.marginTop = window.pageYOffset + "px";
		}
		//Muestro el lightbox
		document.getElementById('lightbox').style.display = "block";
		//Elimino el scroll dle sitio
		document.body.style.overflow = 'hidden';
	}

	function hiddenBlack(){
		
		//Oculto la placa de descarga
		document.getElementById('lightbox').style.display = "none";
		//Coloco el scroll al sitio
		document.body.style.overflow = 'visible';
		//estado = false;
	}

	window.onresize = function(){
		var navegador = navigator.appName;
		if(navegador == "Microsoft Internet Explorer"){
			var navegador = navigator.appName;
			var marginsup = (document.body.clientHeight / 2) - 130;
			document.getElementById('white-block').style.marginTop = marginsup + "px";
			document.getElementById('black-block').style.height = document.body.offsetHeight;
		}else if(navegador != "Microsoft Internet Explorer"){
			var navegador = navigator.appName;
			var marginsup = (document.body.clientHeight / 2) - 130;
			document.getElementById('white-block').style.marginTop = marginsup + "px";
			document.getElementById('black-block').style.height = document.body.offsetHeight;
		}
	}


	function buildForm(idtema,idalbum,nombre_tema,nombre_artista,nombre_genero,nombre_album,valor_tema){
		document.form_compra.idtema.value = idtema;
		document.form_compra.codigo.value = "Ingresa tu codigo";
		document.getElementById("id_tema_compra").innerHTML = unescape(idtema);
		document.getElementById("nombre_album_compra").innerHTML = unescape(nombre_album);
		document.getElementById("nombre_tema_compra").innerHTML = unescape(nombre_tema);
		document.getElementById("nombre_artista_compra").innerHTML = unescape(nombre_artista);
		document.getElementById("nombre_genero_compra").innerHTML = unescape(nombre_genero);
		var valor_tema = (valor_tema > 1)? valor_tema+" cr&eacute;ditos" : valor_tema+" cr&eacute;dito";
		if(document.getElementById("valor_tema") != null){
			document.getElementById("valor_tema").innerHTML = "Costo de la descarga "+valor_tema+".";
		}
		
		document.getElementById("tapa_album_compra").innerHTML = '<img src="http://www.wazzupmusic.com/sitio/getcover.php?width=78&height=78&id='+idalbum+'" class="border-picture" alt="Artista" title="Artista" />';
		
		document.getElementById('respuesta-error').style.display = "none";
		document.getElementById('respuesta-ok').style.display = "none";
		document.getElementById('respuesta-error').innerHTML = "";
		document.getElementById('respuesta-ok').innerHTML = "";
		document.getElementById('insert-code').style.display = "block";
		showBlack();
	}
	
	//Ingresar Código al usuario
	function controlarCodigo(){
		if(document.form_pin.codigo.value == ""){
			document.getElementById('pin-respuesta-ok').style.display = "none";
			document.getElementById('pin-respuesta-error').style.display = "block";
			document.getElementById('pin-respuesta-error').innerHTML = "Por favor ingresa un c&oacute;digo";
			document.getElementById('pin-insert-code').style.display = "block";
		}else{ 
			document.getElementById('pin-respuesta-ok').innerHTML = "Enviando datos...";
			document.getElementById('pin-respuesta-ok').style.display = "block";			
			document.getElementById('pin-respuesta-error').style.display = "none";			
			document.getElementById('pin-insert-code').style.display = "none";
			sendCodigo();
		}
	}
	function sendCodigo(){		
		var ajax = nuevoAjax();
		ajax.open("POST", "compra_ingresar-credito.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4){
				var respuestaAjax = ajax.responseText.split('|');
				var respuesta = respuestaAjax[0];
				var respuesta_extra = respuestaAjax[1];
				var creditos = respuestaAjax[2];
				if(respuesta == "ok"){
					var texto_creditos = (creditos>1)? "cr&eacute;ditos" : "cr&eacute;dito";
					document.getElementById('creditos-user').innerHTML = "Tienes "+creditos+" "+texto_creditos;
					document.getElementById('pin-respuesta-error').style.display = "none";
					document.getElementById('pin-respuesta-ok').style.display = "block";
					document.getElementById("pin-respuesta-ok").innerHTML = respuesta_extra; //'Realiza tu descarga <a href="getTrack.php?idtema='+document.form_compra.idtema.value+'&codigo='+document.form_compra.codigo.value+'">click aqu&iacute;</a>';
					document.form_compra.reset();
				}else{
					document.getElementById('pin-insert-code').style.display = "block";
					document.getElementById('pin-respuesta-ok').style.display = "none";
					document.getElementById('pin-respuesta-error').style.display = "block";
					document.getElementById("pin-respuesta-error").innerHTML = respuesta_extra;
					//document.form_compra.reset();
				}
			}
		}
		ajax.send("codigo="+document.form_pin.codigo.value+"&idusuario="+document.form_pin.idusuario.value);
	}
	//Termina Ingresar Código al usuario
	function descargarTema(){
		
		var ajax = nuevoAjax();
		ajax.open("POST", "descargar.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4){
				var respuestaAjax = ajax.responseText.split('|');
				var respuesta = respuestaAjax[0];
				var respuesta_extra = respuestaAjax[1];
				if(respuesta == "ok"){
					var url_descarga = respuestaAjax[2];
					document.getElementById('insert-code').style.display = "none";
					document.getElementById('respuesta-error').style.display = "none";
					document.getElementById('respuesta-ok').style.display = "block";
					document.getElementById("respuesta-ok").innerHTML = respuesta_extra; //'Realiza tu descarga <a href="getTrack.php?idtema='+document.form_compra.idtema.value+'&codigo='+document.form_compra.codigo.value+'">click aqu&iacute;</a>';
					autoDownload(url_descarga);
					document.form_compra.reset();
				}else{
					document.getElementById('insert-code').style.display = "none";
					document.getElementById('respuesta-ok').style.display = "none";
					document.getElementById('respuesta-error').style.display = "block";
					document.getElementById("respuesta-error").innerHTML = respuesta_extra;
					//document.form_compra.reset();
				}
			}
		}
		ajax.send("idtema="+document.form_compra.idtema.value);
	}
	function autoDownload(url) {
		var navegador = navigator.appName;
		if (navegador == "Microsoft Internet Explorer") {
			return false;
		} else { 
			location.href = url;
		}
	}

	
	
	
	function controlarCampos(){
		if(document.form_compra.codigo.value == "Ingresa tu codigo" || document.form_compra.codigo.value == ""){
			document.getElementById('respuesta-ok').style.display = "none";
			document.getElementById('respuesta-error').style.display = "block";
			document.getElementById('respuesta-error').innerHTML = "Por favor ingresa un c&oacute;digo";
			document.getElementById('insert-code').style.display = "block";
		}else{ 
			document.getElementById('respuesta-ok').innerHTML = "Enviando datos...";
			document.getElementById('respuesta-ok').style.display = "block";			
			document.getElementById('respuesta-error').style.display = "none";			
			document.getElementById('insert-code').style.display = "none";
			sendData();
		}
	}
	function sendData(){		
		var ajax = nuevoAjax();
		ajax.open("POST", "compra.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4){
				var respuestaAjax = ajax.responseText.split('|');
				var respuesta = respuestaAjax[0];
				var respuesta_extra = respuestaAjax[1];
				if(respuesta == "ok"){
					var url_descarga = respuestaAjax[2];
					document.getElementById('respuesta-error').style.display = "none";
					document.getElementById('respuesta-ok').style.display = "block";
					document.getElementById("respuesta-ok").innerHTML = respuesta_extra; //'Realiza tu descarga <a href="getTrack.php?idtema='+document.form_compra.idtema.value+'&codigo='+document.form_compra.codigo.value+'">click aqu&iacute;</a>';
					autoDownload(url_descarga);
					document.form_compra.reset();
				}else{
					document.getElementById('insert-code').style.display = "block";
					document.getElementById('respuesta-ok').style.display = "none";
					document.getElementById('respuesta-error').style.display = "block";
					document.getElementById("respuesta-error").innerHTML = respuesta_extra;
					//document.form_compra.reset();
				}
			}
		}
		ajax.send("codigo="+document.form_compra.codigo.value+"&idtema="+document.form_compra.idtema.value);
	}
	function changeFieldValue(idcampo,valor){
		document.getElementById(idcampo).value = valor;
	}

-->
