<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
require_once('include/functions.inc.php');

//sleep( 3 );
// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;
$q = strtolower($_GET["term"]);
// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);

$items=array();
$db=connect();
$presult = $db->Execute("Select codigo,descripcion from e_pproductos where codigo like '$q%' order by codigo");
if ($presult && !$presult->EOF){
	while(!$presult->EOF){
		//$items[$presult->fields[codigo]]=$presult->fields[descripcion];
		$aresult[] = ('{"id":"'.$presult->fields[codigo].'","label":"'.$presult->fields[codigo].'","value":"'.htmlentities($presult->fields[descripcion]).'"}');
		$presult->MoveNext();
	}
}
$result = '[';
$result .= implode(",",$aresult);
$result .= ']';
echo $result;

/*
$result = array();
foreach ($items as $key=>$value) {
	//echo $key;
	if (strpos(strtolower($key), $q) !== false) {
		array_push($result, array("id"=>$key, "label"=>$key." | ".strip_tags($value), "value" => strip_tags($key)));
	}
	if (count($result) > 11)
		break;
}

// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
echo json_encode($result);
*/
?>