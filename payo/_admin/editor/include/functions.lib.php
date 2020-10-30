<?php
//---------------------------------------------------------------------------
function make_absoluteURI($url, $protocol = null, $port = null) {
	if (!preg_match('!^([a-z0-9]+)://!i', $url)) {
		// Resolve relative URLs
		if (isset($_SERVER)) {
			$aServerVars =& $_SERVER;
		} else {
			global $HTTP_SERVER_VARS;
			$aServerVars =& $HTTP_SERVER_VARS;
		}

		$sHost = (!empty($aServerVars['HTTP_HOST'])) ? $aServerVars['HTTP_HOST'] : ((!empty($aServerVars['SERVER_NAME'])) ? $aServerVars['SERVER_NAME'] : 'localhost');

		// HTTP_HOST could contain port information, we need
		// to remove it to normalize the code below
		list($sHost) = explode(':', $sHost);

		if (empty($protocol)) {
			$sProtocol = (strtoupper(@$aServerVars['HTTPS']) == 'ON') ? 'https' : 'http';
			$iPort = (!is_int($port)) ? $aServerVars['SERVER_PORT'] : $port;

		} else {
			// Just in case the user passes parameters by reference
			// we don't want to change them...
			$sProtocol = $protocol;
			$iPort = (is_int($port)) ? $port : null;
		}

		$sServer = $sProtocol . '://' . $sHost . ((is_null($iPort) || ( $iPort == 80 || $iPort == 443 )) ? '' : (':' . $iPort));

		if ($url{0} == '/') {
			return $sServer . $url;
		} elseif (!empty($aServerVars['PATH_INFO'])) {
			// Correct for PATH_INFO that offsets the path
			// to current script
			return $sServer . dirname(substr($aServerVars['PHP_SELF'], 0, -1 * strlen($aServerVars['PATH_INFO']))) . '/' . $url;
		} else {
			return $sServer . dirname($aServerVars['PHP_SELF']) . '/' . $url;
		}
	} elseif (!empty($protocol)) {
		// Change scheme
		$sURL = $protocol . ':' . array_pop(explode(':', $url, 2));
		// Since we change protocol but stay on the same server,
		// the port MUST change... Is that new port the default one
		// for this protocol?
		$sPort = (!is_int($port) 
			|| empty($port) 
			|| $port == ($iPort = HTTP::_getStandardPort($protocol))
			|| is_null($iPort)) ? '\1' : '\1:' . $port;

		return preg_replace('!^(([a-z0-9]+)://[^/:]+)(:[\d]+)?!i', $sPort, $sURL);
	}
	return $url;
}
//---------------------------------------------------------------------------
function BaseUri($dire=""){
	$uri = make_absoluteURI($PHP_SELF);
	$uri = str_replace(basename($PHP_SELF),'',$uri);
   $uri = str_replace($dire,'',$uri);
	return $uri;
}
//---------------------------------------------------------------------------

//---------------------------------------------------------------------------
function connect() {
	global $default;
	$db = NewADOConnection($default->dbtype);
	if (preg_match("/oci8/i",$default->dbtype)){
		$db->connectSID = true;
	}
	if (!$db->Connect($default->dbhost, $default->dbuser,$default->dbuser_pass,$default->database)) {
		return 0;
	} else {
		return $db;
	}
}
//---------------------------------------------------------------------------
function NotifyFn($expireref, $sesskey) {
	global $ADODB_SESS_CONN; 					# the session connection object
	$user = $ADODB_SESS_CONN->qstr($expireref);
	/////$ADODB_SESS_CONN->Execute("delete from trakxalbum where user_id=$user");
}
//---------------------------------------------------------------------------

?>