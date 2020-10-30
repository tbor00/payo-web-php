<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
$file_size = $_GET[file_size];
$imgtype = $_GET[imgtype];

if (strlen($_GET[dest_fold]) > 0){
	$destination_folder = $_GET[dest_fold];
} else {
	$destination_folder = "../imagenes/";
}
if (strlen($filesize) > 0){
	$file_size = $filesize;
}
?>
<HTML>
<HEAD> 
<TITLE>Selecci&oacute;n de Imagenes</TITLE>
<STYLE TYPE="text/css"><!--
	*			{ font-family:MS Sans Serif,Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
		.colorTable		{ cursor:hand; }
	-->
</STYLE>
<SCRIPT>
var vista='list';
//--------------------------------------------------------
function CheckForm() {
	if (document.imgn.imagen_name.value == "" ) {
 		return false;
	}
	return true;
}
//--------------------------------------------------------
function Volver(){
	if (CheckForm()) {
		window.returnValue = document.imgn.imagen_name.value;
		var parentDoc = parent.document;
		var dialogElms = parentDoc.getElementsByTagName('dialog');
		var firstDialog = dialogElms[0];
		firstDialog.close();
	}
}
//--------------------------------------------------------
function Cancelar(){
		window.returnValue = "";
		var parentDoc = parent.document;
		var dialogElms = parentDoc.getElementsByTagName('dialog');
		var firstDialog = dialogElms[0];
		firstDialog.close();
}
//--------------------------------------------------------
function UploadImage() {
	var urlp = "upload.php?vista=" + vista + "&dest_fold=<?php echo $destination_folder ?>&file_size=<?php echo $file_size ?>&imgtype=<?php echo $imgtype ?>";
	var imgupl = window.open(urlp,"","height=180,width=400,toolbar=no,resizable=no,location=no,scrollbars=no,status=no,top=100,left=300")
}
//--------------------------------------------------------
function CambiaModo(modo){
	var parentDoc = parent.document;
	var ImagesFrame = parentDoc.getElementsByTagName('imagesFrame');
	if (modo==1) {
		var vista = 'thumb';
		ImagesFrame.location="images.php?vista=thumb&dest_fold=<?php echo $destination_folder ?>";
		//document.frames("images").location="images.php?vista=thumb&dest_fold=<?php echo $destination_folder ?>";
		//ImagesFrame.location.reload();
	} else {
		var vista = 'list';
		ImagesFrame.location="images.php?vista=thumb&dest_fold=<?php echo $destination_folder ?>";
		//document.frames("images").location="images.php?vista=lista&dest_fold=<?php echo $destination_folder ?>";
		//ImagesFrame.location.reload();
	}
}
//--------------------------------------------------------
</SCRIPT> 
</HEAD>
<BODY BGCOLOR="#E6E6E6"> 
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="2"> 
<TR> 
<TD>
&nbsp;<INPUT TYPE="IMAGE" SRC="img/upload.gif" BORDER="0" WIDTH="20" HEIGHT="20" ALT="Subir imagen" ONCLICK="UploadImage()">
<!-- &nbsp;<INPUT TYPE="IMAGE" SRC="img/icon_list.gif" BORDER="0" WIDTH="20" HEIGHT="20" ALT="Lista" ONCLICK="CambiaModo(0)">
&nbsp;<INPUT TYPE="IMAGE" SRC="img/icon_thumb.gif" BORDER="0" WIDTH="20" HEIGHT="20" ALT="Vistas en miniatura" ONCLICK="CambiaModo(1)"> -->
</TD> 
</TR> 
</TABLE>
<IFRAME HEIGHT="205" WIDTH="100%" SRC="images.php?vista=list&dest_fold=<?php echo $destination_folder ?>" NAME="imagesFrame" ID="imagesFrame"></IFRAME> 
<FORM NAME="imgn" STYLE="margin-top: 0pt; margin-bottom: 0pt"> 
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD> Imagen: <INPUT TYPE="TEXT" NAME="imagen_name" SIZE="60"></TD> 
<TD><INPUT STYLE="width:70px" TYPE="BUTTON" NAME="aceptar" VALUE="Aceptar" ID="Aceptar" ONCLICK="Volver()"></TD> 
</TR> 
<TR> 
<TD>&nbsp;</TD> 
<TD><INPUT STYLE="width:70px" TYPE="button" VALUE="Cancelar" ID="Cerrar" ONCLICK="Cancelar();"></TD> 	   
</TR> 
</TABLE>
</FORM>
</BODY>
</HTML>
