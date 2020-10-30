//--------------------------------------------------------- 
function newwindow2(lista,url,width,heigth,accion) { 
 	var valor; 
	var word; 
	valor=showModalDialog(url, message ,"dialogWidth:"+width+"px; dialogHeight:"+heigth+"px; help: 0; status: 0; resizable:0; center:1"); 
	if( valor == false || valor == "" || message) { 
		return; 
	} else { 
		if(accion=='a'){ 
			//alert('ag'+valor); 
			word=valor.split(";") 
			//alert(word[3]); 
			agregar(word[3],valor,lista); 
		}else{ 
			//alert('mod'+valor); 
			word=valor.split(";") 
			//alert(word[3]); 
			modificaritem(word[3],valor,lista); 
		} 
	} 
} 
//--------------------------------------------------------- 
function popupimagen(url,width,heigth,lista){ 
	var word; 
	var i; 
	i = window.document.forms[0][lista].options.selectedIndex; 
	if(i!=-1){ 
		seleccion=window.document.forms[0][lista].options[i]; 
		word=seleccion.value.split(";") 
		url2=url+'?id='+word[0]+'&id_multimedia='+word[1]+'&tipo=gral'+'&lista='+lista; 
		newwindow(url2,width,heigth); 
	} 
} 
//--------------------------------------------------------- 
function showcombotip(coli){ 
	if (document.forms[0][coli].selectedIndex > -1) { 
		sli = document.forms[0][coli].selectedIndex; 
		topp= DL_GetElementTop(document.forms[0][coli]); 
		leftp=DL_GetElementLeft(document.forms[0][coli])+10; 
    	oText = document.forms[0][coli].options[sli].text; 
		showtip(coli,event,oText,topp,leftp); 
	} 
} 
//--------------------------------------------------------- 
function DL_GetElementLeft(eElement) { 
	var nLeftPos = eElement.offsetLeft;       
	var eParElement = eElement.offsetParent;  
	while (eParElement != null) {                                         
		nLeftPos += eParElement.offsetLeft;   
		eParElement = eParElement.offsetParent; 
	} 
	return nLeftPos;                            
} 
//--------------------------------------------------------- 
function DL_GetElementTop(eElement) { 
	var nTopPos = eElement.offsetTop;          
	var eParElement = eElement.offsetParent;   
	while (eParElement != null) {              
		nTopPos += eParElement.offsetTop;       
		eParElement = eParElement.offsetParent; 
	} 
	return nTopPos;                            
} 
//--------------------------------------------------------- 
function hidetip(){ 
	if (document.all||document.getElementById){ 
	  	document.all.tooltip.style.visibility="hidden"; 
		clearTimeout(TTtime); 
	} else if (document.layers){ 
		document.tooltip.visibility="hidden"; 
		clearTimeout(TTtime); 
	} 
} 
//--------------------------------------------------------- 
function showtip(current,e,text,topp,leftp){ 
   var lifetime = 2000; 
 
  	if (TTtime) { 
		clearTimeout(TTtime); 
	} 
	 
	if (document.all||document.getElementById){ 
		document.all.tooltip.innerHTML=text; 
		document.all.tooltip.style.padding = '2px'; 
		document.all.tooltip.style.border="1px solid black"; 
		document.all.tooltip.style.zIndex=9999; 
		if (topp==0) { 
			document.all.tooltip.style.pixelTop=e.clientY+document.body.scrollTop+10; 
		} else { 
			document.all.tooltip.innerHTML=text; 
			document.all.tooltip.style.pixelTop=topp - (document.all.tooltip.offsetHeight+4); 
		} 
		if (leftp==0) { 
			document.all.tooltip.style.pixelLeft=e.clientX+document.body.scrollLeft+10; 
		} else { 
			document.all.tooltip.style.pixelLeft=leftp; 
		} 
		document.all.tooltip.style.visibility="visible"; 
		TTtime = setTimeout(function() { hidetip()} ,lifetime); 
 
 
	} else if (document.layers){ 
		document.tooltip.document.write('<layer bgColor="white" style="border:1px solid black;font-size:12px;">'+text+'</layer>') 
		document.tooltip.document.close() 
		document.tooltip.left=e.pageX+5 
		document.tooltip.top=e.pageY+5 
		document.tooltip.visibility="show" 
	} 
} 
//--------------------------------------------------------- 
function newwindow3(url,width,heigth) { 
	var altoPantalla = window.screen.height; 
	var anchoPantalla = window.screen.width; 
	var sPropsVentana; 
	var left = (anchoPantalla / 2) - (width / 2); 
	var top = (altoPantalla / 2) - (heigth / 2); 
	sPropsVentana  = 'width='+ width +',height='+ heigth; 
	sPropsVentana += ',top=' + top + ',left=' + left; 
	sPropsVentana += ', , status=no, resizable=yes, scrollbars=yes'; 
	win1=	window.open( url , "",sPropsVentana); 
} 
//--------------------------------------------------------- 
function agregar(mensaje,value,lista){ 
	var sizelist; 
	sizelist=window.document.forms[0][lista].options.length; 
	if(window.document.forms[0][lista].options[sizelist]==null){ 
		window.document.forms[0][lista].options[sizelist]=new Option(mensaje, value , 1, 1); 
	} 
} 
//--------------------------------------------------------- 
function eliminar(lista,i){ 
		window.document.forms[0][lista].options[i]=null; 
} 
//--------------------------------------------------------- 
function confirmar(lista) { 
	var i; 
	var texto; 
	i=window.document.forms[0][lista].options.selectedIndex; 
	if(i!=-1){ 
		texto=window.document.forms[0][lista].options[i].text; 
		var is_confirmed = confirm('Esta seguro que desea eliminar :\n' + texto +'?'); 
		if (is_confirmed) { 
			eliminar(lista,i); 
		} 
    	return is_confirmed; 
	} 
} 
//--------------------------------------------------------- 
function additemstoparent(valor) { 
	//	alert(valor); 
	window.returnValue=valor; 
	window.close(); 
} 
//--------------------------------------------------------- 
function modificaritem(texto,valor,lista){ 
	seleccion = window.document.forms[0][lista].options[window.document.forms[0][lista].options.selectedIndex]; 
	seleccion.text=texto; 
	seleccion.value=valor; 
}
//---------------------------------------------------------
function newwindow(url,width,heigth) {
	var altoPantalla = window.screen.height;
	var anchoPantalla = window.screen.width;
	var sPropsVentana;
	var left = (anchoPantalla / 2) - (width / 2);
	var top = (altoPantalla / 2) - (heigth / 2);
	sPropsVentana  = 'width='+ width +',height='+ heigth;
	sPropsVentana += ',top=' + top + ',left=' + left;
	sPropsVentana += ', status=yes,resizable=no';
	win1=	window.open( url , "",sPropsVentana);
}
//--------------------------------------------------------- 
function toggleBox(szDivID, iState) { // 1 visible, 0 hidden 
	if(document.layers) {  //NN4+ 
		document.layers[szDivID].visibility = iState ? "show" : "hide"; 
	} else if(document.getElementById) { //gecko(NN6) + IE 5+ 
		var obj = document.getElementById(szDivID); 
		obj.style.visibility = iState ? "visible" : "hidden"; 
	} else if(document.all) { // IE 4 
		document.all[szDivID].style.visibility = iState ? "visible" : "hidden"; 
	} 
} 
//--------------------------------------------------------- 
function ChangeOptionDays(Which){ 
	DaysObject = eval("document.forms[0]." + Which + "day"); 
	MonthObject = eval("document.forms[0]." + Which + "month"); 
	YearObject = eval("document.forms[0]." + Which + "year"); 
	Month = MonthObject[MonthObject.selectedIndex].text; 
	Year = YearObject[YearObject.selectedIndex].text; 
	DaysForThisSelection = DaysInMonth(Month, Year); 
	CurrentDaysInSelection = DaysObject.length - 1; 
	if (CurrentDaysInSelection > DaysForThisSelection)  { 
		for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++){ 
			DaysObject.options[DaysObject.options.length - 1] = null 
		} 
	} 
	if (DaysForThisSelection > CurrentDaysInSelection){ 
		for (i=0; i<(DaysForThisSelection-CurrentDaysInSelection); i++){ 
      	NewOption = new Option(DaysObject.options.length); 
      	DaysObject.add(NewOption); 
		} 
	} 
	if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex == 0; 
} 
  
//--------------------------------------------------------- 
