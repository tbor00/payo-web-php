var TTtime=null;
//--------------------------------------------------------------------
function Importar_Comerciantes(){
	var height = "240";
	var width = "480";
	var altoPantalla = window.screen.height;
	var anchoPantalla = window.screen.width;
	var sPropsVentana;
	var left = (anchoPantalla / 2) - (width / 2);
	var top = (altoPantalla / 2) - (height / 2);
	sPropsVentana  = 'width='+ width+ ',height=' + height;
	sPropsVentana += ',top=' + top + ',left=' + left;
	sPropsVentana += ',scrollbars=no,resizable=no';
	var url = 'comerciantes_import.php';
	window.open( url , 'importar',sPropsVentana);
}

//---------------------------------------------------------
function First_Field_Focus(){
	var bFound = false; 
	for (f=0; f < document.forms.length; f++){ 
		for(i=0; i < document.forms[f].length; i++){ 
			if (document.forms[f][i].type != "hidden"){ 
				if (document.forms[f][i].disabled != true) { 
					document.forms[f][i].focus(); 
					var bFound = true; 
				} 
			} 
			if (bFound == true){ 
				break; 
			} 
		} 
		if (bFound == true){ 
			break; 
		} 
	} 
} 
//--------------------------------------------------------- 
function control_form_user(form){ 
	if(ChequeaForm(form)){ 
		if(form.pass1.value!=form.pass2.value){ 
			window.alert("Las contraseñas ingresadas no son iguales"); 
			return false; 
		} 
		form.password.value=form.pass1.value; 
		return true; 
	} else { 
		return false; 
	} 
}
//--------------------------------------------------------- 
function control_passwd_form_user(form){ 
	if(ChequeaForm(form)){
		if (MD5(MD5(form.pass0.value))!=form.oldpassword.value){
			window.alert("La Contraseña Anterior es incorrecta"); 
			return false; 
		}
		if(form.pass1.value!=form.pass2.value){ 
			window.alert("Las contraseñas ingresadas no son iguales"); 
			return false; 
		} 
		form.password.value=form.pass1.value; 
		return true; 
	} else { 
		return false; 
	} 
} 

