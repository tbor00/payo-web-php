<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"titulo[1]\", 2, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
if ($op =='a'){
	$dosecc = $config[seccion];
} else {
	$dosecc = $formdata[1]->fields['seccion'];
}
if ($dosecc == 'oth'){ 
	$urlimg = "../other/imagenes/";
} else {
	$urlimg = "../imagenes/";
}
?>
<SCRIPT LANGUAGE="Javascript">
var campo="";
//-------------------------------------------------------------------
function Retorna(formulario){
	doSub('adics');
	doSub('adics_2');
	if (ChequeaForm(formulario)){
		return true;
	}
	return false;
}
//-----------------------------------------------------
function AddImage(fcampo) {
	campo = fcampo;
	var imgfolder = '<?php echo $urlimg?>';
	var url = 'brwimages.php?dest_fold=' + imgfolder;
	var imagePath = window.showModalDialog(url ,"","dialogHeight:340px;dialogWidth:480px;center:yes;help:no;scroll:yes;status:no;resizable:no ")
 	if ((imagePath != null) && (imagePath != "") && (imagePath != undefined)) {
		document.forms[0].elements['chimagen'].value = 1;
      document.forms[0].elements[campo].value = imagePath;
  	}
}
//-----------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_secciones" ONSUBMIT="return Retorna(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['titulo']; ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='100'>
&nbsp;&nbsp;<STRONG>Ocultar t&iacute;tulo:</STRONG><INPUT TYPE="CHECKBOX" NAME="hidetitle[1]" VALUE='1' <?php if($formdata[1]->fields['hidetitle']==1){ echo "checked"; } ?>></TD> 
<TD></TD>
</TR>
<?php
if ($formdata[1]->fields['tipo']==1){
?>
<TR> 
<TD><STRONG>Email:</STRONG></TD> 
<TD><INPUT NAME='contacto[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['contacto']; ?>" TYPE='TEXT' SIZE='70' MAXLENGTH='200'></TD> 
<TD></TD>
</TR>
<?php
}
?>
<TR>
<TD><STRONG>Imagen:&nbsp;</STRONG></TD> 
<TD VALIGN="MIDDLE" COLSPAN="2">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD><INPUT NAME='imgtitulo[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['imgtitulo']; ?>" TYPE='TEXT' SIZE='40' MAXLENGTH='60'></TD>
<TD>&nbsp;<INPUT TYPE="BUTTON" CLASS='button' NAME="Examinar" VALUE="Examinar" ONCLICK="AddImage('imgtitulo[1]');"></TD>
</TR> 
</TABLE></TD>
</TR> 
<TR>
<TD><STRONG>Ejecutar:</STRONG></TD>
<TD><INPUT TYPE="TEXT" CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['ejecutar']; ?>" NAME="ejecutar[1]" SIZE="50" MAXLENGTH="250"></TD>
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[1]->fields['texto_up'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_up" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_up[1]','','<?echo $dosecc?>');">
</TD>
</TR>
<TR>
<TD><STRONG>Incluye:</STRONG></TD>
<TD><SELECT NAME="inclusion[1]" CLASS="select" SIZE="1">
<OPTION VALUE="0"> - - - - - - - - - - - - - - - - </OPTION>
<?php
$db = connect();
$db->debug = SDEBUG;
$inc_q = "SELECT id_form,titulo FROM e_formulars";
if ($inc_res = $db->Execute($inc_q)){
	while( !$inc_res->EOF ){
		echo "<OPTION VALUE='".$inc_res->fields[id_form]."'";
 		if ($formdata[1]->fields['inclusion']==$inc_res->fields['id_form']) {
			echo ' SELECTED="SELECTED"';
		}
 		echo ">".$inc_res->fields[titulo]."</OPTION>";
		$inc_res->MoveNext();
	}
}
?>
</SELECT></TD>
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Inferior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_down[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[1]->fields['texto_down'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[1]','','<?echo $dosecc?>');">
</TD>
</TR>
<TR>
<TD COLSPAN="3">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0"> 
<TR> 
<TD WIDTH="250"ALIGN="CENTER"><B>Adicionales disponibles</B></TD>
<TD WIDTH="35" ALIGN="CENTER"></TD>   
<TD WIDTH="250"ALIGN="CENTER"><B>Adicionales seleccionados</B></TD> 
<TD></TD> 
</TR> 
<TR> 
<TD WIDTH="144" ROWSPAN="2"> 
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="adicd">
<?php
$clauseinf=0;
$query = "SELECT adicional_id FROM e_menu_adic WHERE lenguaje_id=1 AND menu_id=".$id;
if ($adicd = $db->Execute($query)){
	while( !$adicd->EOF ){
		if (strlen($clauseinf) > 0){
			$clauseinf = $clauseinf.",".$adicd->fields[adicional_id];
		} else {
			$clauseinf = $adicd->fields[adicional_id];
		}
		$adicd->MoveNext();
	}
}
if (strlen($clauseinf) > 0){
	$not_in = "e_adicionales.id_adicional NOT IN (".$clauseinf.") ";
	$if_in = "e_adicionales.id_adicional IN (".$clauseinf.") ";
	$query ="SELECT id_adicional,titulo FROM e_adicionales WHERE $not_in AND activo=1 AND lenguaje_id=1 ORDER BY titulo";
} else {
	$query = "SELECT id_adicional,titulo FROM e_adicionales WHERE activo=1 AND lenguaje_id=1 ORDER BY titulo";
}
if ($adicr = $db->Execute($query)){
	while( !$adicr->EOF ){
		echo "<OPTION VALUE=\"".$adicr->fields[id_adicional]."\">".$adicr->fields[titulo]."</OPTION>\n";
		$adicr->MoveNext();
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER">
<A HREF="javascript:AddMod('adics','adicd','adics');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13" BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('adicd','adics','adics');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13" BORDER="0" ALT="Excluir"></A>
</TD>
<TD>
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="adics">
<?php
if (strlen($if_in)>0) {
	$query = "SELECT id_adicional,titulo FROM e_adicionales INNER JOIN e_menu_adic ON e_menu_adic.adicional_id = e_adicionales.id_adicional
	WHERE $if_in AND activo=1 AND e_menu_adic.menu_id='$id' AND e_adicionales.lenguaje_id=1 AND e_menu_adic.lenguaje_id=1 ORDER BY posicion_adic";
	if ($adicselr = $db->Execute($query)){
		while( !$adicselr->EOF ){
			echo "<OPTION VALUE=\"".$adicselr->fields[id_adicional]."\">".$adicselr->fields[titulo]."</OPTION>\n";
			$adicselr->MoveNext();
		}
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'adics');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'adics');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
</TD>
</TR>
</TABLE>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;
<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
</TR>
</TABLE>
</TD></TR></TABLE>
</TD>
</TR>
<!-- 	-------------------------------------------------	INGLES 	-------------------------------------------------	-->
<TR>
<TD><BR><HR><BR> 
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_great_britain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Ingl&eacute;s</SPAN></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD><INPUT NAME='titulo[2]' CLASS="boxes" VALUE="<?php echo $formdata[2]->fields['titulo']; ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='100'>
&nbsp;&nbsp;<STRONG>Ocultar t&iacute;tulo:</STRONG><INPUT TYPE="CHECKBOX" NAME="hidetitle[2]" VALUE='1' <?php if($formdata[2]->fields['hidetitle']==1){ echo "checked"; } ?>></TD> 
<TD></TD>
</TR>
<?php
if ($formdata[1]->fields['tipo']==1){
?>
<TR> 
<TD><STRONG>Email:</STRONG></TD> 
<TD><INPUT NAME='contacto[1]' CLASS="boxes" VALUE="<?php echo $formdata[2]->fields['contacto']; ?>" TYPE='TEXT' SIZE='70' MAXLENGTH='200'></TD> 
<TD></TD>
</TR>
<?php
}
?>
<TR>
<TD><STRONG>Imagen:&nbsp;</STRONG></TD> 
<TD VALIGN="MIDDLE" COLSPAN="2">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD><INPUT NAME='imgtitulo[2]' CLASS="boxes" VALUE="<?php echo $formdata[2]->fields['imgtitulo']; ?>" TYPE='TEXT' SIZE='40' MAXLENGTH='60'></TD>
<TD>&nbsp;<INPUT TYPE="BUTTON" CLASS='button' NAME="Examinar" VALUE="Examinar" ONCLICK="AddImage('imgtitulo[2]');"></TD>
</TR> 
</TABLE></TD>
</TR> 
<TR>
<TD><STRONG>Ejecutar:</STRONG></TD>
<TD><INPUT TYPE="TEXT" CLASS="boxes" VALUE="<?php echo $formdata[2]->fields['ejecutar']; ?>" NAME="ejecutar[2]" SIZE="50" MAXLENGTH="250"></TD>
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[2]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[2]->fields['texto_up'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_up" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_up[2]','','<?echo $dosecc?>');">
</TD>
</TR>
<TR>
<TD><STRONG>Incluye:</STRONG></TD>
<TD><SELECT NAME="inclusion[2]" CLASS="select" SIZE="1">
<OPTION VALUE="0"> - - - - - - - - - - - - - - - - </OPTION>
<?php
if ($inc_res){
	$inc_res->MoveFirst();
	while(!$inc_res->EOF){
		echo "<OPTION VALUE='".$inc_res->fields[id_form]."'";
 		if ($formdata[2]->fields['inclusion']==$inc_res->fields['id_form']) {
			echo ' SELECTED="SELECTED"';
		}
 		echo ">".$inc_res->fields[titulo]."</OPTION>";
		$inc_res->MoveNext();
	}
}
?>
</SELECT></TD>
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Inferior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_down[2]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[2]->fields['texto_down'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[2]','','<?echo $dosecc?>');">
</TD>
</TR>
<TR>
<TD COLSPAN="3">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0"> 
<TR> 
<TD WIDTH="250"ALIGN="CENTER"><B>Adicionales disponibles</B></TD>
<TD WIDTH="35" ALIGN="CENTER"></TD>   
<TD WIDTH="250"ALIGN="CENTER"><B>Adicionales seleccionados</B></TD> 
<TD></TD> 
</TR> 
<TR> 
<TD WIDTH="144" ROWSPAN="2"> 
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="adicd_2">
<?php
$clauseinf=0;
$query = "SELECT adicional_id FROM e_menu_adic WHERE lenguaje_id=2 AND menu_id=".$id;
if ($adicd = $db->Execute($query)){
	while( !$adicd->EOF ){
		if (strlen($clauseinf) > 0){
			$clauseinf = $clauseinf.",".$adicd->fields[adicional_id];
		} else {
			$clauseinf = $adicd->fields[adicional_id];
		}
		$adicd->MoveNext();
	}
}
if (strlen($clauseinf) > 0){
	$not_in = "e_adicionales.id_adicional NOT IN (".$clauseinf.") ";
	$if_in = "e_adicionales.id_adicional IN (".$clauseinf.") ";
	$query ="SELECT id_adicional,titulo FROM e_adicionales WHERE $not_in AND activo=1 AND lenguaje_id=2 ORDER BY titulo";
} else {
	$query = "SELECT id_adicional,titulo FROM e_adicionales WHERE activo=1 AND lenguaje_id=2 ORDER BY titulo";
}
if ($adicr = $db->Execute($query)){
	while( !$adicr->EOF ){
		echo "<OPTION VALUE=\"".$adicr->fields[id_adicional]."\">".$adicr->fields[titulo]."</OPTION>\n";
		$adicr->MoveNext();
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER">
<A HREF="javascript:AddMod('adics_2','adicd_2','adics_2');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13" BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('adicd_2','adics_2','adics_2');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13" BORDER="0" ALT="Excluir"></A>
</TD>
<TD>
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="adics_2">
<?php
if (strlen($if_in)>0) {
	$query = "SELECT id_adicional,titulo FROM e_adicionales INNER JOIN e_menu_adic ON e_menu_adic.adicional_id = e_adicionales.id_adicional
	WHERE $if_in AND activo=1 AND e_menu_adic.menu_id='$id' AND e_adicionales.lenguaje_id=2 AND e_menu_adic.lenguaje_id=2 ORDER BY posicion_adic";
	if ($adicselr = $db->Execute($query)){
		while( !$adicselr->EOF ){
			echo "<OPTION VALUE=\"".$adicselr->fields[id_adicional]."\">".$adicselr->fields[titulo]."</OPTION>\n";
			$adicselr->MoveNext();
		}
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'adics_2');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'adics_2');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
</TD>
</TR>
</TABLE>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;
<INPUT TYPE="CHECKBOX" NAME="activo[2]" VALUE="1" <?php if($formdata[2]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
</TR>
</TABLE>
</TD></TR></TABLE>
</TD>
</TR>
<TR>
<TD>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>
</TABLE>
</TD></TR></TABLE>
<?php
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"adics_lst\" VALUE=\"\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"adics_2_lst\" VALUE=\"\">\n";
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
