<?
require_once("include/config.inc.php");
require_once("include/file.lib.php");

//--------------------------------------------------------------------

$dirname = dir_slash($img_dir);
if ($passdir==""){
	$passdir = $dirname;
} else {
	$passdir = dir_slash($passdir);
} 


$input_field_name = "picturefile";
$accepted_mime_types = $img_mime;
$destination_folder = dir_slash($passdir);
$overwrite = $img_overwrite;

$upload = new upload($input_field_name);
$upload->set_max_file_size($img_max_size);
//$upload->set_max_image_size($img_max_width, $img_max_height);
$upload->set_accepted_mime_types($accepted_mime_types);

$url="upload.php?type=$type&passdir=$destination_folder";
?>
<HTML>
<HEAD>
<TITLE>Subir Archivo</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8 ?>">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<SCRIPT>
<?
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
   $result = $upload->security_check();
	if ($result == true){
		$result = $upload->move($destination_folder, $overwrite);
	}
   if ($result == true){
		echo "var refreshW=1;\n";
		echo "var uperror='';\n";
	}else{
	   echo "var refreshW=0;\n";
		echo "var uperror='$upload->error_msg';\n";
	}
} else {
	echo "var refreshW=0;\n";
	echo "var uperror='';\n";
}
?>
//------------------------------------------------------------------------
function Do_close() {
   if (refreshW == 1) {
		tinyMCEPopup.getWindowArg("frame").src="files.php?type=<? echo $type ?>&passdir=<? echo $destination_folder ?>";
		tinyMCEPopup.alert("El archivo se ha subido con exito!");
		tinyMCEPopup.close();
	} else {
		if (uperror!=''){
			tinyMCEPopup.alert(uperror);
			tinyMCEPopup.close();
		}
	}
}
//------------------------------------------------------------------------
</SCRIPT>
</HEAD>
<BODY BGCOLOR="buttonface" Onload="Do_close();"> 
<SCRIPT>
function uploading(){
   document.getElementById('FM1').style.visibility='hidden';
   document.getElementById('FM2').style.visibility='visible';
   return true;
}
</SCRIPT>
<DIV ID='FM1' STYLE='visibility=visible'>
<FORM ENCTYPE="multipart/form-data" ACTION="<? echo $url ?>" METHOD="post" ONSUBMIT="return uploading();">
<B>Archivo:</B> <BR>
<INPUT SIZE='30' NAME="<? echo $upload->input_field_name ?>" TYPE="file" CLASS="">
<BR><BR>
<INPUT TYPE="submit" VALUE="Aceptar" CLASS="button">
<?
if ($upload->max_file_size > 0 ){
	echo "<BR><BR>Tama&ntilde;o m&aacute;ximo: ". round($upload->max_file_size / 1024, 2) . "Kb";
}
if ($upload->max_image_width > 0 ) {
	if ($upload->max_file_size > 0 ){
		echo " &oacute; ";
	} else {
		echo "<BR><BR>Tama&ntilde;o m&aacute;ximo: ";
	}
	echo "". $upload->max_image_width . "x" . $upload->max_image_height . " pixeles";
}
?>
</FORM></DIV>
<DIV ID="FM2" ALIGN="CENTER" STYLE="position:absolute;left:120 px;top:60 px;visibility:hidden;">
<P ALIGN="CENTER"><STRONG STYLE="color:red">Espere por favor...</STRONG></P>
</DIV>
</BODY>
</HTML>

