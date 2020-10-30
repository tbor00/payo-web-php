//-----------------------------------------------------
// VARIABLES DEL FORMULARIO 
//-----------------------------------------------------
// NombreForm : 'Nombre del formulario' 
// Campos : Array 
//				new Array(NumeroCampos);
// Campo[x]: Construccion de campos 
//				new Campo('nombre campo', longitud minima, 'texto del mensaje', 'tipo de campo', condicion);
//				nombre de campo : Texto
//				longitud minima : Numero
//				texto del mensaje: Texto
//			   tipo de campo : Texto ('text','num','date','mail','radio','time','texnum','select','list')
//				condicion: condicion a evaluar para aplicar el chequeo

//-----------------------------------------------------
//   SETEO DE MENSAJES
//-----------------------------------------------------
var LengthError = ("Debe completar correctamente ");
var NumError = ("Debe ingresar un valor numérico ");
var NumCeroError = ("Debe ingresar un valor > a cero ");
var DateError = ("Debe ingresar una fecha válida ");
var EmailError = ("Debe ingresar un mail válido ");
var RadioError = ("Debe seleccionar una opción ");
var SelectError = ("Debe seleccionar una opción ");
var TimeError = ("Debe ingresar una hora válida ");
var TimeErrorFormat = ("El formato debe ser HH:MM");
var sTimeErrorFormat = ("El formato debe ser MM:SS");
var DateErrorFormat = ("El formato debe ser DD/MM/YYYY");
var ListError = ("Debe seleccionar al menos un ");
var FileError = "Debe selecionar un archivo para ";
var TxtLengthMin = "La longitud mínima es de ";
var TxtCaracters = " caracteres."; 

var Campos = new Array();


//-----------------------------------------------------
//   CHEQUEO DE CAMPOS DEL FORMULARIO
//-----------------------------------------------------
function ChequeaForm(formname) {
	for (var i=0; i<Campos.length; i++) {	
		var Value = formname.elements[Campos[i].nombre].value;
		var Condi;
		if (Campos[i].condicion == '') {
			Condi = true;
		} else {
			Condi = eval(Campos[i].condicion);
		}
		if (Condi == true) {
			if (Campos[i].tipo == 'text'){
				if (Campos[i].minlength > 0) {
				   if (Value.length < Campos[i].minlength) {
						alert(LengthError + Campos[i].mensaje + '\n' + TxtLengthMin + Campos[i].minlength + TxtCaracters);
						formname.elements[Campos[i].nombre].focus();
 						return false;
  				   }
				}
			} else if (Campos[i].tipo == 'file'){
				if (Campos[i].minlength > 0) {
				   if (Value.length < Campos[i].minlength) {
						alert (FileError + Campos[i].mensaje);
						formname.elements[Campos[i].nombre].focus();
 						return false;
  				   }
				}
	   	} else if (Campos[i].tipo == 'num' ){
		  		if (isNaN(parseFloat(Value))) {
							alert(NumError + Campos[i].mensaje);
							formname.elements[Campos[i].nombre].focus();
							return false;
		  		} else {
			  		if (parseFloat(Value)/1 <= 0) {
							alert(NumCeroError + Campos[i].mensaje);
							formname.elements[Campos[i].nombre].focus();
							return false;
					}
				}
	   	} else if (Campos[i].tipo == 'select' ){
		  		if (isNaN(parseFloat(Value))) {
		  		    if (Value == '') {
		      			alert(SelectError + Campos[i].mensaje);
							formname.elements[Campos[i].nombre].focus();
		      			return false;
		  		    }
				} else {    
		  		    if (Value == 0) {
		      			alert(SelectError + Campos[i].mensaje);
							formname.elements[Campos[i].nombre].focus();
		      			return false;
		  		    }
				}
	   	} else if (Campos[i].tipo == 'textnum' ){
		  		if (Value.length < Campos[i].minlength) {
		      		alert (LengthError + Campos[i].mensaje);
				    	formname.elements[Campos[i].nombre].focus();
				    	return false;
		  		}
		  		if (isNaN(parseFloat(Value))) {
		      		alert(NumError + Campos[i].mensaje);
				    	formname.elements[Campos[i].nombre].focus();
		      		return false;
		  		}
	   	} else if (Campos[i].tipo == 'date' ){
		  		if (!validDate(Value)) {
		      		alert(DateError + Campos[i].mensaje +"\n" + DateErrorFormat);
				    	formname.elements[Campos[i].nombre].focus();
		      		return false;
		  		}
	   	} else if (Campos[i].tipo == 'time' ){
		  		if (!validTime(Value)) {
		      	alert(TimeError + Campos[i].mensaje +"\n" + TimeErrorFormat);
				   formname.elements[Campos[i].nombre].focus();
		      	return false;
		  		}
	   	} else if (Campos[i].tipo == 'stime' ){
		  		if (!validsTime(Value)) {
		      	alert(TimeError + Campos[i].mensaje +"\n" + sTimeErrorFormat);
				   formname.elements[Campos[i].nombre].focus();
		      	return false;
		  		}
			} else if (Campos[i].tipo == 'mail' ){
		 		if (Value.indexOf('@') == -1) {
		  			alert (EmailError + Campos[i].mensaje);
					formname.elements[Campos[i].nombre].focus();
		  			return false;
		  		}
		 		if (Value.indexOf('.') == -1) {
		  			alert (EmailError + Campos[i].mensaje);
					formname.elements[Campos[i].nombre].focus();
		  			return false;
		  		}
			} else if (Campos[i].tipo == 'radio' ){
  				var radioSelected = false;
  				for (j = 0;  j < formname.elements[Campos[i].nombre].length;  j++) {
    				if (formname.elements[Campos[i].nombre][j].checked) {
        				radioSelected = true;			  
					}
  				}
  				if (!radioSelected)	{
    				alert (RadioError + Campos[i].mensaje);
    				return (false);
  				}
	   	} else if (Campos[i].tipo == 'list' ){
				   if (Value == '') {
						alert (ListError + Campos[i].mensaje);
 						return false;
  				   }
			}
		}
  	}
	return true;
}

