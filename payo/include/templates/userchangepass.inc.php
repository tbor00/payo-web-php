<?php
$error=array();
if ($useraction=="save"){
	$modificar = 1;
	$user_id=$_SESSION['uid'];
	$db=connect();
	$db->debug = SDEBUG;

	if ((strlen($_POST['pass0']) < 6) || (strlen($_POST['pass0']) > 15)){
		$error['pass0']="La contrase&ntilde;a debe tener entre 6 y 15 caracteres.";
	}
	if ((strlen($_POST['pass1']) < 6) || (strlen($_POST['pass1']) > 15)){
		$error['pass1']="La contrase&ntilde;a debe tener entre 6 y 15 caracteres.";
	}
	if ($_POST['pass2']<>$_POST['pass1']){
		$error['pass2']="La contrase&ntilde;as no coinciden.";
	} elseif ((strlen($_POST['pass2']) < 6) || (strlen($_POST['pass2']) > 15)){
		$error['pass2']="La contrase&ntilde;a debe tener entre 6 y 15 caracteres.";
	}


	if (sizeof($error)==0){
		$cons_query = "select * from e_webusers where user_id=". $_SESSION['uid'] ;
		$cons_res=$db->Execute($cons_query);
		if($cons_res && ! $cons_res->EOF ){
			if ($cons_res->fields['password'] == md5($_POST['pass0'])){
				$mquery = "UPDATE e_webusers set password='".MD5($_POST[pass1])."' WHERE user_id=$user_id";
				if (!$db->Execute($mquery)){
				   echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>"."Error intentando guardar en la base de datos."."</div>\n";
					$modificar=0;
				}else{
					echo "<div class=\"alert alert-success\"><i class=\"fa fa-exclamation-circle\"></i>"."Los datos han sido actualizados satisfactoriamente."."</div>\n";
				}
			} else {	
				$error['pass0']="La Contrase&ntilde;a Anterior no es correcta.";
				$modificar=0;
			}
		} else {
			$modificar =0;
		}
	} else {
		$modificar = 0;
	}
} 
if ($modificar==0){
?>
<form action="<?php echo "{$_SERVER['PHP_SELF']}" ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
<input name="op" value="profile" type="hidden">
<input name="action" value="changepasswd" type="hidden">
<input name="useraction" value="save" type="hidden">
<fieldset id="account">
<legend>Cambiar contrase&ntilde;a</legend>

<div class="form-group required">
<label class="col-sm-3 control-label" for="input-pass0">Contrase&ntilde;a anterior</label>
<div class="col-sm-4">
<input type="password" name="pass0" value="" maxlength='15' placeholder="Contrase&ntilde;a" id="input-pass0" class="form-control" />
<?php if ($error['pass0']) { ?>
<div class="text-danger"><?php echo $error['pass0']; ?></div>
<?php } ?>
</div>
</div>
<div class="form-group required">
<label class="col-sm-3 control-label" for="input-pass1">Nueva Contrase&ntilde;a</label>
<div class="col-sm-4">
<input type="password" name="pass1" value="" maxlength='15' placeholder="Contrase&ntilde;a" id="input-pass1" class="form-control" />
<?php if ($error['pass1']) { ?>
<div class="text-danger"><?php echo $error['pass1']; ?></div>
<?php } ?>
</div>
</div>
<div class="form-group required">
<label class="col-sm-3 control-label" for="input-pass2">Repetir Contrase&ntilde;a</label>
<div class="col-sm-4">
<input type="password" name="pass2" value="" maxlength='15' placeholder="Contrase&ntilde;a" id="input-pass2" class="form-control" />
<?php if ($error['pass2']) { ?>
<div class="text-danger"><?php echo $error['pass2']; ?></div>
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
}
?>