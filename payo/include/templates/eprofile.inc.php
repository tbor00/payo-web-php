<?php
// ---------------------------------------------------
// Perfil
// ---------------------------------------------------
?>

  <div class="container" class="col-sm-12">
  <h2>Mi Perfil</h2>
  </div>

  <div class="row"><aside id="column-left" class="col-sm-3">
  <div class="list-group">

  <a href="<?php echo $_SERVER['PHP_SELF']."?op=profile&action=user" ?>" class="list-group-item">Editar Perfil</a>
  <a href="<?php echo $_SERVER['PHP_SELF']."?op=profile&action=changepasswd" ?>" class="list-group-item">Cambiar Contrase&ntilde;a</a>
  <?php
 	if ($_SESSION["nivel"]==1 && $_SESSION["socio"]>0){
	?>
  <a href="<?php echo $_SERVER['PHP_SELF']."?op=profile&action=member" ?>" class="list-group-item">Datos del Socio</a>
  <?php
	}
	?>
  </div>
  </aside>
  <div id="content" class="col-sm-9">
<?php
if ($action=='user'){
	if (file_exists("include/templates/userform.inc.php")){
		include("include/templates/userform.inc.php");
	}
} elseif ($action=='changepasswd') {
	if (file_exists("include/templates/userchangepass.inc.php")){
		include("include/templates/userchangepass.inc.php");
	}
} elseif ($action=='member' && ($_SESSION["nivel"]==1 && $_SESSION["socio"]>0)) {
	if (file_exists("include/templates/memberform.inc.php")){
		include("include/templates/memberform.inc.php");
	}
}
?>
</div>
</div>