//--------------------------------------------------------- 
function AddMod(col,coli,hcampo) { 
	if (document.forms[0][coli].selectedIndex > -1) { 
		sli = document.forms[0][coli].selectedIndex; 
		sl = document.forms[0][col].length ; 
		if (document.forms[0][coli].options[sli].value >"") { 
	   	doSub(hcampo); 
			oText = document.forms[0][coli].options[sli].text; 
			oValue = document.forms[0][coli].options[sli].value; 
			var optionObj = new Option(oText, oValue, false, true); 
			document.forms[0][coli].options[sli] = null; 
			document.forms[0][col].options[sl] = optionObj; 
			doSub(hcampo); 
		} 
	} 
} 
//--------------------------------------------------------- 
function makeList(col) { 
	val = ""; 
	for (j=0; j<document.forms[0][col].length; j++) { 
		if (val > "") { 
			val += "~"; 
		} 
		if (document.forms[0][col].options[j].value > "") val += document.forms[0][col].options[j].value; 
	} 
	return val; 
} 
//--------------------------------------------------------- 
function doSub(hcampo) { 
	col = hcampo; 
	document.forms[0][hcampo+'_lst'].value = makeList(col); 
	return true; 
} 
//--------------------------------------------------------- 
function hide(whichLayer) { 
	if (document.getElementById) { 
		document.getElementById(whichLayer).style.visibility = "hidden"; 
	} else if (document.all) { 
		document.all[whichlayer].style.visibility = "hidden"; 
	} else if (document.layers) { 
		document.layers[whichLayer].visibility = "hidden"; 
	} 
} 
//--------------------------------------------------------- 
function show(whichLayer) { 
	if (document.getElementById) { 
		document.getElementById(whichLayer).style.visibility = "visible"; 
	} else if (document.all) { 
		document.all[whichlayer].style.visibility = "visible"; 
	}	else if (document.layers) { 
		document.layers[whichLayer].visibility = "visible"; 
	} 
} 
//--------------------------------------------------------- 
function OpenEditorHTML(ecampo,reservado){ 
	var altoPantalla = window.screen.height; 
	var anchoPantalla = window.screen.width; 
	var sPropsVentana; 
	var height = "520"; 
	var width = "780"; 
	var left = (anchoPantalla / 2) - (width / 2); 
	var top = (altoPantalla / 2) - (height / 2); 
	sPropsVentana  = 'width='+ width +',height='+ height; 
	sPropsVentana += ',top=' + top + ',left=' + left; 
	sPropsVentana += ', status=no,resizable=yes,scrollbars=no'; 
	if (reservado == "on"){ 
		reservado="&doreserved=on"; 
	} else { 
		reservado=""; 
	} 
	url = "editor/editor.php?campo=" + ecampo + reservado; 
	edito=window.open( url , "htmleditor",sPropsVentana); 
} 
//-------------------------------------- 
function orderModule(down, col) { 
 	sl = document.forms[0][col].selectedIndex; 
	if (sl != -1 && document.forms[0][col].options[sl].value > "") { 
		oText = document.forms[0][col].options[sl].text; 
		oValue = document.forms[0][col].options[sl].value; 
		if (document.forms[0][col].options[sl].value > "" && sl > 0 && down == 0) { 
			document.forms[0][col].options[sl].text = document.forms[0][col].options[sl-1].text; 
			document.forms[0][col].options[sl].value = document.forms[0][col].options[sl-1].value; 
			document.forms[0][col].options[sl-1].text = oText; 
			document.forms[0][col].options[sl-1].value = oValue; 
			document.forms[0][col].selectedIndex--; 
		} else if (sl < document.forms[0][col].length-1 && document.forms[0][col].options[sl+1].value > "" && down == 1) { 
			document.forms[0][col].options[sl].text = document.forms[0][col].options[sl+1].text; 
			document.forms[0][col].options[sl].value = document.forms[0][col].options[sl+1].value; 
			document.forms[0][col].options[sl+1].text = oText; 
			document.forms[0][col].options[sl+1].value = oValue; 
			document.forms[0][col].selectedIndex++; 
		} 
	} else { 
		alert("Seleccione un item"); 
	} 
} 
//-------------------------------------- 
function orderMenu(down, col) { 
	var haschild = 0; 
	var hadchild = 0; 
 	sl = document.forms[0][col].selectedIndex; 
	if (sl != -1 && document.forms[0][col].options[sl].value > "") { 
		oText = document.forms[0][col].options[sl].text; 
		oValue = document.forms[0][col].options[sl].value; 
		wsbmnu=document.forms[0][col].options[sl].value.split("~"); 
		mnparent = wsbmnu[0]; 
		mnchild = wsbmnu[1]; 
		mnparentstr = "p" + mnchild + "~"; 
		if (document.forms[0][col].options[sl].value > "" && sl > 0 && down == 0) { 
			if (mnparent != 'p0'){ 
				if ( document.forms[0][col].options[sl-1].value.indexOf('p0~') == -1){ 
					document.forms[0][col].options[sl].text = document.forms[0][col].options[sl-1].text; 
					document.forms[0][col].options[sl].value = document.forms[0][col].options[sl-1].value; 
					document.forms[0][col].options[sl-1].text = oText; 
					document.forms[0][col].options[sl-1].value = oValue; 
					document.forms[0][col].selectedIndex--; 
				} 
			} else { 
				if (sl < document.forms[0][col].length-1){ 
					if ( document.forms[0][col].options[sl+1].value.indexOf(mnparentstr) != -1){ 
						haschild = 1; 
					} 
				} 
				for (i=sl-1; i >= 0; i--){ 
					oText=  document.forms[0][col].options[i+1].text; 
					oValue = document.forms[0][col].options[i+1].value; 
					document.forms[0][col].selectedIndex--; 
					document.forms[0][col].options[i+1].text = document.forms[0][col].options[i].text; 
					document.forms[0][col].options[i+1].value = document.forms[0][col].options[i].value; 
					document.forms[0][col].options[i].text = oText; 
					document.forms[0][col].options[i].value = oValue; 
					sla = document.forms[0][col].selectedIndex; 
					if ( document.forms[0][col].options[i+1].value.indexOf('p0~') != -1){ 
						break; 
					} 
				} 
				if ( haschild==1 ){ 
					for (i=sl+1; i < document.forms[0][col].length ; i++){ 
						if ( document.forms[0][col].options[i].value.indexOf(mnparentstr) != -1){ 
							for (n=i-1; n > sla ; n--){ 
								oText=  document.forms[0][col].options[n].text; 
								oValue = document.forms[0][col].options[n].value; 
								document.forms[0][col].options[n].text = document.forms[0][col].options[n+1].text; 
								document.forms[0][col].options[n].value = document.forms[0][col].options[n+1].value; 
								document.forms[0][col].options[n+1].text = oText; 
								document.forms[0][col].options[n+1].value = oValue; 
							} 
							sla++; 
						} 
					} 
				} 
			} 
		} else if (sl < document.forms[0][col].length-1 && document.forms[0][col].options[sl+1].value > "" && down == 1) { 
			if (mnparent != 'p0'){ 
				if (document.forms[0][col].options[sl+1].value.indexOf('p0~') == -1){
					document.forms[0][col].options[sl].text = document.forms[0][col].options[sl+1].text; 
					document.forms[0][col].options[sl].value = document.forms[0][col].options[sl+1].value; 
					document.forms[0][col].options[sl+1].text = oText; 
					document.forms[0][col].options[sl+1].value = oValue; 
					document.forms[0][col].selectedIndex++; 
				} 
			} else { 
				var ismenu = 0; 
				if (sl < document.forms[0][col].length-1){ 
					if ( document.forms[0][col].options[sl+1].value.indexOf(mnparentstr) != -1){ 
						haschild = 1; 
					} 
				} 
				for (i=sl+1; i < document.forms[0][col].length; i++){ 
					oText=  document.forms[0][col].options[i-1].text; 
					oValue = document.forms[0][col].options[i-1].value; 
					document.forms[0][col].options[i-1].text = document.forms[0][col].options[i].text; 
					document.forms[0][col].options[i-1].value = document.forms[0][col].options[i].value; 
					document.forms[0][col].options[i].text = oText; 
					document.forms[0][col].options[i].value = oValue; 
					document.forms[0][col].selectedIndex++; 
					sla = document.forms[0][col].selectedIndex; 
					if ( (document.forms[0][col].options[i-1].value.indexOf('p0~') != -1)){ 
						ismenu = 1; 
					} 
					if (ismenu==1){ 
						if (i < document.forms[0][col].length-1){  
							if (document.forms[0][col].options[i+1].value.indexOf('p0~') != -1){ 
								break; 
							} 
						} else { 
							break; 
						} 
					} 
				} 
				if ( haschild==1 ){ 
					for (i=sl; i < document.forms[0][col].length ; i++){ 
						if ( document.forms[0][col].options[i].value.indexOf(mnparentstr) != -1){ 
							for (n=i+1; n <= sla ; n++){ 
								oText = document.forms[0][col].options[n].text; 
								oValue = document.forms[0][col].options[n].value; 
								document.forms[0][col].options[n].text = document.forms[0][col].options[n-1].text; 
								document.forms[0][col].options[n].value = document.forms[0][col].options[n-1].value; 
								document.forms[0][col].options[n-1].text = oText; 
								document.forms[0][col].options[n-1].value = oValue; 
							} 
							document.forms[0][col].selectedIndex--; 
						} else { 
							break; 
						} 
						i--; 
					} 
				} 
			} 
		} 
	} else { 
		alert("Seleccione un item"); 
	} 
} 
//--------------------------------------------------------- 
function makeMenuList(col) { 
	val = ""; 
	for (j=0; j<document.forms[0][col].length; j++) { 
		if (val > "") { 
			val += ","; 
		} 
		if (document.forms[0][col].options[j].value > "") val += document.forms[0][col].options[j].value; 
	} 
	return val; 
} 
//--------------------------------------------------------- 
function doMenuSub(hcampo) { 
	col = hcampo; 
	document.forms[0][hcampo+'_lst'].value = makeMenuList(col); 
	return true; 
} 
//--------------------------------------------------------- 
function DaysInMonth(WhichMonth, WhichYear){ 
	var DaysInMonth = 31; 
	if (WhichMonth == "4" || WhichMonth == "5" || WhichMonth == "9" || WhichMonth == "11") DaysInMonth = 30; 
	if (WhichMonth == "2" && (WhichYear/4) != Math.floor(WhichYear/4))	DaysInMonth = 28; 
	if (WhichMonth == "2" && (WhichYear/4) == Math.floor(WhichYear/4))	DaysInMonth = 29; 
	return DaysInMonth; 
} 
//-------------------------------------------------------------------- 
function ShowCalendar(campo_form){ 
	var ranNum= Math.floor(Math.random()*5); 
	var altoPantalla = window.screen.height; 
	var anchoPantalla = window.screen.width; 
	var sPropsVentana; 
	var height = "200"; 
	var width = "200"; 
	var left = (anchoPantalla / 2) - (width / 2); 
	var top = (altoPantalla / 2) - (height / 2); 
	sPropsVentana  = 'width=' + width + ',height=' + height; 
	sPropsVentana += ',top=' + top + ',left=' + left; 
	sPropsVentana += ', status=no,resizable=no,scrollbars=no'; 
	url = "popcalendar.php?campo=" + campo_form; 
	calendario=window.open( url , "cal_" + ranNum + "_ar" , sPropsVentana); 
} 
//-------------------------------------------------------------------- 
function helpwindow(topic,type,hindex){ 
	width=235; 
	height=400; 
	if (hindex!=""){ 
		indice="&hindex=" + hindex; 
	} else { 
		indice = ""; 
	} 
	if (topic!=""){ 
		topico="&htopic=" + topic; 
	} else { 
		topico = ""; 
	} 
	if (type!=""){ 
		tipo="?tipo=" + type; 
	} else { 
		tipo="?tipo=" 
	} 
 
	url = "help.php" + tipo + topico + indice; 
	winh=window.open(url,"","width="+width+", height="+height+", status=no, resizable=no, scrollbars=yes"); 
} 
//-------------------------------------------------------------------- 
function IsNumeric(valor){ 
	var log=valor.length; var sw="S"; 
	for (x=0; x<log; x++){ 
		v1=valor.substr(x,1); 
		v2 = parseInt(v1); 
	// --------------------------------- 
	//	Compruebo si es un valor numérico 
	// --------------------------------- 
	if (isNaN(v2)){sw= "N";} 
	} 
	if (sw=="S") {return true;} else {return false; } 
	} 
	 
	var primerslap=false; 
	var segundoslap=false; 
