<?php
require_once("include/config.inc.php");
require_once("include/file.lib.php");
require_once("include/functions.lib.php");
require_once("include/login_check.inc.php");

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


$filebrowse = new filebrowse($type);


$dirname=dir_slash($dirname);

if ($passdir==""){
	$passdir = dir_slash($dirname);
} else {
	$passdir = dir_slash($passdir);
} 
//-------------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML XMLNS="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Seleccionar archivos</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<SCRIPT LANGUAGE="JavaScript">
<!-- 
var div_id = "";
var img_files = new Array("jpg","png","gif","jpeg","bmp");
//----------------------------------
function inArray(a, v) {
	var i, l;
	if (a) {
		for (i=0, l=a.length; i<l; i++) {
			if (a[i] === v)
			return i;
		}
	}
	return -1;
}
//----------------------------------
function ClickFile(filePath, fileType, id_div){
	parent.document.flbrw.file_name.value = filePath;
	if (inArray(img_files, fileType)>=0){
		parent.FileBrowse.showPreviewImage(filePath);
	} else {
		parent.FileBrowse.showPreviewImage();
	}
	document.getElementById(id_div).className= 'SelectedD';
	if (div_id!="" && div_id!=id_div) {
		document.getElementById(div_id).className= 'UnSelectedD';
	}
	div_id = id_div;
}
//----------------------------------
function DblClickFile(filePath){
	parent.document.flbrw.file_name.value = filePath;
	parent.FileBrowse.close();
}
//----------------------------------
function ClickDir(dirPath, id_div){
	parent.FileBrowse.resetForm();
	document.getElementById(id_div).className= 'SelectedD';
	if (div_id != "" && div_id!=id_div) {
		document.getElementById(div_id).className= 'UnSelectedD';
	}
	div_id = id_div;
}
//----------------------------------
function DblClickDir(dirPath){
	Dirs = dirPath.split("/");
	var LastPath;
	parent.FileBrowse.resetForm();
	if (Dirs[Dirs.length-1] == '..'){
		for (var i=0; i<Dirs.length-2; i++) {
			if (i==0){
				LastPath = Dirs[i];
			} else {
				LastPath = LastPath + "/" + Dirs[i];
			}
		}
		parent.document.flbrw.file_name.value = '';
		parent.document.flbrw.passdir.value = LastPath;
		parent.document.getElementById("filebrowse").src = "files.php?type=<?php echo $type ?>&passdir=" + LastPath;
	} else {
		parent.document.flbrw.file_name.value = '';
		parent.document.flbrw.passdir.value = dirPath;
		parent.document.getElementById("filebrowse").src = "files.php?type=<?php echo $type ?>&passdir=" + dirPath;
	}
}
//----------------------------------
// -->
</SCRIPT>
</HEAD>
<BODY CLASS="filebrw" BGCOLOR="#FFFFFF">
<TABLE CELLPADDING="1" CELLSPACING="0" BORDER="0" ID="IMGTABLE" WIDTH="100%" BGCOLOR="#FFFFFF">
<?php
echo "<TR>";
echo "<TD VALIGN='MIDDLE' WIDTH='20' CLASS='titbrw'>&nbsp;</TD>";
echo "<TD VALIGN='MIDDLE' NOWRAP='NOWRAP' CLASS='titbrw'>Nombre</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='20' ALIGN='LEFT' NOWRAP='NOWRAP' CLASS='titbrw'>Tipo</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='70' ALIGN='CENTER' NOWRAP='NOWRAP' CLASS='titbrw'>Tama&ntilde;o</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='120' ALIGN='CENTER' NOWRAP='NOWRAP' CLASS='titbrw'>Fecha</TD>";
echo "</TR>\n";
$filebrowse->listfiles($passdir,$dirname);
if (count($filebrowse->files) > 0){
	$id_divf = 1;
	foreach ($filebrowse->files as $file){
		echo "<TR ID=\"ID{$id_divf}\" CLASS=\"UnSelectedD\" ";
		if (is_dir($passdir.$file['name'])){
			echo "OnClick=\"ClickDir('{$passdir}{$file['name']}','ID{$id_divf}')\" ";
			echo "OndblClick=\"DblClickDir('{$passdir}{$file['name']}')\">";
		} else {
			echo "OnClick=\"ClickFile('{$passdir}{$file['name']}','{$file['type']}','ID{$id_divf}')\" ";
			echo "OndblClick=\"DblClickFile('{$passdir}{$file['name']}')\">";
		}
		echo "<TD VALIGN='MIDDLE' WIDTH='20'><IMG SRC='{$file['icon']}' BORDER=\"0\" ALIGN=\"ABSMIDDLE\" STYLE=\"margin-right: 2pt;\"></TD>";
		echo "<TD VALIGN='MIDDLE' NOWRAP='NOWRAP'>".htmlspecialchars($file['name'])."</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='20' ALIGN='LEFT' NOWRAP='NOWRAP'>{$file['type']}</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='70' ALIGN='RIGHT' NOWRAP='NOWRAP'>{$file['size']}</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='120' ALIGN='RIGHT' NOWRAP='NOWRAP'>{$file['mtime']}</TD>";
		echo "</TR>\n";
		$id_divf++;
	}
} 
?>
</TABLE>
</BODY>
</HTML>
