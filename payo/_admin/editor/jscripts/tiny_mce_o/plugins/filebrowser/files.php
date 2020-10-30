<?
require_once("include/config.inc.php");
require_once("include/file.lib.php");

$dirname = dir_slash($img_dir);
if ($passdir==""){
	$passdir = $dirname;
} else {
	$passdir = dir_slash($passdir);
} 

if ($type!=""){
	if ($type=="image"){
		$filtro=array("jpg","png","gif");
	}
	if ($type=="media"){
		$filtro=array("swf","mov","wmv","mp4","mp3","qt");
	}
	if ($type=="file"){
		$filtro=array("swf","mov","wmv","rm","jpg","png","gif","bmp","html","htm","pdf","doc","docx","xls","xlsx","ppt","pptx");
	}
} else {
		$filtro=array("swf","mov","wmv","rm","jpg","png","gif","bmp","html","htm","pdf","doc","docx","xls","xlsx","ppt","pptx");
}
//-------------------------------------------------------------------------------
function file_property($file){
	$property = array("images/mime/unknown.gif","");
	$icons = array(
      "bmp"  =>   array("images/mime/bmp.gif","bmp"),
      "dir"  =>   array("images/mime/folder.gif","dir"),
      "exe"  =>   array("images/mime/binary.gif","bin"),
      "doc"  =>   array("images/mime/doc.gif","doc"),
      "docx" =>   array("images/mime/doc.gif","doc"),
      "pdf"  =>   array("images/mime/pdf.gif","pdf"),  
      "ppt"  =>   array("images/mime/ppt.gif","ppt"),  
      "pptx" =>   array("images/mime/ppt.gif","ppt"),  
      "gif"  =>   array("images/mime/gif.gif","gif"),  
      "html" =>   array("images/mime/html.gif","html"), 
      "htm"  =>   array("images/mime/html.gif","htm"), 
      "png"  =>   array("images/mime/img.gif","png"),  
      "jpg"  =>   array("images/mime/jpg.gif","jpg"),  
      "mov"  =>   array("images/mime/movie.gif","mov"),
      "qt"   =>   array("images/mime/movie.gif","qt"),
      "mpg"  =>   array("images/mime/movie.gif","mp3"),
      "mp4"  =>   array("images/mime/movie.gif","mp4"),
      "rm"   =>   array("images/mime/movie.gif","rm"),
      "mp3"  =>   array("images/mime/sound.gif","mp3"),
      "swf"  =>   array("images/mime/swf.gif","swf"),  
      "txt"  =>   array("images/mime/txt.gif","txt"),  
      "wmv"  =>   array("images/mime/wmv.gif","wmv"),  
      "xls"  =>   array("images/mime/xls.gif","xls"),
      "xlsx" =>   array("images/mime/xls.gif","xls"),
	);
	$partes_ruta = pathinfo($file);
	foreach($icons as $k => $v){
		if (trim(strtolower($partes_ruta['extension'])) == trim($k)){
			$property[iname] = strtolower(trim($partes_ruta['basename']));
			$property[name] = trim($partes_ruta['basename']);
			$property[icon] = $v[0];
			$property[type] = $v[1];
			$property[size] = round(filesize($file)/1024,2)."&nbsp;KB";
			$property[mtime] = date ("d/m/Y H:i", filemtime($file));
			break;
		}
	}
	return $property;
}
//-------------------------------------------------------------------------------
function listfiles($passdir){
	global $dirname,$filtro;
	$arradir = array();
	$arrafiles = array();
	if ($handle = opendir($passdir)) {
		while (false !== ($nombre_archivo = readdir($handle))) {
				if (is_dir($passdir.$nombre_archivo)){
					if ($nombre_archivo != "." && $nombre_archivo != "..") {
						$arradir[] = array (
							"iname" => strtolower(trim($nombre_archivo)),
							"name" => trim($nombre_archivo),
							"type" => "",
							"icon" => "images/mime/folder.gif",
							"size" => "",
							"mtime" => "",
						);
					} else {
						if ($passdir != $dirname && $nombre_archivo == ".."){
							$arradir[] = array (
								"iname" => strtolower(trim($nombre_archivo)),
								"name" => trim($nombre_archivo),
								"type" => "",
								"icon" => "images/mime/folder.gif",
								"size" => "",
								"mtime" => "",
							);
						}
					}
				} else {
					$partes_ruta = pathinfo($passdir.$nombre_archivo);
					if (in_array(strtolower($partes_ruta['extension']), $filtro, true)){ 
						$arrafiles[] = file_property($passdir.$nombre_archivo);
					}
				}
	 	}
		closedir($handle); 
	}

	sort($arradir);
	sort($arrafiles);
	$result = array_merge($arradir, $arrafiles);
	return $result;
}
//-------------------------------------------------------------------------------
?>
<HTML>
<HEAD>
<TITLE>Seleccionar archivos</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<LINK REL="stylesheet" TYPE="text/css" HREF="jscripts/tiny_mce/themes/advanced/skins/default/dialog.css">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<SCRIPT LANGUAGE="JavaScript">
<!-- 
var origPath = '<? echo "$ThisURL"; ?>';
var div_id = "";
var ftype = '<? echo $type; ?>';
//----------------------------------
function ClickFile(filePath, id_div){
	filePath = origPath + filePath
	parent.document.flbrw.file_name.value = filePath;
	if (ftype == 'image' ){
		parent.FileBrowse.showPreviewImage(filePath);
	}
	document.getElementById(id_div).className= 'SelectedD';
	if (div_id != "" && div_id!=id_div) {
		document.getElementById(div_id).className= 'UnSelectedD';
	}
	div_id = id_div;
}
//----------------------------------
function DblClickFile(filePath){
	filePath = origPath + filePath
	parent.document.flbrw.file_name.value = filePath;
	parent.FileBrowse.Close();
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
		parent.document.getElementById("filebrowse").src = "files.php?type=<? echo $type ?>&passdir=" + LastPath;
	} else {
		parent.document.flbrw.file_name.value = '';
		parent.document.flbrw.passdir.value = dirPath;
		parent.document.getElementById("filebrowse").src = "files.php?type=<? echo $type ?>&passdir=" + dirPath;
	}
}
//----------------------------------
// -->
</SCRIPT>
</HEAD>
<BODY CLASS="body_file" BGCOLOR="#FFFFFF">
<TABLE CELLPADDING="1" CELLSPACING="0" BORDER="0" ID="IMGTABLE" WIDTH="100%" BGCOLOR="#FFFFFF">
<?
echo "<TR>";
echo "<TD VALIGN='MIDDLE' WIDTH='20' CLASS='titbrw'>&nbsp;</TD>";
echo "<TD VALIGN='MIDDLE' NOWRAP='NOWRAP' CLASS='titbrw'>Nombre</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='20' ALIGN='LEFT' NOWRAP='NOWRAP' CLASS='titbrw'>Tipo</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='70' ALIGN='CENTER' NOWRAP='NOWRAP' CLASS='titbrw'>Tama&ntilde;o</TD>";
echo "<TD VALIGN='MIDDLE' WIDTH='120' ALIGN='CENTER' NOWRAP='NOWRAP' CLASS='titbrw'>Fecha</TD>";
echo "</TR>\n";
$arradir=listfiles($passdir);
if (count($arradir) > 0){
	$id_divf = 1;
	foreach ($arradir as $file){
		echo "<TR ID=\"ID" . $id_divf  . "\" CLASS=\"UnSelectedD\" ";
		if (is_dir($passdir.$file['name'])){
			echo "OnClick=\"ClickDir('". $passdir . $file['name']  . "','ID" . $id_divf  . "')\" ";
			echo "OndblClick=\"DblClickDir('" . $passdir. $file['name'] . "')\">";
		} else {
			echo "OnClick=\"ClickFile('". $passdir . $file['name'] . "','ID" . $id_divf  . "')\" ";
			echo "OndblClick=\"DblClickFile('" . $passdir . $file['name'] . "')\">";
		}
		echo "<TD VALIGN='MIDDLE' WIDTH='20'><IMG SRC='".$file['icon']."' BORDER=\"0\" ALIGN=\"ABSMIDDLE\" STYLE=\"margin-right: 2pt;\"></TD>";
		echo "<TD VALIGN='MIDDLE' NOWRAP='NOWRAP'>".$file['name']."</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='20' ALIGN='LEFT' NOWRAP='NOWRAP'>".$file['type']."</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='70' ALIGN='RIGHT' NOWRAP='NOWRAP'>".$file['size']."</TD>";
		echo "<TD VALIGN='MIDDLE' WIDTH='120' ALIGN='RIGHT' NOWRAP='NOWRAP'>".$file['mtime']."</TD>";
		echo "</TR>\n";
		$id_divf = $id_divf + 1;
	}
} 
?>
</TABLE>
</BODY>
</HTML>
