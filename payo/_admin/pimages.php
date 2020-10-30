<?php
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
require("image.lib.php");
if (ini_get('register_globals') == false ){
	extract($_GET,EXTR_OVERWRITE,"");
	extract($_POST,EXTR_OVERWRITE,"");
	$PHP_SELF = $_SERVER['PHP_SELF'];
}
$colstable = 1;
$id_divf = 1;
$TableCol  = 13;
$thumb_res="60x60";
$thumb_quality="60";
if (strlen($dest_fold) > 0){
	$dirname = $dest_fold;
} else {
	$dirname = "../imagenes/";
}
?>
<HTML>
<HEAD>
<TITLE>Seleccionar Imagenes</TITLE> 
<STYLE TYPE="text/css"><!--
	*			{ font-family:MS Sans Serif,Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
		.colorTable		{ cursor:hand; }
	.SelectedD    {color:white; background:navy;margin-top: 0pt; margin-bottom: 5pt; cursor: pointer; cursor: hand;}
	.UnSelectedD  {color:black; background:white;margin-top: 0pt; margin-bottom: 5pt; cursor: pointer; cursor: hand;}
	-->
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
var imgPath = '<?php echo "$ThisURL"; ?>';
var div_id = "";
//--------------------------------------------------------
function ClickIcon(imagePath, id_div){
	document.getElementById(id_div).className= 'SelectedD';
	if (div_id != "" && div_id!=id_div) {
		document.getElementById(div_id).className= 'UnSelectedD';
	}
	div_id = id_div;
}
//--------------------------------------------------------
// -->
</SCRIPT>
</HEAD>
<BODY BGCOLOR="#FFFFFF" LEFTMARGIN="2" TOPMARGIN="0">
<TABLE CELLPADDING="2" CELLSPACING="2" BORDER="0" ID="IMGTABLE" >
<?php
echo "<TR><TD VALIGN='TOP' NOWRAP='NOWRAP'>\n";
$arradir = array();
if ($handle = opendir($dirname)) {
	while (false !== ($nombre_archivo = readdir($handle))) {
		if (strpos(strtoupper($nombre_archivo),".JPG")>0 || strpos(strtoupper($nombre_archivo),".GIF")>0 || strpos(strtoupper($nombre_archivo),".PNG")>0) {
			$arradir[] = $nombre_archivo;
		}
 	}
	closedir($handle); 
} else {
	echo "Error leyendo directorio: $dirname";
}
sort($arradir);
if (count($arradir) > 0){
	foreach ($arradir as $file){
		if ($colstable >= $TableCol) {
			echo "<TD VALIGN='TOP' NOWRAP='NOWRAP'>\n";
			$colstable = 1;
		}
		echo "<DIV ID='ID" . $id_divf  . "' CLASS='UnSelectedD' ";
		echo "OnClick=\"ClickIcon('" . $file  . "','ID" . $id_divf  . "')\">";

		if ($vista=="thumb"){
			$sourcepic = $dirname . trim($file);
			$destpic = "tmpdir/" . trim($file);
			if (convert_image($sourcepic, $destpic, $thumb_res, $thumb_quality)) {
				echo "<IMG SRC=\"$destpic\" BORDER='0' ALIGN='ABSMIDDLE' ALT=\"".trim($file)."\"></DIV>\n";
			} else {
				echo "<IMG SRC=\"$sourcepic\" BORDER='0' ALIGN='ABSMIDDLE' ALT=\"".trim($file)."\"></DIV>\n";
			}
		} else {
			echo "<IMG SRC='img/mime/img.gif' BORDER='0' ALIGN='ABSMIDDLE'>" . trim($file) . "&nbsp;</DIV>\n";
		}
		$colstable = $colstable + 1;
		if ($colstable >= $TableCol){
 			echo "</TD>\n";
		}
		$id_divf = $id_divf + 1;
	}
}
echo "</TD></TR>\n";
echo "</TABLE>\n";
echo "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"><TR><TD>Archivos: ".($id_divf-1)."</TD></TR></TABLE>\n";

?>

</BODY>
</HTML>