//-------------------------------------------------------------------- 
function formateahora(fecha){ 
	var long = fecha.length; 
	var hora; 
	var minuto; 
	 
	if ((long>=2) && (primerslap==false)) { hora=fecha.substr(0,2); 
	if ((IsNumeric(hora)==true) && (hora<=24)) { fecha=fecha.substr(0,2)+":"+fecha.substr(3,5); primerslap=true; } 
	else { fecha=""; primerslap=false;} 
	} 
	else 
	{ dia=fecha.substr(0,1); 
	if (IsNumeric(dia)==false) 
	{fecha="";} 
	if ((long<=2) && (primerslap=true)) {fecha=fecha.substr(0,1); primerslap=false; } 
	} 
	if ((long>=5) && (segundoslap==false)) 
	{ minuto=fecha.substr(3,2); 
	if ((IsNumeric(minuto)==true) &&(minuto<=59)) { fecha=fecha.substr(0,5); segundoslap=true; } 
	else { fecha=fecha.substr(0,3);; segundoslap=false;} 
	} 
	else { if ((long<=5) && (segundoslap=true)) { fecha=fecha.substr(0,4); segundoslap=false; } } 
	 
	return (fecha); 
} 
//-------------------------------------------------------------------- 

var primerslap=false;
var segundoslap=false;
function formateafecha(fecha) {
	var long = fecha.length;
	var dia;
	var mes;
	var ano;
	var separator="/"

	if ((long>=2) && (primerslap==false)) { 
		dia=fecha.substr(0,2);
		if ((IsNumeric(dia)==true) && (dia<=31) && (dia!="00")) { 
			fecha=fecha.substr(0,2)+ separator +fecha.substr(3,7); 
			primerslap=true; 
		} else { 
			fecha=""; 
			primerslap=false;
		}
	} else { 
		dia=fecha.substr(0,1);
		if (IsNumeric(dia)==false){
			fecha="";
		}
		if ((long<=2) && (primerslap=true)) {
			fecha=fecha.substr(0,1); 
			primerslap=false; 
		}
	}
	if ((long>=5) && (segundoslap==false)) { 
		mes=fecha.substr(3,2);
		if ((IsNumeric(mes)==true) &&(mes<=12) && (mes!="00")) { 
			fecha=fecha.substr(0,5)+separator+fecha.substr(6,4); 
			segundoslap=true; 
		} else { 
			fecha=fecha.substr(0,3);
			segundoslap=false;
		}
	} else { 
		if ((long<=5) && (segundoslap=true)) { 
			fecha=fecha.substr(0,4); 
			segundoslap=false; 
		} 
	}
	if (long>=7) { 
		ano=fecha.substr(6,4);
		if (IsNumeric(ano)==false) { 
			fecha=fecha.substr(0,6); 
		} else { 
			if (long==10){ 
				if ((ano==0) || (ano<1900) || (ano>2100)) { 
					fecha=fecha.substr(0,6); 
				} 
			} 
		}
	}
	if (long>=10) {
		fecha=fecha.substr(0,10);
		dia=fecha.substr(0,2);
		mes=fecha.substr(3,2);
		ano=fecha.substr(6,4);
		// Anioo no viciesto y es febrero y el dia es mayor a 28
		if ( (ano%4 != 0) && (mes ==02) && (dia > 28) ) { 
			fecha=fecha.substr(0,2)+separator; 
		}
	}
	return (fecha);
}
 
