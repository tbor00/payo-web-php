<?php
function form_user(){
	global $error;
	$db = connect();
	$db->debug = SDEBUG;
	$uquery = "SELECT * from e_webusers where user_id=".$_SESSION['uid'];
	$ures = $db->Execute($uquery);
	if ($ures && !$ures->EOF){
		
		//---------------------------------------------------------------------------
?>
	<form action="<?php echo "{$_SERVER['PHP_SELF']}" ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
	<input type="hidden" name="useraction" value="save">
	<input type="hidden" name="op" value="profile">
	<input type="hidden" name="action" value="user">
	<fieldset id="account">
	<legend>Informaci&oacute;n de la cuenta</legend>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-nombres">Nombres</label>
	<div class="col-sm-4">
	<input type="text" name="nombres" value="<?php echo $ures->fields['nombres']; ?>" maxlength='60' placeholder="Nombres" id="input-nombres" class="form-control" />
	<?php if ($error['nombres']) { ?>
	<div class="alert-danger"><?php echo $error['nombres']; ?></div>
	<?php } ?>
	</div>
	</div>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-surnames">Apellidos</label>
	<div class="col-sm-10">
	<input type="text" name="apellidos" value="<?php echo $ures->fields['nombres'];?>" maxlength='60' placeholder="Apellidos" id="input-surnames" class="form-control" />
	<?php if ($error['apellidos']) { ?>
	<div class="alert-danger"><?php echo $error['apellidos']; ?></div>
	<?php } ?>
	</div>
	</div>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-email">Direcci&oacute;n de e-mail</label>
	<div class="col-sm-10">
	<input type="text" name="email" value="<?php echo $ures->fields['email'];?>" maxlength='120' placeholder="Direcci&oacute;n de e-mail" id="input-email" class="form-control" />
	<?php if ($error['email']) { ?>
	<div class="alert-danger"><?php echo $error['email']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-razonsocial">Raz&oacute;n Social</label>
	<div class="col-sm-10">
	<input type="text" name="razonsocial" value="<?php echo $ures->fields['razonsocial'];?>" maxlength='50' placeholder="Raz&oacute;n Social" id="input-razonsocial" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-direccion">Domicilio</label>
	<div class="col-sm-10">
	<input type="text" name="direccion" value="<?php echo $ures->fields['direccion'];?>" maxlength='50' placeholder="Domicilio" id="input-direccion" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-ciudad">Ciudad</label>
	<div class="col-sm-10">
	<input type="text" name="ciudad" value="<?php echo $ures->fields['ciudad'];?>" maxlength='50' placeholder="Ciudad" id="input-ciudad" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-cp">C&oacute;digo Postal</label>
	<div class="col-sm-3">
	<input type="text" name="cp" value="<?php echo $ures->fields['cp'];?>" maxlength='10' placeholder="C&oacute;digo Postal" id="input-cp" class="form-control" />
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
	        echo "<OPTION VALUE=\"".$provr->fields[id_provincia]."\"";
	        if ($ures->fields['provincia_id']==$provr->fields['id_provincia']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields[provincia]."</OPTION>\n";
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
	<input type="text" name="telefonos" value="<?php echo $ures->fields['telefonos'];?>" maxlength='50' placeholder="Tel&eacute;fono" id="input-telefonos" class="form-control" />
	</div>
	</div>

	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-web">Web</label>
	<div class="col-sm-10">
	<input type="text" name="web" value="<?php echo $ures->fields['web'];?>" maxlength='50' placeholder="Web" id="input-web" class="form-control" />
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
	        echo "<OPTION VALUE=\"".$provr->fields[id_rubrow]."\"";
	        if ($ures->fields['rubro_id']==$provr->fields['id_rubrow']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields[rubrow]."</OPTION>\n";
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
	        echo "<OPTION VALUE=\"".$provr->fields[id_iva]."\"";
	        if ($ures->fields['iva']==$provr->fields['id_iva']) {
	            echo ' SELECTED="SELECTED"';
	        }
	        echo ">".$provr->fields[descripcion]."</OPTION>\n";
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
	<div class="alert-danger"><?php echo $error['cuit']; ?></div>
	<?php } ?>
	</div>
	</div>
	</fieldset>

	<fieldset id="newsletter">
	<div class="form-group">
	<label class="col-sm-2 control-label" for="input-pregunta">Subscripci&oacute;n al Bolet&iacute;n</label>
	<div class="col-sm-10">
	<input type="checkbox" name="news" value="1" <?php if($ures->fields['news']==1){ echo "checked"; } ?> placeholder="Bolet&iacute;n de Noticias" id="input-news">
	</div>
	</div>
	</fieldset>


	<fieldset id="resetpasswd">
	<legend>Opciones para restablecer la contrase&ntilde;a</legend>
	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-pregunta">Pregunta secreta</label>
	<div class="col-sm-10">
	<input type="text" name="pregunta" value="<?php echo $ures->fields['pregunta'];?>" maxlength='100' placeholder="Pregunta" id="input-pregunta" class="form-control" />
	<?php if ($error['pregunta']) { ?>
	<div class="alert-danger"><?php echo $error['pregunta']; ?></div>
	<?php } ?>
	</div>
	</div>

	<div class="form-group required">
	<label class="col-sm-2 control-label" for="input-respuesta">Respuesta</label>
	<div class="col-sm-10">
	<input type="text" name="respuesta" value="<?php echo $ures->fields['respuesta'];?>" maxlength='100' placeholder="Respuesta" id="input-respuesta" class="form-control" />
	<?php if ($error['respuesta']) { ?>
	<div class="alert-danger"><?php echo $error['respuesta']; ?></div>
	<?php } ?>
	</div>
	</div>
	</fieldset>

	<div class="buttons clearfix">
	<div class="pull-left"><a href="<?php echo $_SERVER['PHP_SELF']."?op=profile" ?>" class="btn btn-default">Cancelar</a></div>
	<div class="pull-right">
	<input type="submit" value="Aceptar" class="btn btn-primary" />
	</div>
	</div>
	</form>
<?php
	} else {
		Echo_Error(ERROR_DB_CONNECT);
	}
}
//---------------------------------------------------------------------
$error=array();
if ($useraction=="save"){

	if ((strlen($_POST['nombres']) < 3) || (strlen($_POST['nombres']) > 50)){
		$error['nombres']="El nombre debe tener entre 3 y 50 caracteres.";
	}

	if ((strlen($_POST['apellidos']) < 3) || (strlen($_POST['apellidos']) > 50)){
		$error['apellidos']="El apellido debe tener entre 3 y 50 caracteres.";
	}
	if ((strlen($_POST['pregunta']) < 3) || (strlen($_POST['pregunta']) > 120)){
		$error['pregunta']="La pregunta secreta debe tener entre 3 y 120 caracteres.";
	}
	if ((strlen($_POST['cuit']) < 3) || (strlen($_POST['cuit']) > 15)){
		$error['cuit']="El C.U.I.T./C.U.I.L./D.N.I. debe contener datos.";
	}
	if ((strlen($_POST['respuesta']) < 3) || (strlen($_POST['respuesta']) > 120)){
		$error['respuesta']="El respuesta debe tener entre 3 y 120 caracteres.";
	}

	if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $_POST['email'])) {
		$error['email'] = "La direcci&oacute;n de e-mail no es v&aacute;lida.";
	}

	if (sizeof($error)==0){
		$db = connect();
		$db->debug = SDEBUG;
		if ($_POST['pregunta']!="" && $_POST['respuesta']!=""){
			$sql_panswer = ", pregunta='".$_POST['pregunta']."' , respuesta='".$_POST['respuesta']."'";
		} else {
			$sql_panswer = ", pregunta='' , respuesta=''";
		}
		if ($_POST['news']==''){
			$news=0;
		} else {
			$news=$_POST['news'];
		}

		$sql_panswer .= ", razonsocial='".$_POST['razonsocial']."',";
		$sql_panswer .= "direccion='".$_POST['direccion']."',";
		$sql_panswer .= "ciudad='".$_POST['ciudad']."',";
		$sql_panswer .= "cp='".$_POST['cp']."',";
		$sql_panswer .= "provincia_id='".$_POST['provincia_id']."',";
		$sql_panswer .= "web='".$_POST['web']."',";
		$sql_panswer .= "rubro_id='".$_POST['rubro_id']."',";
		$sql_panswer .= "news='".$news."',";
		$sql_panswer .= "cuit='".$_POST['cuit']."',";
		$sql_panswer .= "iva='".$_POST['iva']."'";

		$uquery = "UPDATE e_webusers SET nombres='".$_POST['nombres']."', apellidos='".$_POST['apellidos']."',email='".$_POST['email']."'$sql_panswer WHERE user_id=".$_SESSION['uid'];
		if ($db->Execute($uquery)){
			$_SESSION["user_alias"]=$_POST['nombres'] . " ". $_POST['apellidos'];
			echo "<div class=\"alert alert-success\"><i class=\"fa fa-exclamation-circle\"></i>"."Los datos han sido actualizados satisfactoriamente."."</div>\n";
		} else {
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>"."Error intentando guardar en la base de datos."."</div>\n";
			form_user();
		}
	} else {
		form_user();
	}
} else {
	form_user();
}
?>

