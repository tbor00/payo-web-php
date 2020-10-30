  <nav id="menu" class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 20px">
	<a class="navbar-brand" href="/payo/index.php"><img src="blanco.png" alt="" style="height: 30px;"></a>
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>

  <div class="navbar-right">
  <div class="collapse navbar-collapse navbar-ex1-collapse">  
      <ul class="nav navbar-nav ">
        <li><a class="nav-link" href="/payo/index.php">Inicio<span class="sr-only">(current)</span></a>
        </li>
    </ul>
<?php
if ($_SESSION['logged']){
	$destiny=1;
} else {
	$destiny=0;
}

$query = "SELECT * from e_menues where tipo=1 and activo=1 and destino<=$destiny order by posicion";
$result = $db->Execute($query);
if ($result && !$result->EOF){
	while(!$result->EOF){
		$menu_array[] = array(
			'href'=>$_SERVER['PHP_SELF']."?op=".$result->fields['id_menu'].$result->fields['ejecutar'],
			'titulo'=>$result->fields['titulo'],
		);
		$result->MoveNext();
	}
}

?>
      <ul class="nav navbar-nav">

		<li class="dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" ><?php if ($_SESSION['logged']) { ?><?php echo $_SESSION["user_alias"];?></a>  <?php } else { ?> <?php echo 'Cuenta'; ?></a><?php } ?>
          <ul class="dropdown-menu dropdown-menu-<?php echo ($default->is_mobile ? "right" : "left")?>">
          <?php if ($_SESSION['logged']) { ?>
           <li><a  class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']."?op=profile"; ?>">Mi perfil</a></li>
            <li><a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']."?auth=logout"; ?>">Salir</a></li>
            <?php } else { ?>
            <li><a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']."?auth=login" ?>">Iniciar sesión</a></li>
            <li><a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']."?auth=registration"; ?>">Registrarse</a></li>
            <?php } ?>
          </ul>
          </li>
		  </ul>

<?php
if ( sizeof($menu_array) > 0 ) {
?>

      <ul class="nav navbar-nav">
        <?php foreach ($menu_array as $menu_a) { ?>
        <li><a href="<?php echo $menu_a['href']; ?>"><?php echo $menu_a['titulo']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    </div>
  </nav>
<?php
}
?>
 <?php if ($_SESSION['logged']) { ?>
<header style="background:white;">
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
      </div>
      <div class="col-sm-5">
      </div>
     <div class="col-sm-3">
     <?php
		if ($_SESSION['logged']){
			if (file_exists("include/templates/cart.inc.php")){
				include("include/templates/cart.inc.php");
			}
		}
		?>

	  </div>
    </div>
  </div>
</header>
<?php }