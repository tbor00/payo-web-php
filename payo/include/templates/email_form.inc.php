<?php
setlocale(LC_CTYPE , "en_US");
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$consulta = $_POST['consulta'];
$oc = $_POST['oc'];
if ($default->use_recaptcha){
	$valida = $_POST['g-recaptcha-response'];
} else {
	$valida = $_POST['valida'];
}

if ($_POST['oc']=="sndmail"){

	if ((strlen($nombre) < 3) || (strlen($nombre) > 60)) {
		$error_name = "El nombre debe tener entre 3 y 60 caracteres.";
	}
	
	if ((strlen($apellido) < 3) || (strlen($apellido) > 60)) {
		$error_lastname = "El apellido debe tener entre 3 y 60 caracteres.";
	}
	
	if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
		$eroor_email = "La direcci&oacute;n de e-mail no es v&aacute;lida.";
	}
	
	if ((strlen($consulta) < 10) || (strlen($consulta) > 2000)) {
		$error_enquiry = "La consulta debe tener entre 10 y 2000 caracteres.";
	}
	
	if ($default->use_recaptcha){

		if (empty($valida)){
			$error_captcha = "Error. Debe completar el robot"; ;
		} else {
			$curlSession = curl_init();
			curl_setopt($curlSession, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret='.$default->recaptcha_secret.'&response='.$valida);
			curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, TRUE);
			$verifyResponse = curl_exec($curlSession);
			curl_close($curlSession);

			//$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$default->recaptcha_secret.'&response='.$valida);
			$captcha = json_decode($verifyResponse);
			if($captcha->success){ 
				//Captcha correcto 
			} else { 
				$error_captcha = "Error"; //$captcha['error-codes'];
			}
		}
	} else {
        if (strtoupper($valida)!=strtoupper($_SESSION['rand_code']) || $valida=="") {
                $error_captcha = "El c&oacute;digo no corresponde con la imagen!";
        }
	}

	if ($error_captcha=="" && $error_enquiry=="" && $error_email=="" && $error_lastname=="" && $error_name==""){
		$enviado = 1;
		$message="";
		$message.="Nombres: $nombre\n";
		$message.="Apellidos: $apellido\n";
		$message.="Email: $email\n";
		$message.="Comentarios: ".stripslashes($consulta)."\n";
		$mail = new PHPMailer();
		$mail->From = $email;
		$mail->FromName = "$nombre $apellido";
		$mail->Mailer   = "mail";
		$mail->Subject = "Mensaje desde la web";
		$mail->Body = $message;
		$mail->AddAddress(txt_email_to());
		$mail->AddReplyTo($email);
		if(!$mail->Send()){
			$texto = $texto . "<P STYLE=\"color:red\">"."Error"."</P>";	
		} else {
			$texto="";
		   echo section_text("CONTACTO_OK");
		}
		$mail->ClearAddresses();
		$mail->ClearReplyTos();
	}
}
if ($enviado==0 ){
	if ($_SESSION['logged']){
		if ($nombre==''){
			$nombre = $_SESSION["user_nombres"];
		}
		if ($apellido==''){
			$apellido = $_SESSION["user_apellidos"];
		}
		if ($email==''){
			$email = $_SESSION["user_email"];
		}
	}
?>
<div class="row">                
<div id="content" class="col-sm-12">      
<?php
	$db = connect();
	$query = "select * from e_telefonos order by id_seccion,nombre";
	$result = $db->Execute($query);
	if ($result && !$result->EOF){
		echo "<legend>Tel&eacute;fonos</legend>\n";
		$query = "select * from e_telefonos where id_seccion=1 order by id_seccion,nombre";
		$vresult = $db->Execute($query);
		if ($vresult && !$vresult->EOF){
			echo "<ul>";
			echo "<li style=\"font-weight: bold; list-style: none;\">Ventas</li>\n";
			while(!$vresult->EOF){
				echo "<li>".$vresult->fields['nombre']."&nbsp;(".$vresult->fields['telefono'].")"."<a title=\"Llamar\" href=\"tel:".$vresult->fields['telefono']."\"><i class=\"fa fa-phone\" style=\"color:blue;padding-left:15px;font-size:20px\"></i></a>";
				if ($vresult->fields['wapp']==1){
						echo "<a title=\"Whatsapp\" href=\"https://api.whatsapp.com/send?phone=549".$vresult->fields['telefono']."\" target=\"_new\"><i class=\"fa fa-whatsapp\" style=\"color:green;padding-left:15px;font-size:20px\"></i></a>";
				}
				echo "</li>\n";
				$vresult->MoveNext();
			}
			echo "</ul>";
		}	
		$query = "select * from e_telefonos where id_seccion=2 order by id_seccion,nombre";
		$vresult = $db->Execute($query);
		if ($vresult && !$vresult->EOF){
			echo "<ul>";
			echo "<li style=\"font-weight: bold; list-style: none;\">Administraci&oacute;n</li>\n";
			while(!$vresult->EOF){
				echo "<li>".$vresult->fields['nombre']."&nbsp;(".$vresult->fields['telefono'].")"."<a title=\"Llamar\" href=\"tel:".$vresult->fields['telefono']."\"><i class=\"fa fa-phone\" style=\"color:blue;padding-left:15px;font-size:20px\"></i></a>";
				if ($vresult->fields['wapp']==1){
						echo "<a title=\"Whatsapp\" href=\"https://api.whatsapp.com/send?phone=549".$vresult->fields['telefono']."\" target=\"_new\"><i class=\"fa fa-whatsapp\" style=\"color:green;padding-left:15px;font-size:20px\"></i></a>";
				}
				echo "</li>\n";
				$vresult->MoveNext();
			}
			echo "</ul>";
		}
		echo "<br>";
	}	

?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
<input name="op" value="<?php echo $op ?>" type="hidden">
<input name="oc" value="sndmail" type="hidden">
<?php
if ($sop != ""){
	echo "<input name=\"sop\" value=\"$sop\" type=\"hidden\">\n";
}
?>
<fieldset>
<legend>Formulario de contacto</legend>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-name">Nombre</label>
<div class="col-sm-10">
<input type="text" name="nombre" value="<?php echo $nombre ?>" id="input-name" class="form-control" />
<?php if ($error_name) { ?>
<div class="text-danger"><?php echo $error_name; ?></div>
<?php } ?>
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-name">Apellido</label>
<div class="col-sm-10">
<input type="text" name="apellido" value="<?php echo $apellido ?>" id="input-lastname" class="form-control" />
<?php if ($error_lastname) { ?>
<div class="text-danger"><?php echo $error_lastname; ?></div>
<?php } ?>
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-email">E-mail</label>
<div class="col-sm-10">
<input type="text" name="email" value="<?php echo $email ?>" id="input-email" class="form-control" />
<?php if ($error_email) { ?>
<div class="text-danger"><?php echo $error_email; ?></div>
<?php } ?>
</div>
</div>

<div class="form-group required">
<label class="col-sm-2 control-label" for="input-enquiry">Consulta</label>
<div class="col-sm-10">
<textarea name="consulta" rows="10" id="input-enquiry" class="form-control"><?php  echo $consulta ?></textarea>
<?php if ($error_enquiry) { ?>
<div class="text-danger"><?php echo $error_enquiry; ?></div>
<?php } ?>
</div>
</div>
<?php 
if ($default->use_recaptcha){
?>
<div class="form-group">
<label class="col-sm-2"><span>&nbsp;</span></label>
<script src='https://www.google.com/recaptcha/api.js' async defer ></script>
<div id="recaptcha" class="col-sm-10">
<div class="g-recaptcha" data-sitekey="<?php echo $default->recaptcha_site_key;?>"></div>
<?php if ($error_captcha) { ?>
<div class="text-danger"><?php echo $error_captcha; ?></div>
<?php } ?>
</div>
</div>
<?php
} else {
$code = e_randomTEXT();
$_SESSION['rand_code'] = $code;
?>
<div class="form-group required">
<label class="col-sm-2 control-label" for="input-captcha">Introduce el codigo de abajo</label>
<div class="col-sm-10">
<input type="text" name="valida" id="input-captcha" class="form-control" />
<img src="auxiliar.php?op=gencaptcha" alt="" />
<?php if ($error_captcha) {?>
<div class="text-danger"><?php echo $error_captcha; ?></div>
<?php } ?>
</div>
</div>
<?php
}
?>


</fieldset>
<div class="buttons">
<div class="pull-right">
<input class="btn btn-primary" type="submit" value="Enviar" />
</div>
</div>
</form>
</div>
</div>
<?php
}
?>



