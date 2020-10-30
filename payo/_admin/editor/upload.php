<?php
require_once("include/config.inc.php");
require_once("include/file.lib.php");
require_once("include/functions.lib.php");
require_once("include/login_check.inc.php");
//--------------------------------------------------------------------
$type = $_GET['type'];
$passdir = $_GET['passdir'];

if ($type!=""){
	if ($type=="image"){
		$accepted_files = array('images');
		$overwrite = $img_overwrite;
		$dirname = $img_dir;
		$max_size = $img_max_size;
	}
	if ($type=="media"){
		$accepted_files = array('adobe_flash','windows_media','real_media','mp3_media');
		$overwrite = $mm_overwrite;
		$dirname = $mm_dir;
		$max_size = $mm_max_size;
	}
	if ($type=="file"){
		$accepted_files = array('images','html','ms_excel','ms-word','ms_powerpoint','adobe_pdf','text');
		$overwrite = $file_overwrite;
		$dirname = $file_dir;
		$max_size = $file_max_size;
	}
}



$dirname=dir_slash($dirname);
if ($passdir==""){
	$passdir = dir_slash($dirname);
} else {
	$passdir = dir_slash($passdir);
} 
$destination_folder = dir_slash($passdir);

$upload = new upload("uploadfile");
$upload->set_max_file_size($max_size);
$upload->set_file_type($accepted_files);

?>
<HTML>
<HEAD>
<TITLE>Subir Archivo</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<SCRIPT>
<?php
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
		var win = tinyMCEPopup.getWindowArg("win");
		var objFunc = tinyMCEPopup.getWindowArg("objFunc");
		if (typeof(objFunc) != "undefined"){
			if (objFunc.refreshBrowse) objFunc.refreshBrowse();
		}
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
<FORM ENCTYPE="multipart/form-data" ACTION="<?php echo "{$_SERVER['PHP_SELF']}?type={$type}&passdir={$destination_folder}" ?>" METHOD="post" ONSUBMIT="return uploading();">
<BR>
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD ALIGN="CENTER" COLSPAN="2"><B>Archivo:&nbsp;</B><INPUT SIZE='30' NAME="<?php echo $upload->input_field_name ?>" TYPE="file" CLASS=""></TD>
</TR>
<?php
if ($upload->max_file_size > 0 ){
	echo "<TR><TD COLSPAN=\"2\">";
	echo "Tama&ntilde;o m&aacute;ximo: ". round($upload->max_file_size / 1024, 2) . "Kb";
	echo "</TD></TR>";
}
?>
<TR> 
<TD ALIGN="RIGHT">&nbsp;</TD>
<TD ALIGN="LEFT">&nbsp;</TD>
<TR>
<TD ALIGN="CENTER"><INPUT TYPE="SUBMIT" ID="insert" NAME="insert" VALUE="Aceptar"></TD> 
<TD ALIGN="CENTER"><INPUT ID="cancel" TYPE="button" name="cancel" VALUE="Cancelar" ONCLICK="tinyMCEPopup.close();"></TD> 	   
</TR>
</TABLE>
</FORM></DIV>
<DIV ID="FM2" ALIGN="CENTER" STYLE="position:absolute;left:120 px;top:60 px;visibility:hidden;">
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0"> 
<TR>
<TD><IMG SRC="images/progress.gif" BORDER="0" WIDTH="32" HEIGHT="32" ALT="Cargando"></TD><TD>Espere por favor...</TD>
</TR>
</TABLE>
</DIV>
</BODY>
</HTML>

