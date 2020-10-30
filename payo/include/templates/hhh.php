<body class="common-home">
<nav id="top">
  <div class="container">
	<?php
	$db = connect();
	$query = "select * from e_telefonos order by id_seccion,nombre";
	$result = $db->Execute($query);
	if ($result && !$result->EOF){
	?>
    <div id="top-links" class="nav pull-left">
        <a href="auxiliar.php?op=phones" id="phone_lnk" class="phone_lnk" style="color:red"><i class="fa fa-phone"></i> Tel&eacute;fonos</a>
    </div>
	<?php
	}	
	?>


	<div id="top-links" class="nav pull-right">
      <ul class="list-inline">
      <li class="dropdown"><a href= "" title="Perfil" class="dropdown-toggle" data-toggle="dropdown" style="color:blue"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $_SESSION["user_alias"]; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($_SESSION['logged']) { ?>
            <li><a href="<?php echo $_SERVER['PHP_SELF']."?op=profile"; ?>">Perfil</a></li>
            <li><a href="<?php echo $_SERVER['PHP_SELF']."?auth=logout"; ?>">Salir</a></li>
            <?php } else { ?>
            <li><a href="<?php echo $_SERVER['PHP_SELF']."?auth=registration"; ?>">Registro</a></li>
            <li><a href="<?php echo $_SERVER['PHP_SELF']."?auth=login" ?>">Acceso</a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div id="logo">
           <a href="index.php"><img src="image/logo.gif" title="<?php echo $default->site_description; ?>" alt="<?php echo $default->site_description; ?>" class="img-responsive" /></a>
        </div>
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