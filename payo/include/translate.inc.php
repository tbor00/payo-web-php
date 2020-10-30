<?php
if (!isset($lang)) {
	if (!isset($_SESSION['lng'])) {
		$_SESSION['lng'] = $default->lang;
	}	
} else {
	unset($_SESSION['lng']);
	$_SESSION['lng'] = $lang;
}
$lang_q = $default->FindLang($_SESSION['lng']);

$glo_language = $_SESSION['lng'];
//--------------------------------------------------------------
function translate( $str ) { 
	global $lang_q, $default;
	$db = connect();
	$db->debug = SDEBUG;
	if ($str==""){
   	return "";
	}
	if ($lang_q==1) {
		return $str;
	}
	$trn_query="select * from translations where text_orig='$str' and lenguaje_id=$lang_q";
	$trn_res = $db->Execute($trn_query);
	if($trn_res && !$trn_res->EOF){
	   $trans_t = $trn_res->fields[text_tran];
	}
   if ($trans_t==''){
		return $str;
	} else {
		return $trans_t;
	}
}
//--------------------------------------------------------------
function etranslate($str){
	echo translate($str);
}
?>