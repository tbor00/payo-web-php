<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
if (ini_get('register_globals') == false ){
	extract($_GET,EXTR_OVERWRITE,"");
	extract($_POST,EXTR_OVERWRITE,"");
	$PHP_SELF = $_SERVER['PHP_SELF'];
}

//ini_set("display_errors","on");
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (strlen($dest_fold) > 0){
	$destination_folder = $dest_fold;
} else {
	$destination_folder = "../img/es/";
}

if (strlen($file_size) > 0 && $file_size > 0 ){
	$file_size;
} else {
	$file_size=(400 * 1024);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Subir Imagenes</title>
<script src="jscripts/jquery-2.1.1.min.js"></script>
<script src="jscripts/upscript.js"></script>
<link rel="stylesheet" type="text/css" href="styles/upstyle.css">
<SCRIPT>
//--------------------------------------------------------
function Refresh_img() {
   window.opener.location.reload();
	//window.opener.document.getElementById('images').location.reload();
	//window.opener.document.frames("images").location="pimages.php?vista=<?php echo $vista ?>&dest_fold=<?php echo $destination_folder ?>";
}
//--------------------------------------------------------
</SCRIPT>
<body ONLOAD="Refresh_img();">
<div id="maindiv">
<div id="formdiv">
<h2>Subir Multiples Imagenes</h2>
<form enctype="multipart/form-data" action="" method="post">
<div id="filediv"><input name="file[]" type="file" id="file"/></div>
<input type="button" id="add_more" class="upload" value="Agregar m&aacute;s archivos"/>
<input type="submit" value="Subir archivo" name="submit" id="upload" class="upload"/>
</form>
<?php
if (isset($_POST['submit'])) {
	$j = 0;
	$target_path = $destination_folder;
	for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
		$validextensions = array("JPEG", "JPG", "PNG", "GIF");
		$ext = explode('.', basename($_FILES['file']['name'][$i]));
		$file_extension = end($ext);
		$target_file = $target_path . basename($_FILES['file']['name'][$i]);
		$j = $j + 1;
		if ($_FILES["file"]["size"][$i] < $file_size) {
			if (in_array(strtoupper($file_extension), $validextensions)) {
				if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_file)) {
					echo $j. ').<span id="noerror">Imagen subida Exitosamente!.</span><br/><br/>';
					//echo "<SCRIPT>Do_Close();</SCRIPT>\n";
				} else {
					echo $j. ').<span id="error">please try again!.</span><br/><br/>';
				}
			} else {
				echo $j. ').<span id="error">***Tipo de Imagen Inv&aacute;lida***</span><br/><br/>';
			}
		} else {
			echo $j. ').<span id="error">***Tama&ntilde;o Excedido***</span><br/><br/>';
		}
	}
}
?>
</div>
</div>
</body>
</html>
