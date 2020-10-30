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
if (isset($config['parent']) && $config['parent']>0) {
	$url_parent="&parent=$config[parent]";
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
if ($total_paginas > 1){
?>
<FORM NAME="irpag" ACTION="<?php echo "$PHP_SELF?accion={$config[accion]}&op=l$url_orden$url_busqueda$url_idioma$url_parent" ?>" METHOD="POST"> 
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0"> 
<TR VALIGN="MIDDLE"> 
<TD VALIGN="MIDDLE"><INPUT TYPE="TEXT" NAME="gotopag" SIZE="2" CLASS="boxes"></TD> 
<TD VALIGN="MIDDLE" ALIGN="LEFT">&nbsp;<INPUT TYPE="IMAGE" NAME="goto" SRC="img/goto.gif" ALIGN="MIDDLE" ALT="Ir a P&aacute;gina" TITLE='Ir a P&aacute;gina'></TD> 
</TR> 
</TABLE></FORM>
<?php
}
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
	if ($config[parent]){
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"parent\" VALUE=\"$config[parent]\">\n";
	}
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"orden\" VALUE=\"$config[orden]\">";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"accion\" VALUE=\"$config[accion]\">";
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
	echo "<SMALL><A TITLE='Agregar' HREF='$PHP_SELF?accion={$config[accion]}$url_orden&op=a&pag=$config[pag]$url_idioma$url_busqueda$url_parent'><IMG SRC='img/new.gif' ALT='Agregar' BORDER='0' TITLE='Agregar'></A></SMALL>";
}else {
	echo "&nbsp;";
}
?>
</TD>
<?php 
if($config['eorden']=="yes"){
	echo "<TD VALIGN='MIDDLE' ALIGN='CENTER'>";
	echo "<SMALL><A  TITLE='Ordenar' HREF='$PHP_SELF?accion={$config[accion]}$url_orden&op=o&pag=$config[pag]$url_idioma$url_busqueda$url_parent'><IMG SRC='img/eorden.gif' ALT='Ordenar' BORDER='0' TITLE='Ordenar'><BR>Ordenar</A></SMALL>";
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
		echo "><IMG SRC='".$config['menu_extra'][$k]['menu_extra_img']."' TITLE='".$config['menu_extra'][$k]['menu_extra_tit']."' BORDER='0'></A></SMALL>";
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
		echo "><IMG SRC='".$config['menu_extra_img']."' TITLE='".$config['menu_extra_tit']."' BORDER='0'></A></SMALL>";
		echo "</TD>";
	}	
}
?>
</TR>
</TABLE>
</TD>
<TD VALIGN="MIDDLE" ALIGN="CENTER" WIDTH="40%">
<?php
echo paginar($config['pag'], $cant_reg, $config['lineas'], "$PHP_SELF?accion={$config[accion]}&op=l$url_orden$url_busqueda$url_idioma$url_parent");
?>
</TD>
<TD VALIGN="MIDDLE" ALIGN="RIGHT" WIDTH="30%">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0"> 
<TR> 
<TD>
<FORM NAME="buscar" ACTION="<?php echo basename($PHP_SELF) ?>">
<INPUT TYPE="HIDDEN" NAME="op" VALUE='l'>
<?php
if($config['set_idioma']=="yes"){
    echo "<INPUT TYPE='HIDDEN' NAME='idioma' VALUE='$config[idioma]'>";
}
if ($config[parent]){
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"parent\" VALUE=\"$config[parent]\">\n";
}
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"orden\" VALUE=\"$config[orden]\">";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"accion\" VALUE=\"$config[accion]\">";
echo "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\">"; 
echo "<TR>";
echo "<TD>";
echo "<SELECT CLASS='select' NAME='buscarx' SIZE='1'>";
foreach($config['campos'][0] as $k => $v){
	if($v!="" && !preg_match("/binario/i",$k) && !preg_match("/state/i",$k)){
		if ($k==$config['buscarx']) {
			$seleccionado = "SELECTED";
		} else {
			$seleccionado = '';
		}        
		echo "<OPTION $seleccionado VALUE=\"$k\">".convert_str($v)."</OPTION>\n";
	}
}
echo "</SELECT>&nbsp;";
?>
<INPUT TYPE="TEXT" NAME="busca" CLASS="boxes" SIZE="14" VALUE="<?php echo $config['buscar'] ?>" align='middle'>
<INPUT TYPE="IMAGE" NAME="busqueda" SRC="img/search.gif" BORDER="0" ALT="Buscar" align='TOP' TITLE='Buscar'>
</TD></TR></TABLE>
</FORM>
</TD>
<TD VALIGN="MIDDLE" ALIGN="RIGHT"><A HREF="<?php echo basename($PHP_SELF)."?accion={$config[accion]}&op=l".$url_idioma.$url_parent ?>"><IMG SRC="img/list.gif" ALT="Restaurar" BORDER="0" TITLE='Restaurar'></A></TD>
</TR> 
</TABLE>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
