<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
$file_size = $_GET[file_size];
$imgtype = $_GET[imgtype];

if (strlen($_GET[dest_fold]) > 0){
	$destination_folder = $_GET[dest_fold];
} else {
	$destination_folder = "../products/imagenes/";
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
function UploadImage() {
	var urlp = "pupload.php?vista=" + vista + "&dest_fold=<?php echo $destination_folder ?>&file_size=<?php echo $file_size ?>&imgtype=<?php echo $imgtype ?>";
	val = window.open(urlp,"","height=640,width=540,toolbar=no,resizable=no,location=no,scrollbars=no,status=no")
}
</SCRIPT> 
</HEAD>
<BODY BGCOLOR="ButtonFace" LEFTMARGIN="0" MARGINWIDTH="0" style="background-color:ButtonFace;"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2"> 
<TR><TD><INPUT TYPE="IMAGE" SRC="img/upload.gif" BORDER="0" WIDTH="20" HEIGHT="20" ALT="Subir imagen" ONCLICK="UploadImage()"></TD></TR> 
</TABLE>
<IFRAME HEIGHT="330" WIDTH="100%" SRC="pimages.php?vista=list&dest_fold=<?php echo $destination_folder ?>" NAME="images" ID="images"></IFRAME> 
<FORM NAME="imgn" STYLE="margin-top: 0pt; margin-bottom: 0pt"> 
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD></TD> 	   
</TR> 
</TABLE>
</FORM>
</BODY>
</HTML>
