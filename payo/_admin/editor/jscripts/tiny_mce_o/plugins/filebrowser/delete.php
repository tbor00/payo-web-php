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

$filename=parse_url($file, PHP_URL_PATH);
$url="delete.php?type=$type&passdir=$passdir";

?>
<HTML>
<HEAD>
<TITLE>Eliminar Archivo</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8 ?>">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="jscripts/tiny_mce/tiny_mce_popup.js"></SCRIPT>
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<?
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$current_path = dir_slash(dirname(__FILE__));
	$current_file = basename($filename);
	if ($file2delete = realpath($current_path.$passdir.$current_file)){
	   $result = unlink($file2delete);
	}
	echo "<SCRIPT>\n";
   if ($result == true){
		echo "var refreshW=1;\n";
		echo "var uperror='';\n";
	}else{
	   echo "var refreshW=0;\n";
		echo "var uperror='Error: No se pudo eliminar el archivo';\n";
	}
	echo "</SCRIPT>\n";
} else {
	echo "<SCRIPT>\n";
	echo "var refreshW=0;\n";
	echo "var uperror='';\n";
	echo "</SCRIPT>\n";
}
?>
<SCRIPT>
function Do_close() {
   if (refreshW == 1) {
		var win = tinyMCEPopup.getWindowArg("window");

		if (typeof(tinyMCEPopup.FileBrowse) != "undefined"){
			if (tinyMCEPopup.FileBrowse.refreshBrowse) tinyMCEPopup.FileBrowse.refreshBrowse();
		}
		//tinyMCEPopup.getWindowArg("frame").src="files.php?type=<? echo $type ?>&passdir=<? echo $passdir ?>";
		tinyMCEPopup.alert("El archivo se ha eliminado con exito!");
		tinyMCEPopup.close();
	} else {
		if (uperror!=''){
			tinyMCEPopup.alert(uperror);
			tinyMCEPopup.close();
		}
	}
}
</SCRIPT>
</HEAD>
<BODY BGCOLOR="buttonface" Onload="Do_close();"> 
<FORM ACTION="<? echo $url ?>" METHOD="post">
<INPUT TYPE="HIDDEN" NAME="file" VALUE="<? echo $file ?>">
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" WIDTH="100%"> 
<TR> 
<TD COLSPAN="2" ALIGN="CENTER"><STRONG>Esta seguro que desea Eliminar el archivo ?</STRONG></TD>
</TR>
<TR> 
<TD COLSPAN="2" ALIGN="CENTER"><BR><BR><STRONG><? echo $filename?></STRONG><BR><BR><BR><BR></TD>
</TR>
<TR> 
<TD ALIGN="CENTER"><INPUT TYPE="SUBMIT" ID="insert" NAME="insert" VALUE="Aceptar"></TD> 
<TD ALIGN="CENTER"><INPUT ID="cancel" TYPE="button" name="cancel" VALUE="Cancelar" ONCLICK="tinyMCEPopup.close();" ></TD> 	   
</TR> 
</TABLE>
</FORM>
</BODY>
</HTML>

