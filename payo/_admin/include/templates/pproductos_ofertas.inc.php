<?php
if ($result->fields[cod_prod]!=""){
	list($year,$month,$day) = split("[/.-]",$result->fields[fecha_baja]);
	if (mktime(0,0,0,$month,$day,$year) >= mktime(0,0,0,date('m'),date('d'),date('Y'))){
		echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=om&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='img/lamp_green.gif' ALT='Modificar Oferta' BORDER='0' TITLE='Modificar Oferta'></A>";
	} else {
		echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=om&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='img/lamp_red.gif' ALT='Modificar Oferta' BORDER='0' TITLE='Modificar Oferta'></A>";
	}
} else {
	echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=oa&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='img/lamp_grey.gif' ALT='Activar Oferta' BORDER='0' TITLE='Activar Oferta'></A>";
}
?>