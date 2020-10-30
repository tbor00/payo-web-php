<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["<?php echo $default->table_bgcolor ?>"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</SCRIPT>
<TABLE WIDTH="100%" BGCOLOR="<?php echo $default->border_color ?>" BORDER="0" CELLPADDING="0" CELLSPACING="1">
<TR><TD>
<TABLE WIDTH='100%' BORDER='0' CELLPADDING='1' CELLSPACING='1' BGCOLOR='<?php echo $default->title_bgcolor ?>'>
<TR>
<TD VALIGN='MIDDLE' ALIGN="LEFT"><H6 STYLE="color: <?php echo $default->title_color ?>;"><?php echo $default->web_title ?></H6></TD>
<TD VALIGN="MIDDLE" ALIGN="RIGHT"><A style="color:white; text-decoration: none;font-weight: bold;" TITLE="Mis Datos" HREF="<?php echo "$PHP_SELF?accion=misdatos&id=$_SESSION[uid]" ?>"><i class="fa fa-user"></i> <?php echo $_SESSION["user_name"]?></A>
&nbsp;&nbsp;<A HREF="index.php?authen=logout"><IMG SRC="img/close.gif" BORDER="0" ALIGN="ABSMIDDLE" ALT="Desconectarse" TITLE="Desconectarse"></A></TD></TR>
</TABLE>
</TD></TR>
<TR><TD>
<TABLE WIDTH='100%' BORDER='0' CELLPADDING='1' CELLSPACING='1' BGCOLOR='<?php echo $default->table_bgcolor ?>'>
<TR><TD VALIGN='LEFT'>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Administraci&oacute;n</a>
	<ul>
	<li><a href="index.php?accion=general">General</a></li>
	<li><a href="index.php?accion=usuarios">Administradores</a></li>
	<li><a href="#">Tablas</a>
	<ul>
		<li><a href="index.php?accion=mensajes">Mensajes del Sistema</a></li>
		<li><a href="index.php?accion=provincias">Provincias</a></li>
		<li><a href="index.php?accion=keys">Keywords</a></li>
	</ul>
	</li>
	</ul>
</li>
<li><a href="#">Contenido</a>
	<ul>
	<li><a href="index.php?accion=menues">Men&uacute; Superior</a></li>
	<li><a href="index.php?accion=menues_sup">Men&uacute; Inferior</a></li>
	<li></li>
	<li><a href="index.php?accion=novedades">Novedades</a></li>
	<li><a href="index.php?accion=carrousel">Slide Banners</a></li>
	<li></li>
	<li><a href="index.php?accion=telefonos">Tel&eacute;fonos</a></li>
	</ul>
</li>
<li><a href="#">Clientes</a>
	<ul>
	<li><a href="index.php?accion=rubrosw">Rubros</a></li>
	<li><a href="index.php?accion=gestiones">Gestiones</a>
	<li><a href="index.php?accion=vendedores">Vendedores</a></li>
	<li><span></span></li>
	<li><a href="index.php?accion=webusers">Usuarios</a></li>
	<li><a href="index.php?accion=ctasctes">Cuentas Corrientes</a></li>
	</ul>
</li>
<li><a href="#">Productos</a>
	<ul>
	<li><a href="index.php?accion=pproductos">Productos</a></li>
	<li><a href="index.php?accion=marcas">Marcas</a>
	<li><a href="index.php?accion=proveedores">Proveedores</a></li>
	<li><a href="index.php?accion=ofertas">Ofertas</a></li>
	<li><a href="index.php?accion=historial">Historial de Actualizaciones</a></li>
	</ul>
</li>
<li><a href="#">Pedidos</a>
	<ul>
	<li><a href="index.php?accion=pedidos">Pedidos</a></li>
	<li><a href="index.php?accion=descuentos">Descuentos</a>
	</ul>
</li>
</ul>
<br style="clear: left" />
</TD>
</TR></TABLE>
</TD></TR></TABLE>

