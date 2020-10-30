<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
require("image.lib.php");
if (ini_get('register_globals') == false ){
	extract($_GET,EXTR_OVERWRITE,"");
	extract($_POST,EXTR_OVERWRITE,"");
	$PHP_SELF = $_SERVER['PHP_SELF'];
}
//ini_set("display_errors","on");
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
$input_field_name = "picturefile";
$accepted_mime_types = array('image/jpg', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/gif');
if (strlen($dest_fold) > 0){
	$destination_folder = $dest_fold;
} else {
	$destination_folder = "../img/es/";
}
$overwrite = false;

$upload = new upload($input_field_name);
if (strlen($file_size) > 0 && $file_size > 0 ){
	$upload->set_max_file_size($file_size);
} else {
	$upload->set_max_file_size(400 * 1024);
}

if ($imgtype=='logo'){
	$upload->set_image_resize(150, 150); // in pixels
} elseif($imgtype=='portfolio') {
	$upload->set_image_resize(200, 150); // in pixels
} 


$upload->set_max_image_size(800, 4000); //in pixels
$upload->set_accepted_mime_types($accepted_mime_types);
$url="upload.php?vista=$vista&dest_fold=$destination_folder&imgtype=$imgtype";
?>
<HTML>
<HEAD>
<TITLE>Subir Imagen</TITLE>
<STYLE TYPE="text/css"><!--
	*			{ font-family:MS Sans Serif,Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
		.colorTable		{ cursor:hand; }
	-->
</STYLE>
<SCRIPT>
//--------------------------------------------------------
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
   $result = $upload->security_check();
	if ($result == true){
		$result = $upload->move($destination_folder, $overwrite);
	}
   if ($result == true){
		echo "var refreshW=1;\n";
		echo "var uperror='';\n";
	} else {
	   echo "var refreshW=0;\n";
		echo "var uperror='$upload->error_msg';\n";
	}
} else {
	echo "var refreshW=0;\n";
	echo "var uperror='';\n";
}
?>
//--------------------------------------------------------
function Do_close() {
   if (refreshW == 1) {
	   window.opener.location.reload();
		//window.opener.document.frames("images").location="images.php?vista=<?php echo $vista ?>&dest_fold=<?php echo $destination_folder ?>";
		self.close();
	} else {
		if (uperror!=''){
			window.alert(uperror);
		}
	}
}
//--------------------------------------------------------
</SCRIPT>
</HEAD>
<BODY BGCOLOR="#E6E6E6" Onload="Do_close();"> 
<?php
$upload->draw_form('Imagen Seleccionada', $url);
?>
</BODY>
</HTML>
