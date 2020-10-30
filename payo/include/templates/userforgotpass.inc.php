<?php
//-------------------------------------------------------------------------------------------------------------------
function forgot_form($url1=''){
	global $default,$lang_q;
section_text("FORGOTSE",1);
?>
<div class="row">     
<div id="content" class="col-sm-12">      

<form action="<?php echo "{$_SERVER['PHP_SELF']}".$url1 ?>" method="post" name="forgotten_form" enctype="multipart/form-data" class="form-horizontal">
<input type="hidden" name="auth" value="forgot">
<input type="hidden" name="useraction" value="remember">
<fieldset>
<legend>¿Olvid&eacute; mi contrase&ntilde;a?</legend>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-email">Usuario</label>
<div class="col-sm-4">
<input type="text" name="wuser" value="" placeholder="Usuario" id="input-user" class="form-control" />
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-email">Direcci&oacute;n de e-mail</label>
<div class="col-sm-4">
<input type="text" name="wemail" value="" placeholder="Direcci&oacute;n de e-mail" id="input-email" class="form-control" />
</div>
</div>

<?php
$code = e_randomTEXT();
$_SESSION['rand_code'] = $code;
?>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-captcha">Introduce el codigo de abajo</label>
<div class="col-sm-4">
<input type="text" name="valida" id="input-captcha" class="form-control" />
<img src="auxiliar.php?op=gencaptcha" alt="" />
<?php if ($error_captcha) { ?>
<div class="text-danger"><?php echo $error_captcha; ?></div>
<?php } ?>
</div>
</div>

</fieldset>
<div class="buttons clearfix">
<div class="pull-left"><a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="btn btn-default">Volver</a></div>
<div class="pull-right">
<input type="submit" value="Aceptar" class="btn btn-primary" />
</div>
</div>
</form>
</div>
</div>
<?php
}
//-------------------------------------------------------------------------------------------------------------------
function chgpwd_form($confirm, $url1='',$tmpuid){
	global $default, $lang_q;
?>
<div class="row">     
<div id="content" class="col-sm-12">      

<form action="<?php echo "{$_SERVER['PHP_SELF']}".$url1 ?>" method="post" name="chgpwd_form" enctype="multipart/form-data" class="form-horizontal">
<input type="hidden" name="useraction" value="chgpwd">
<input type="hidden" name="auth" value="forgot">
<input type="hidden" name="confirm" value="<?php echo $confirm ?>">
<input type="hidden" name="newpasswd" value="">
<fieldset>
<legend>Recuperaci&oacute;n de contrase&ntilde;a</legend>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-email">Usuario</label>
<div class="col-sm-4">
<input type="text" name="wuser" value="" placeholder="Usuario" id="input-user" class="form-control" />
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-question">Pregunta</label>
<div class="col-sm-10">
<input type="text" name="wpregunta" value="" placeholder="Pregunta" id="input-question" class="form-control" />
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-ask">Respuesta</label>
<div class="col-sm-10">
<input type="text" name="wrespuesta" value="" placeholder="Respuesta" id="input-ask" class="form-control" />
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-newpasswd">Nueva Contrase&ntilde;a</label>
<div class="col-sm-4">
<input type="password" name="newpasswd" value="" maxlength="15" placeholder="Contrase&ntilde;a" id="input-newpasswd" class="form-control" />
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-confirmpasswdd">Confirmar Contrase&ntilde;a</label>
<div class="col-sm-4">
<input type="password" name="wpwd2" value="" maxlength="15" placeholder="Repetir Contrase&ntilde;a" id="input-confirmpasswdd" class="form-control" />
</div>
</div>

</fieldset>
<div class="buttons clearfix">
<div class="pull-left"><a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="btn btn-default">Volver</a></div>
<div class="pull-right">
<input type="submit" value="Aceptar" class="btn btn-primary" />
</div>
</div>
</form>
</div>
</div>
<?php
}
//-------------------------------------------------------------------------------------------------------------------
function checkforgot($wuser,$wemail,$valida){
	global $default,$glo_onload;
	if(strtoupper($valida) != strtoupper($_SESSION['rand_code']) || $valida=="" ){
		return false;
	}
	$_SESSION['rand_code']="";
	$db_u = connect();
	$db_u->debug = SDEBUG;
	$user_re = $db_u->Execute("SELECT user_id,username,email,password,nombres,apellidos,pregunta FROM e_webusers WHERE username='$wuser' AND email='$wemail' AND activo_u=1");
	if( $user_re && ! $user_re->EOF ){
		$chash = $user_re->fields['user_id'].$user_re->fields['password'].$user_re->fields['username'].$user_re->fields['email'];
		$hash = md5($chash);
		$user_id =  $user_re->fields['user_id'];
		$retype = md5($user_re->fields['pregunta']);
		$retype = md5($user_re->fields['pregunta']);
		$user_ro = $db_u->Execute("update e_webusers set forgot='$hash' where user_id='$user_id'");
		$body = section_text("FORGOT",0);
		$url = make_absoluteURI($_SERVER['PHP_SELF'])."?auth=forgot&confirm=$hash&retype=$retype&code=$valida";
		$mail_body = '<HTML><HEAD><TITLE></TITLE>
<STYLE TYPE="text/css">
BODY { font-family: Arial, Helvetica, sans-serif;	font-size: 9pt; color: #000000; }
TD, TH {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; }
</STYLE> 
</HEAD> 
<BODY BGCOLOR="#FFFFFF"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" STYLE="border: 1pt #7D104A solid"> 
<TR> 
<TD> 
<TABLE WIDTH="500" BORDER="0" CELLPADDING="0" CELLSPACING="0"> 
<TR VALIGN="BOTTOM"> 
<TD VALIGN="BOTTOM" ALIGN="LEFT" BGCOLOR="#F5F5F5"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%"> 
<TR> 
<TD VALIGN="MIDDLE" ALIGN="LEFT">&nbsp;<IMG SRC="cid:logo" ALT="LOGO" WIDTH="261" HEIGHT="66" BORDER="0" ALIGN="ABSMIDDLE"></TD>
</TR> 
</TABLE></TD> 
</TR> 
</TABLE> 
<TABLE WIDTH="500" BORDER="0" CELLPADDING="2" CELLSPACING="1"> 
<TR> 
<TD BGCOLOR="#CACACA"><BR>##TEXTO##<BR><BR>##ACCION## <A HREF="##URL##">aqui</A><BR><BR></TD> 
</TR> 
</TABLE>
</TD> 
</TR> 
</TABLE 
</BODY>
</HTML>';

		$mail_body = str_replace("##TEXTO##","$body",$mail_body); 
		$mail_body = str_replace("##URL##","$url",$mail_body); 
		$mail_body = str_replace("##ACCION##","Puede obtenerla haciendo click",$mail_body);
		$txt_body  = str_replace("<BR>","\n",nl2br($body)); 
	   $txt_body .= "$url\n"; 
		$mail = new PHPMailer();
  		$mail->IsHTML(true);
		$mail->From = txt_email_to();
		$mail->FromName = $default->mailfromname;
		$mail->Mailer   = "mail";
		$mail->AddAddress($user_re->fields['email'],"{$user_re->fields[nombres]} {$user_re->fields[apellidos]}");
		$mail->Priority = 1;
		$mail->Subject = $default->web_title ." - ". translate("Olvidó su contraseña");
		$mail->AddEmbeddedImage("image/logo.gif", "logo", "logo.gif");
		$mail->AltBody = $txt_body;
		$mail->Body = $mail_body;
		if(!$mail->Send()){
			$texto = $texto . "<P STYLE=\"color:red\">"."Error"."</P>";	
		} else {
			$texto="";
		   echo section_text("FORGOTSE",1);
		}
		$mail->ClearAddresses();
		return true;
	} else {
		return false;
	}
}
//-------------------------------------------------------------------------------------------------------------------
function checkconfirm($hash){
	global $default,$glo_onload,$login;
	$db_u = connect();
	$user_re = $db_u->Execute("select user_id from e_webusers where forgot='$hash' and activo_u=1");
	if( $user_re && !$user_re->EOF ){
		return $user_re->fields[user_id];
	} else {
		return false;
	}
}
//-------------------------------------------------------------------------------------------------------------------
function change_passwd($user,$hash,$newpasswd){
	global $default,$glo_onload,$login;
	$db_u = connect();
	$user_re = $db_u->Execute("select user_id,email,username, nombres, apellidos from e_webusers where forgot='$hash' and username='$user' and activo_u=1");
	if( $user_re != false && ! $user_re->EOF ){
		$hash = md5($newpasswd);
		$user_id =  $user_re->fields['user_id'];
		$user_ro = $db_u->Execute("update e_webusers set password='$hash', forgot='' where user_id='$user_id'");

		$body = section_text("FORGOTCP",0)."<BR><BR>";
		$body.= translate("Usuario").": ".$user."<BR>";
		$body.= translate("Nueva contraseña").": ".$newpasswd."<BR>";
		$mail_body = '<HTML><HEAD><TITLE></TITLE>
<STYLE TYPE="text/css">
BODY { font-family: Arial, Helvetica, sans-serif;	font-size: 9pt; color: #000000; }
TD, TH {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; }
</STYLE> 
</HEAD> 
<BODY BGCOLOR="#FFFFFF"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" STYLE="border: 1pt #7D104A solid"> 
<TR> 
<TD> 
<TABLE WIDTH="500" BORDER="0" CELLPADDING="0" CELLSPACING="0"> 
<TR VALIGN="BOTTOM"> 
<TD VALIGN="BOTTOM" ALIGN="LEFT" BGCOLOR="#F5F5F5"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%"> 
<TR> 
<TD VALIGN="MIDDLE" ALIGN="LEFT">&nbsp;<IMG SRC="cid:logo" ALT="LOGO" WIDTH="261" HEIGHT="66" BORDER="0" ALIGN="ABSMIDDLE"></TD>
</TR> 
</TABLE></TD> 
</TR> 
</TABLE> 
<TABLE WIDTH="500" BORDER="0" CELLPADDING="2" CELLSPACING="1"> 
<TR> 
<TD BGCOLOR="#CACACA"><BR>##TEXTO## <BR><BR></TD> 
</TR> 
</TABLE>
</TD> 
</TR> 
</TABLE 
</BODY>
</HTML>';

		$mail_body = str_replace("##TEXTO##","$body",$mail_body); 
		$txt_body  = str_replace("<BR>","\n",nl2br($body)); 
		$mail = new PHPMailer();
  		$mail->IsHTML(true);
		$mail->From = txt_email_to();
		$mail->FromName = $default->mailfromname;
		$mail->Mailer   = "mail";
		$mail->AddAddress($user_re->fields['email'],"{$user_re->fields[nombres]} {$user_re->fields[apellidos]}");
		$mail->Priority = 1;
		$mail->Subject = $default->web_title ." - ". translate("Cambio de contraseña");
		$mail->AddEmbeddedImage("image/logo.gif","logo", "logo.gif");
		$mail->AltBody = $txt_body;
		$mail->Body = $mail_body;
		if(!$mail->Send()){
			$texto = $texto . "<P STYLE=\"color:red\">"."Error"."</P>";	
		} else {
			$texto="";
		   echo section_text("FGTCAOK",1);
		}
		$mail->ClearAddresses();
		$mail->ClearReplyTos();
		return true;
		return true;
	} else {
		echo section_text("FORGOTER",1);
		return false;
	}
}

//-------------------------------------------------------------------------------------------------------------------

$url1 = uri_val($_SERVER['QUERY_STRING'], array('authen','useraction','confirm','retype','valida'));
$confirm = $_GET[confirm];
$retype = $_GET[retype];
if (strlen($url1) > 0){
	$url1="?".$url1;
}
if ($_POST["useraction"] == 'remember'){
	if (!checkforgot($_POST[wuser],$_POST[wemail],$_POST[valida])){
		echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>Los datos proporcionados no son correctos.</div>\n";
		forgot_form($url1);
	}
} elseif ($_POST["useraction"] == 'chgpwd'){
	$wpregunta = $_POST[wpregunta];
	$wrespuesta = $_POST[wrespuesta];
	$wuser = $_POST[wuser];
	$confirm = $_POST[confirm];

	if ($_POST["newpasswd"] == "" ){
		echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>La Nueva contrase&ntilde;a no puede estar en blanco.</div>\n";
		chgpwd_form($confirm,$url1);	
		return;
	}
	if ($_POST["newpasswd"] != $_POST["wpwd2"] ){
		echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>Las Contrase&ntilde;as ingresadas no concuerdan.</div>\n";
		chgpwd_form($confirm,$url1);
		return;
	}

	$db_u = connect();
	$db_u->debug = SDEBUG;
	$user_re = $db_u->Execute("SELECT username,pregunta,respuesta FROM e_webusers WHERE username='$wuser' AND activo_u=1");
	if( $user_re && ! $user_re->EOF ){
		if ($_POST["wpregunta"] != $user_re->fields["pregunta"] ){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>La respuesta y/o pregunta no coincide.</div>\n";
			chgpwd_form($confirm,$url1);
			return;
		}
		if ($_POST["wrespuesta"] != $user_re->fields["respuesta"] ){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>La respuesta y/o pregunta no coinciden.</div>\n";
			chgpwd_form($confirm,$url1);
			return;
		}
		change_passwd($wuser,$confirm,$_POST[newpasswd]);
	} else {
		echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>El usuario es incorrecto.</div>\n";
		chgpwd_form($confirm,$url1);	
	}

} else {
	if (!isset($confirm) || $confirm=="" ){
		forgot_form($url1);
	} else {
		$tmpuid= checkconfirm($confirm,$retype);
		if	( $tmpuid>0 ){
			chgpwd_form($confirm,$url1,$tmpuid);
		} else {
			echo "Error: ".translate("Operación no permitida.");
		}
	}
}

?>
