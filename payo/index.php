<?php
require('include/site.lib.php');
session_name($default->session_name);
session_start();
require_once('include/translate.inc.php');
require_once('include/users.lib.php');
require('include/phpmailer/class.phpmailer.php');

if ($extra != "" && $op != ""){
	header("Location:$extra");
}
/*
if (!isset($op) || $op==""){
	$op=0;
}

if ($sectionname!=""){
	$op=search_op($sectionname);
}

if ($sop != 0){
	$n_op= $sop;
} else {
	$n_op = $op;
}

if ($n_op==0 || $n_op==""){
  $n_op = 1;
}
*/
//list($glo_title,$glo_color,$glo_privado) = BuscarProps($n_op,$lang_q);
if (!is_numeric($sop)){
	$sop='';
}	

session_write_close ;

$glo_img_dir = "img/".$glo_language;

if (isset($glo_title) && ($glo_title != "")) {
	$loc_title = $default->web_title . " - ".  translate($glo_title) ;
} else {
	$loc_title = $default->web_title;    
}

if (isset($glo_onload) && ($glo_onload != "")) {
	$loc_ONLOAD = " onload=\"$glo_onload\"";
	$glo_onload = "";
} else {
	$loc_ONLOAD = "";
}

$db = connect();
$db->debug = SDEBUG;

include("include/templates/header.inc.php");
include("include/templates/page_header.inc.php");
include("include/templates/page_body.inc.php");
include("include/templates/page_footer.inc.php");
include("include/templates/footer.inc.php");

?>