//-----------------------------------------------------
//   ASIGNACION DE VALORES AL ARRAY CAMPOS
//-----------------------------------------------------
function AddCampo(nombre, minlength, mensaje, tipo,condicion) {
   var z = Campos.length;
   Campos[z] = new Campo(nombre, minlength, mensaje, tipo,condicion);
}

//-----------------------------------------------------
//   CONSTRUCCION DE CAMPOS
//-----------------------------------------------------
function Campo(nombre, minlength, mensaje, tipo,condicion) {
	this.nombre=nombre;
	this.minlength=minlength;
	this.mensaje=mensaje;
	this.tipo = tipo;
	this.condicion = condicion;
}

//-----------------------------------------------------
//   VALIDACION DE FECHAS
//-----------------------------------------------------
function validDate(s) {
	if (s.length < 10 || s.length > 10) {
		return false;
	}    
	var dateString = stripCharsInBag(s,"-/ ");
	var dd = dateString.substring(0,2);
	var mm = dateString.substring(2,4);
	var yyyy = dateString.substring(4,s.length);
	if (!isMonth(mm)) return false;
	if (!isDay(dd,mm,yyyy)) return false;
	if (!isYear(yyyy)) return false;
	return true;
}
//-----------------------------------------------------
function isIntegerInRange (s, a, b){
	if (isEmpty(s)) return false;    
	var num = parseInt (s, 10);
	return ((num >= a) && (num <= b));
}
//-----------------------------------------------------
function isEmpty(s) {
	return ((s == null) || (s.length == 0))
}
//-----------------------------------------------------
function isYear (s){
	if (isEmpty(s)) return false; 
	if (isNaN(parseInt(s))) return false;
	return isIntegerInRange (s, 1000, 9999);
}
//-----------------------------------------------------
function isMonth (s){
	if (isEmpty(s)) return false;	
	return isIntegerInRange (s, 1, 12);
}
//-----------------------------------------------------
function isHour (s){
	if (isEmpty(s)) return false;	
	return isIntegerInRange (s, 0, 23);
}
//-----------------------------------------------------
function isMinute (s){
	if (isEmpty(s)) return false;	
	return isIntegerInRange (s, 0, 59);
}
//-----------------------------------------------------
function isSecond (s){
	if (isEmpty(s)) return false;	
	return isIntegerInRange (s, 0, 59);
}
//-----------------------------------------------------
function isDay (s,month,year){
	if (isEmpty(s)) return false; 
	var uday = 30;
   if ((month == 1) || (month == 3) || (month ==5) || (month == 7) || (month == 8) || (month == 10) || (month == 12)) {
   	uday = 31;
	}
	if (month == 2) {
		uday = daysInFebruary (year)
	}	
	return isIntegerInRange (s, 1, uday);
}
//-----------------------------------------------------
function daysInFebruary (year){
	return (  ((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0) ) ) ? 29 : 28 );
}
//-----------------------------------------------------
function stripCharsInBag (s, bag){
	var i;
   var returnString = "";
   for (i = 0; i < s.length; i++)  {   
		var c = s.charAt(i);
		if (bag.indexOf(c) == -1) returnString += c;
   }
   return returnString;
}
//-----------------------------------------------------
function validTime(s) {
	if (s.length < 5 || s.length > 5) {
		return false;
	}    
	var dateString = stripCharsInBag(s,": ");
	var hh = dateString.substring(0,2);
	var mm = dateString.substring(2,4);
	if (!isHour(hh)) return false;
	if (!isMinute(mm)) return false;
	return true;
}
//-----------------------------------------------------
function validsTime(s) {
	if (s.length < 4 || s.length > 5) {
		return false;
	}   
   if (s.indexOf(':') == -1) {
		return false;
	}
   if (s.indexOf(' ') != -1) {
		return false;
	}
	var mm = s.substring(0,s.indexOf(':'));
	var ss = s.substring(s.indexOf(':')+1,4);
	//if (!isMinute(mm)) return false;
	if (!isSecond(ss)) return false;
	return true;
}
//-----------------------------------------------------
//-----------------------------------------------------
//-----------------------------------------------------

