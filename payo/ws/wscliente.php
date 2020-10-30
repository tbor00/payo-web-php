<?php
require_once("../include/lib//nusoap.php");

$client = new nusoap_client('https://www.electropuerto.com.ar/ws/wsproductos.php?wsdl','wsdl');

$err = $client->getError();
if ($err) {
	// Display the error
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	// At this point, you know the call that follows will fail
}


$result = $client->call('GetProductsAsArray', array());
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	echo htmlspecialchars($client->faultstring, ENT_QUOTES);
	echo '</pre>';
	echo '<h2>Debug</h2>';
	echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
} else {
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
		echo '<h2>Debug</h2>';
		echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages

?>