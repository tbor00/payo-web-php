<?php
if ($_POST['auth'] == 'login'){
	if (!checkuser($_POST['user'],$_POST['passwd'])){
		session_defaults();
	}
}
if ($auth=="logout") {
	$op=0;
	$sop="";
	session_defaults();
	header("Location:index.php");
}

if (!isset($_SESSION['uid'])) {
	session_defaults();
}

//-------------------------------------------------------------------------
function session_defaults() {
	$_SESSION['logged'] = false;
	$_SESSION['uid'] = 'NULL';
	$_SESSION['user_name'] = '';
	$_SESSION['last_logged'] = null;
	$_SESSION["user_alias"] = '';
	$_SESSION["nivel"] = 0;
	$_SESSION["user_email"] = '';
	$_SESSION["user_apellidos"] = '';
	$_SESSION["user_nombres"] = '';
	$_SESSION["iva"] = 1;
	$_SESSION['coef'] = 1;
}
//-------------------------------------------------------------------------
function checkuser($wuser,$wpass){
	global $default,$a_alert;
	$db_u = connect();
	$db_u->debug = SDEBUG;
	$logged = false;
	$user_re = $db_u->Execute("select user_id,username,password,nivel,nombres,apellidos,email,iva,coef from e_webusers where username=? and activo_u=1",array($wuser));
	if( $user_re != false && !$user_re->EOF ){
		$hash = md5($wpass);
		if( $hash == $user_re->fields['password']){
	  		$_SESSION["user_name"]=$wuser;
	  		$_SESSION["user_alias"]=$user_re->fields['nombres'] . " ". $user_re->fields['apellidos'];
	  		$_SESSION["user_nombres"]=$user_re->fields['nombres'];
	  		$_SESSION["user_apellidos"]=$user_re->fields['apellidos'];
	  		$_SESSION["user_email"]=$user_re->fields['email'];
			$_SESSION["uid"] = $user_re->fields['user_id'];
			$_SESSION["nivel"] = $user_re->fields['nivel'];
			if ($user_re->fields['coef']!=0){
				$_SESSION["coef"] = $user_re->fields['coef'];
			} else {
				$_SESSION["coef"] = 1;
			}	
			$iva_r=$db_u->Execute("SELECT * from e_ivas where id_iva=".$user_re->fields['iva']."");
			if ($iva_r && !$iva_r->EOF){
				$_SESSION["iva"] = $iva_r->fields['tipo'];	
			} else {
				$_SESSION["iva"] = 0;	
			}
			$_SESSION['logged'] = true;
 			$_SESSION['last_login'] = '0000-00-00';
			$db_u->Execute("UPDATE e_webusers set last_login_date='".date("Y-m-d")."', last_login_time='".date("H:i:s")."' WHERE user_id='".$user_re->fields['user_id']."'");
			$logged =  true;
		} else {
			$a_alert[] = " Atenci&oacute;n: &iexcl;El usuario o la contrase&ntilde;a son incorrectos!";
		}
	} else {
		$a_alert[] = " Atenci&oacute;n: &iexcl;El usuario o la contrase&ntilde;a son incorrectos!";
	}
	return $logged;
}
//-------------------------------------------------------------------------
?>