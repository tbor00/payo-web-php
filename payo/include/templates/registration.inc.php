<?php
function form_user(){
	global $op, $error;
	$username = rawurldecode($_POST['username']);
	$nombres = rawurldecode($_POST['nombres']);
	$apellidos = rawurldecode($_POST['apellidos']);
	$email = rawurldecode($_POST['email']);
	$razonsocial = rawurldecode($_POST['razonsocial']);
	$direccion = rawurldecode($_POST['direccion']);
	$ciudad = rawurldecode($_POST['ciudad']);
	$cp = rawurldecode($_POST['cp']);
	$iva = $_POST['iva'];
	$provincia_id = $_POST['provincia_id'];
	$telefonos = rawurldecode($_POST['telefonos']);
	$web = rawurldecode($_POST['web']);
	$rubro_id = $_POST['rubro_id'];
	$pregunta = rawurldecode($_POST['pregunta']);
	$respuesta = rawurldecode($_POST['respuesta']);
	$valida = $_POST['valida'];
	$news = $_POST['news'];
	$cuit = rawurldecode($_POST['cuit']);

	$db = connect();
	$db->debug = SDEBUG;
		
	?>
	<div class="row">     
	<div id="content" class="col-sm-12">

	<form action="<?php echo "{$_SERVER['PHP_SELF']}" ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
	<input type="hidden" name="useraction" value="save">
	<input type="hidden" name="op" value="<?php echo $op ?>">
	<input type="hidden" name="auth" value="registration">
	<fieldset id="account">
	<legend>Datos de la cuenta</legend>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-username">Usuario</label>
	<div class="col-sm-4">
	<input type="text" name="username" value="<?php echo htmlspecialchars($username) ?>" maxlength='30' placeholder="Usuario" id="input-username" class="form-control" />
	<?php if ($error['username']) { ?>
	<div class="text-danger"><?php echo $error['username']; ?></div>
	<?php } ?>
	</div>
	</div>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-password1">Contrase&ntilde;a</label>
	<div class="col-sm-4">
	<input type="password" name="password1" value="" maxlength='15' placeholder="Contrase&ntilde;a" id="input-password1" class="form-control" />
	<?php if ($error['password1']) { ?>
	<div class="text-danger"><?php echo $error['password1']; ?></div>
	<?php } ?>
	</div>
	</div>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-password2">Repetir Contrase&ntilde;a</label>
	<div class="col-sm-4">
	<input type="password" name="password2" value="" maxlength='15' placeholder="Contrase&ntilde;a" id="input-password2" class="form-control" />
	<?php if ($error['password2']) { ?>
	<div class="text-danger"><?php echo $error['password2']; ?></div>
	<?php } ?>
	</div>
	</div>
	</fieldset>

	<fieldset id="accountinfo">
	<legend>Informaci&oacute;n de la cuenta</legend>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-names">Nombres</label>
	<div class="col-sm-10">
	<input type="text" name="nombres" value="<?php echo htmlspecialchars($nombres);?>" maxlength='60' placeholder="Nombres" id="input-names" class="form-control" />
	<?php if ($error['nombres']) { ?>
	<div class="text-danger"><?php echo $error['nombres']; ?></div>
	<?php } ?>
	</div>
	</div>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-surnames">Apellidos</label>
	<div class="col-sm-10">
	<input type="text" name="apellidos" value="<?php echo htmlspecialchars($apellidos);?>" maxlength='60' placeholder="Apellidos" id="input-surnames" class="form-control" />
	<?php if ($error['apellidos']) { ?>
	<div class="text-danger"><?php echo $error['apellidos']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-email">Direcci&oacute;n de e-mail</label>
	<div class="col-sm-10">
	<input type="text" name="email" value="<?php echo htmlspecialchars($email);?>" maxlength='120' placeholder="Direcci&oacute;n de e-mail" id="input-email" class="form-control" />
	<?php if ($error['email']) { ?>
	<div class="text-danger"><?php echo $error['email']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-razonsocial">Raz&oacute;n Social</label>
	<div class="col-sm-10">
	<input type="text" name="razonsocial" value="<?php echo htmlspecialchars($razonsocial);?>" maxlength='50' placeholder="Raz&oacute;n Social" id="input-razonsocial" class="form-control" />
	<?php if ($error['razonsocial']) { ?>
	<div class="text-danger"><?php echo $error['razonsocial']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-direccion">Domicilio</label>
	<div class="col-sm-10">
	<input type="text" name="direccion" value="<?php echo htmlspecialchars($direccion);?>" maxlength='50' placeholder="Domicilio" id="input-direccion" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-ciudad">Ciudad</label>
	<div class="col-sm-10">
	<input type="text" name="ciudad" value="<?php echo htmlspecialchars($ciudad);?>" maxlength='50' placeholder="Ciudad" id="input-ciudad" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-cp">C&oacute;digo Postal</label>
	<div class="col-sm-3">
	<input type="text" name="cp" value="<?php echo htmlspecialchars($cp);?>" maxlength='10' placeholder="C&oacute;digo Postal" id="input-cp" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-provincia">Provincia</label>
	<div class="col-sm-5">
	<select name="provincia_id" id="input-provincia" class="form-control" />
	<OPTION VALUE="0">----------------------</OPTION>
	<?php
	$provq = "select * from provincias;";
	if ($provr = $db->Execute($provq)){
	    while( !$provr->EOF ){
	        echo "<OPTION VALUE=\"".$provr->fields['id_provincia']."\"";
	        if ($provincia_id==$provr->fields['id_provincia']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields['provincia']."</OPTION>\n";
	        $provr->MoveNext();
	    }
	}
	?>
	</select>
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-telefonos">Tel&eacute;fono</label>
	<div class="col-sm-10">
	<input type="text" name="telefonos" value="<?php echo htmlspecialchars($telefonos);?>" maxlength='50' placeholder="Tel&eacute;fono" id="input-telefonos" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-web">Web</label>
	<div class="col-sm-10">
	<input type="text" name="web" value="<?php echo htmlspecialchars($web);?>" maxlength='50' placeholder="Web" id="input-web" class="form-control" />
	</div>
	</div>
	

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-rubro">Rubro</label>
	<div class="col-sm-5">
	<select name="rubro_id" id="input-rubro" class="form-control" />
	<OPTION VALUE="0">----------------------</OPTION>
	<?php
	$provq = "select * from e_rubrosw order by rubrow";
	if ($provr = $db->Execute($provq)){
	    while( !$provr->EOF ){
	        echo "<OPTION VALUE=\"".$provr->fields['id_rubrow']."\"";
	        if ($rubro_id==$provr->fields['id_rubrow']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields['rubrow']."</OPTION>\n";
	        $provr->MoveNext();
	    }
	}
	?>
	</select>
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-iva">Condici&oacute;n ante el IVA</label>
	<div class="col-sm-5">
	<select name="iva" id="input-iva" class="form-control" />
	<OPTION VALUE="0">----------------------</OPTION>
	<?php
	$provq = "select * from e_ivas;";
	if ($provr = $db->Execute($provq)){
	    while( !$provr->EOF ){
	        echo "<OPTION VALUE=\"".$provr->fields['id_iva']."\"";
	        if ($iva==$provr->fields['id_iva']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields['descripcion']."</OPTION>\n";
	        $provr->MoveNext();
	    }
	}
	?>
	</select>
	</div>
	</div>

	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-cuit">C.U.I.T./C.U.I.L./D.N.I.</label>
	<div class="col-sm-5">
	<input type="text" name="cuit" value="<?php echo $ures->fields['cuit'];?>" maxlength='13' placeholder="C.U.I.T." id="input-cuit" class="form-control" />
	<?php if ($error['cuit']) { ?>
	<div class="text-danger"><?php echo $error['cuit']; ?></div>
	<?php } ?>
	</div>
	</div>

	</fieldset>

	<fieldset id="newsletter">
	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-pregunta">Subscripci&oacute;n al Bolet&iacute;n</label>
	<div class="col-sm-10">
	<input type="checkbox" name="news" value="1" checked placeholder="Bolet&iacute;n de Noticias" id="input-news">
	</div>
	</div>
	</fieldset>

	<fieldset id="resetpasswd">
	<legend>Opciones para restablecer la contrase&ntilde;a</legend>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-pregunta">Pregunta secreta</label>
	<div class="col-sm-10">
	<input type="text" name="pregunta" value="<?php echo htmlspecialchars($pregunta);?>" maxlength='100' placeholder="Pregunta" id="input-pregunta" class="form-control" />
	<?php if ($error['pregunta']) { ?>
	<div class="text-danger"><?php echo $error['pregunta']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-respuesta">Respuesta</label>
	<div class="col-sm-10">
	<input type="text" name="respuesta" value="<?php echo htmlspecialchars($respuesta);?>" maxlength='100' placeholder="Respuesta" id="input-respuesta" class="form-control" />
	<?php if ($error['respuesta']) { ?>
	<div class="text-danger"><?php echo $error['respuesta']; ?></div>
	<?php } ?>
	</div>
	</div>
	</fieldset>

	<fieldset id="validate">
	<legend></legend>
	<div class="form-group">
	<label class="col-sm-2"><span>&nbsp;</span></label>
	<div id="recaptcha" class="col-sm-10 text-right">
	<div class="g-recaptcha" data-sitekey="<?php echo $default->captcha_site_key ?>"></div>
	<?php if ($error['captcha']) { ?>
	<div class="text-danger"><?php echo $error['captcha']; ?></div>
	<?php } ?>
	</div>
	</div>
	</fieldset>

	<div class="buttons clearfix">
	<div class="pull-left"><a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="btn btn-default">Cancelar</a></div>
	<div class="pull-right">
	<input type="submit" value="Aceptar" class="btn btn-primary" />
	</div>
	</div>

	</form>
	</div>
	</div>
<?php
}
//---------------------------------------------------------------------
$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$razonsocial = $_POST['razonsocial'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$cp = $_POST['cp'];
$cuit = $_POST['cuit'];
$iva = $_POST['iva'];
$provincia_id = $_POST['provincia_id'];
$telefonos = $_POST['telefonos'];
$web = $_POST['web'];
$rubro_id = $_POST['rubro_id'];
$pregunta = $_POST['pregunta'];
$respuesta = $_POST['respuesta'];
$news = $_POST['news'];
$valida = $_POST['valida'];
$auth = $_POST['auth'];
$error=array();


setlocale(LC_CTYPE , "en_US");
if ($useraction=="save" && $auth=='registration'){
	if (strtoupper($valida)<>strtoupper($_SESSION['rand_code']) && $valida==""){
		$error['captcha']="El c&oacute;digo no corresponde con la imagen!";
	}

	if ((strlen($username) < 3) || (strlen($username) > 30)){
		$error['username']="El usuario debe tener entre 3 y 30 caracteres.";
	}

	if ((strlen($password1) < 6) || (strlen($password1) > 15)){
		$error['password1']="La contrase&ntilde;a debe tener entre 6 y 15 caracteres.";
	}

	if (($password2<>$password1)){
		$error['password2']="La contrase&ntilde;as no coinciden.";
	} elseif ((strlen($password2) < 6) || (strlen($password2) > 15)){
		$error['password2']="La contrase&ntilde;a debe tener entre 6 y 15 caracteres.";
	}

	if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
		$error['email'] = "La direcci&oacute;n de e-mail no es v&aacute;lida.";
	}

	if ((strlen($nombres) < 3) || (strlen($nombres) > 50)){
		$error['nombres']="El nombre debe tener entre 3 y 50 caracteres.";
	}

	if ((strlen($apellidos) < 3) || (strlen($apellidos) > 50)){
		$error['apellidos']="El apellido debe tener entre 3 y 50 caracteres.";
	}
	if ((strlen($_POST['cuit']) < 3) || (strlen($_POST['cuit']) > 15)){
		$error['cuit']="El C.U.I.T./C.U.I.L./D.N.I. debe contener datos.";
	}
	if ((strlen($pregunta) < 3) || (strlen($pregunta) > 120)){
		$error['pregunta']="La pregunta secreta debe tener entre 3 y 120 caracteres.";
	}
	if ((strlen($respuesta) < 3) || (strlen($respuesta) > 120)){
		$error['respuesta']="La respuesta debe tener entre 3 y 120 caracteres.";
	}




	$db=connect();
	$db->debug = SDEBUG;
	$cons_query = "select user_id from e_webusers where username=?";
	$cons_res=$db->Execute($cons_query,array($username));
	if($cons_res && ! $cons_res->EOF ){
		$texto="";
		$error['username']="El usuario ya se encuentra registrado.";
	}
	$cons_query = "select user_id from e_webusers where email=?";
	$cons_res=$db->Execute($cons_query,array($email));
	if($cons_res && ! $cons_res->EOF ){
		$texto="";
		$error['email']="La direcci&oacute;n de e-mail ya se encuentra registrada.";
	}
	if ($news==''){
		$news=0;
	}
	if (sizeof($error)==0){
		$date=date("d/m/Y");
		$password=$password1;
		$config['tabla']="e_webusers";
		$config['camposes']=array(
			'user_id'=>"auto_increment",
			'username'=>"$username",
			'password'=>MD5("$password"),
			'email'=>"$email",
			'nombres'=>"$nombres",
			'apellidos'=>"$apellidos",
			'pregunta'=>"$pregunta",
			'respuesta'=>"$respuesta",
			'activo_u'=>"1",
			'fecha_reg'=>timestd2sql("$date"),
         'razonsocial'=>"$razonsocial",
         'direccion'=>"$direccion",
         'ciudad'=>"$ciudad",
         'cp'=>"$cp",
         'provincia_id'=>"$provincia_id",
         'telefonos'=>"$telefonos",
         'web'=>"$web",
         'rubro_id'=>"$rubro_id",
         'iva'=>"$iva",
         'cuit'=>"$cuit",
			'news'=>"$news",
			);
		if (alta($config)){
			$enviado=1;

			$mail_body = txt_email_body("ELECTROPUERTO MAX");

			$body="Se ha registrado un nuevo Usuario<BR><BR>";
			$body.="Nombre y Apellido: <STRONG>$nombres $apellidos</STRONG><BR>";
			$body.="Mail: <STRONG>$email</STRONG><BR>";
			$body.="Nombre de Usuario: <STRONG>$username</STRONG><BR>";
			$body.="Empresa: <STRONG>$razonsocial</STRONG><BR>";
			$body.="Dirección: <STRONG>$direccion</STRONG><BR>";
			$body.="Localidad: <STRONG>$ciudad</STRONG><BR>";
			$body.="Cód. Postal: <STRONG>$cp</STRONG><BR>";
			$body.="Fecha de registración: <STRONG>$date</STRONG><BR><BR>";
			$abody="Ud. lo debe ACTIVAR si desea que ingrese como Usuario Registrado<BR>";
			$mmail_body = str_replace("##TEXTO##","$body"."$abody",$mail_body); 
			$txt_body  = str_replace("<BR>","\n",nl2br($body.$abody)); 
			$mail = new PHPMailer();
	  		$mail->IsHTML(true);
			$mail->From = txt_email_to();
			$mail->FromName = $default->mailfromname;
			$mail->Mailer   = "mail";
			$mail->AddAddress(txt_email_to(),$default->mailfromname);
			$mail->Priority = 1;
			$mail->Subject = $default->web_title ." - ". translate("Registro de Usuario");
			$mail->AddEmbeddedImage("image/logo.gif", "logo", "logo.gif");
			$mail->AltBody = $txt_body;
			$mail->Body = $mmail_body;
			$mail->Send();	
			$mail->ClearAddresses();
			$mail->ClearReplyTos();
			$regok = section_text("REGISTRO_OK",1);
			echo $regok;
			
			// envio mail de notificación para el usuario
			$body="Solicitud de registro<BR><BR>";
			$body.="Nombre y Apellido: <STRONG>$nombres $apellidos</STRONG><BR>";
			$body.="Mail: <STRONG>$email</STRONG><BR>";
			$body.="Nombre de Usuario: <STRONG>$username</STRONG><BR>";
			$body.="Empresa: <STRONG>$razonsocial</STRONG><BR>";
			$body.="Dirección: <STRONG>$direccion</STRONG><BR>";
			$body.="Localidad: <STRONG>$ciudad</STRONG><BR>";
			$body.="Cód. Postal: <STRONG>$cp</STRONG><BR>";
			$body.="Fecha de registración: <STRONG>$date</STRONG><BR><BR>";
		   
			$mmail_body = str_replace("##TEXTO##","$body"."$regok",$mail_body); 
			$txt_body  = str_replace("<BR>","\n",nl2br($body.$regok)); 
			$mail = new PHPMailer();
	  		$mail->IsHTML(true);
			$mail->From = txt_email_to();
			$mail->FromName = $default->mailfromname;
			$mail->Mailer   = "mail";
			$mail->AddAddress($email);
			$mail->Priority = 1;
			$mail->Subject = $default->web_title ." - ". translate("Solicitud de Registro de Usuario");
			$mail->AddEmbeddedImage("image/logo.gif", "logo", "logo.gif");
			$mail->AltBody = $txt_body;
			$mail->Body = $mmail_body;
			$mail->Send();	
			$mail->ClearAddresses();
			$mail->ClearReplyTos();

		} else {
		   $a_alert[]="Error intentando guardar en la base de datos.";
		}
	}
}

if ($enviado==0) {
	echo section_text("REGISTRO_FORM",1);
	form_user();
}
//echo "</TD></TR></TABLE>";
?>