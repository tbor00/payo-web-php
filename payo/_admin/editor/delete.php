<?php
require_once("include/config.inc.php");
require_once("include/file.lib.php");
require_once("include/login_check.inc.php");

//--------------------------------------------------------------------

$type = $_GET['type'];
$passdir = $_GET['passdir'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$file2delete = $_POST['file2delete'];
} else {
	$file2delete = $_GET['file2delete'];
}

$dirname=dir_slash($dirname);
if ($passdir==""){
	$passdir = dir_slash($dirname);
} else {
	$passdir = dir_slash($passdir);
} 
$filename=parse_url($file2delete, PHP_URL_PATH);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Eliminar Archivo</TITLE>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$current_path = dir_slash(dirname(__FILE__));
	$current_file = basename($filename);
	if ($file2delete = realpath($current_path.$passdir.$current_file)){
	   $result = unlink($file2delete);
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
   if ($result == true){
		echo "var refreshW=1;\n";
		echo "var uperror='';\n";
	}else{
	   echo "var refreshW=0;\n";
		echo "var uperror='Error: No se pudo eliminar el archivo';\n";
	}
	echo "</SCRIPT>\n";
} else {
	echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
	echo "var refreshW=0;\n";
	echo "var uperror='';\n";
	echo "</SCRIPT>\n";
}
?>
<SCRIPT LANGUAGE="JavaScript">
function Do_Close() {
	var win = tinyMCEPopup.getWindowArg("win");
   if (refreshW == 1) {
		var objFunc = tinyMCEPopup.getWindowArg("objFunc");
		if (typeof(objFunc) != "undefined"){
			if (objFunc.refreshBrowse) objFunc.refreshBrowse();
		}
		tinyMCEPopup.alert("El archivo se ha eliminado con exito!");
		tinyMCEPopup.close();
		return;
	} else {
		if (uperror!=''){
			tinyMCEPopup.alert(uperror);
			tinyMCEPopup.close();
			return;
		}
	}
}
</SCRIPT>
</HEAD>
<BODY BGCOLOR="buttonface" ONLOAD="Do_Close();"> 
<FORM ACTION="<?php echo "delete.php?type=$type&passdir=$passdir" ?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="file2delete" VALUE="<?php echo $file2delete ?>">
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD COLSPAN="2" ALIGN="CENTER"><STRONG>Esta seguro que desea Eliminar el archivo ?</STRONG></TD>
</TR>
<TR> 
<TD COLSPAN="2" ALIGN="CENTER"><BR><BR><STRONG><?php echo $filename?></STRONG><BR><BR><BR><BR></TD>
</TR>
<TR> 
<TD ALIGN="CENTER"><INPUT TYPE="SUBMIT" ID="insert" NAME="insert" VALUE="Aceptar"></TD> 
<TD ALIGN="CENTER"><INPUT ID="cancel" TYPE="button" name="cancel" VALUE="Cancelar" ONCLICK="tinyMCEPopup.close();"></TD> 	   
</TR> 
</TABLE>
</FORM>
</BODY>
</HTML>
