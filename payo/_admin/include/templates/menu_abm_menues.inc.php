<?php
if (isset($config['orden'])) {
	$url_orden="&orden=$config[orden]&ord=$config[ord]";
}
if ($config['set_idioma']=="yes") {
	$url_idioma="&idioma=$config[idioma]";
}
if (isset($config['buscar']) && isset($config['buscarx'])) {
	$url_busqueda="&busca=$config[buscar]&buscarx=$config[buscarx]";
}
?>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="1" CELLSPACING="0" BGCOLOR="<?php echo $default->border_color ?>">
<TR><TD>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="1" CELLSPACING="0" BGCOLOR="<?php echo $default->title_bgcolor ?>">
<TR><TD>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR><TD>
<STRONG STYLE="color: <?php echo $default->title_color ?>;">&nbsp;<?php echo convert_str($config['menu'],$default->encode)  ?>
<?php
$total_paginas = ceil($cant_reg/$config['lineas']);
if (isset ($sq_reg)) {
	echo " - P&aacute;gina $config[pag] de $total_paginas &nbsp;";
}
echo "</STRONG></TD><TD>";
?>
</TD></TR></TABLE>
</TD>
<TD VALIGN='MIDDLE' ALIGN='RIGHT'>
<?php
if($config[set_idioma]=="yes"){
	echo "<TABLE BORDER='0' CELLPADDING='1' CELLSPACING='0'>";
	echo "<TR><TD><P STYLE='color: $default->title_color'>Lenguaje:</P></TD><TD>";
	echo "<FORM>";
	echo "<SELECT ONCHANGE=\"document.location=this.value;\" CLASS='select' NAME='idioma' SIZE='1'>";
	if ($lngresult = $db->Execute("SELECT * FROM lenguajes ORDER BY id_lenguaje")) {
		while(!$lngresult->EOF) {
			if ($config['idioma']== $lngresult->fields[0]) {
				$seleccionado = 'SELECTED ';
			} else {
				$seleccionado = '';
			}        
			echo "<OPTION ".$seleccionado. "VALUE='$PHP_SELF?accion={$config[accion]}&op=l&idioma=".$lngresult->fields[0]."'> ".$lngresult->fields[1] ."</OPTION>";
			$lngresult->MoveNext();
		}
	} else {
    	    echo "<OPTION VALUE=''>-----</OPTION>";
	}
	echo "</SELECT>";
	echo "</FORM>";
	echo "</TD></TR></TABLE>";
}
?>
</TD>
</TR>
</TABLE>
</TD></TR></TABLE>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="1" CELLSPACING="0" BGCOLOR="<?php echo $default->border_color ?>">
<TR><TD>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="2" CELLSPACING="0" BGCOLOR="<?php echo $default->table_bgcolor ?>">
<TR>
<TD VALIGN="MIDDLE" ALIGN="LEFT" WIDTH="30%">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="2"> 
<TR> 
<TD VALIGN="MIDDLE" ALIGN="CENTER">
<?php 
if($config['agregar']=="yes"){
	echo "<SMALL><A  TITLE='Agregar' HREF='$PHP_SELF?accion={$config[accion]}$url_orden&op=a&pag=$config[pag]$url_idioma$url_busqueda' TITLE='Agregar'><IMG SRC='img/new.gif' ALT='Agregar' BORDER='0' TITLE='Agregar'></A></SMALL>";
}else {
	echo "&nbsp;";
}
?>
</TD>
<?php 
if($config['eorden']=="yes"){
	echo "<TD VALIGN='MIDDLE' ALIGN='CENTER'>";
	echo "<SMALL><A  TITLE='Ordenar' HREF='$PHP_SELF?accion={$config[accion]}$url_orden&op=o&pag=$config[pag]$url_idioma$url_busqueda'><IMG SRC='img/eorden.gif' ALT='Ordenar' BORDER='0' TITLE='Ordenar'></A></SMALL>";
	echo "</TD>";
}	
if (is_array($config['menu_extra'])){

	for($k=0; $k<count($config['menu_extra']); $k++){
		echo "<TD VALIGN='MIDDLE' ALIGN='CENTER'>";
		if ($config['menu_extra'][$k]['menu_extra_script']) {
			echo $config['menu_extra'][$k]['menu_extra_script'];
		}
		echo "<SMALL><A HREF='".$config['menu_extra'][$k]['menu']."'";
		if ($config['menu_extra'][$k]['menu_extra_target'] != ""){
			echo " TARGET='".$config['menu_extra'][$k]['menu_extra_target']."'";
		}
		echo "><IMG SRC='".$config['menu_extra'][$k]['menu_extra_img']."' TITLE='".$config['menu_extra'][$k]['menu_extra_tit']."' BORDER='0'><BR>".$config['menu_extra'][$k]['menu_extra_tit']."</A></SMALL>";
		echo "</TD>";

	}	
} else {
	if($config['menu_extra']){
		echo "<TD VALIGN='MIDDLE' ALIGN='CENTER'>";
		if ($config['menu_extra_script']) {
			echo $config['menu_extra_script'];
		}
		echo "<SMALL><A HREF='".$config['menu_extra']."'";
		if ($config['menu_extra_target'] != ""){
			echo " TARGET='".$config['menu_extra_target']."'";
		}
		echo "><IMG SRC='".$config['menu_extra_img']."' ALT='".$config['menu_extra_tit']."' BORDER='0' TITLE='".$config['menu_extra_tit']."'></A></SMALL>";
		echo "</TD>";
	}	
}
?>
</TR>
</TABLE>
</TD>
<TD VALIGN="MIDDLE" ALIGN="CENTER" WIDTH="40%">
<?php
?>
</TD>
<TD VALIGN="MIDDLE" ALIGN="RIGHT" WIDTH="30%">

</TR>
</TABLE>
</TD>
</TR>
</TABLE>
