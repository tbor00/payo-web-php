<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"name_id[1]\", 2, \"" . convert_str("el campo Identificador.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"titulo[1]\", 2, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"titulo[2]\", 2, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<SCRIPT LANGUAGE="Javascript">
//-------------------------------------------------------------------
function Retorna(formulario){
	doSub('adics');
	doSub('adics_2');
	doSub('forms');
	doSub('forms_2');
	if (ChequeaForm(formulario)){
		return true;
	}
	return false;
}
//-------------------------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_otherpage" ONSUBMIT="return Retorna(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<?php 
if ($op == 'a'){
	$style = "CLASS=\"boxes\"";
} else {
	$style = "CLASS=\"box_readonly\" READONLY";
}
?>
<TR>
<TD COLSPAN="3"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Identificador:</STRONG>&nbsp;&nbsp;
<INPUT NAME="name_id[1]" <?php echo $style ?> VALUE="<?php echo $formdata[1]->fields['name_id']; ?>"  TYPE="TEXT" SIZE="12" MAXLENGTH="10"></TD>
</TR>
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR>  
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['titulo'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='100'>
&nbsp;&nbsp;<STRONG>Ocultar t&iacute;tulo:</STRONG><INPUT TYPE="CHECKBOX" NAME="hidetitle[1]" VALUE='1' <?php if($formdata[1]->fields['hidetitle']==1){ echo "checked"; } ?>></TD> 
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_up'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_up" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_up[1]','','<?echo $config[seccion]?>');">
</TD>
</TR>
<TR>
<TD COLSPAN="3">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0"> 
<TR> 
<TD WIDTH="250"ALIGN="CENTER"><B>Formularios disponibles</B></TD>
<TD WIDTH="35" ALIGN="CENTER"></TD>   
<TD WIDTH="250"ALIGN="CENTER"><B>Formularios seleccionados</B></TD> 
<TD></TD> 
</TR> 
<TR> 
<TD WIDTH="144" ROWSPAN="2"> 
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="formd">
<?php
$db = connect();
$db->debug = SDEBUG;
$clauseinf=0;
if ($op != 'a'){
	$query = "SELECT form_id FROM e_menu_form WHERE lenguaje_id=1 AND menu_id=".$id;
	if ($formd = $db->Execute($query)){
		while( !$formd->EOF ){
			if (strlen($clauseinf) > 0){
				$clauseinf = $clauseinf.",".$formd->fields[form_id];
			} else {
				$clauseinf = $formd->fields[form_id];
			}
			$formd->MoveNext();
		}
	}
}
if (strlen($clauseinf) > 0){
	$not_in = "e_formulars.id_form NOT IN (".$clauseinf.") ";
	$if_in = "e_formulars.id_form IN (".$clauseinf.") ";
	$query ="SELECT id_form,titulo FROM e_formulars WHERE $not_in ORDER BY titulo";
} else {
	$query = "SELECT id_form,titulo FROM e_formulars ORDER BY titulo";
}
if ($formr = $db->Execute($query)){
	while( !$formr->EOF ){
		echo "<OPTION VALUE=\"".$formr->fields[id_form]."\">".$formr->fields[titulo]."</OPTION>\n";
		$formr->MoveNext();
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER">
<A HREF="javascript:AddMod('forms','formd','forms');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('formd','forms','forms');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Excluir"></A>
</TD>
<TD>
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="forms">
<?php
if (strlen($if_in)>0) {
	$query = "SELECT id_form,titulo FROM e_formulars INNER JOIN e_menu_form ON e_menu_form.form_id = e_formulars.id_form
	WHERE $if_in AND e_menu_form.menu_id='$id' AND e_menu_form.lenguaje_id=1 ORDER BY posicion_form";
	if ($formselr = $db->Execute($query)){
		while( !$formselr->EOF ){
			echo "<OPTION VALUE=\"".$formselr->fields[id_form]."\">".$formselr->fields[titulo]."</OPTION>\n";
			$formselr->MoveNext();
		}
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'forms');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'forms');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Inferior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_down[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_down'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[1]','','<?echo $config[seccion]?>');">
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
if ($op != 'a'){
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
}
if (strlen($clauseinf) > 0){
	$not_in = "e_adicionales.id_adicional NOT IN (".$clauseinf.") ";
	$if_in = "e_adicionales.id_adicional IN (".$clauseinf.") ";
	$query ="SELECT id_adicional,titulo FROM e_adicionales WHERE $not_in AND lenguaje_id=1 AND activo=1 ORDER BY titulo";
} else {
	$query = "SELECT id_adicional,titulo FROM e_adicionales WHERE lenguaje_id=1 AND activo=1 ORDER BY titulo";
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
<A HREF="javascript:AddMod('adics','adicd','adics');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('adicd','adics','adics');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Excluir"></A>
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
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[2]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[2]->fields['texto_up'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_up" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_up[2]','','<?echo $config[seccion]?>');">
</TD>
</TR>
<TR>
<TD COLSPAN="3">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0"> 
<TR> 
<TD WIDTH="250"ALIGN="CENTER"><B>Formularios disponibles</B></TD>
<TD WIDTH="35" ALIGN="CENTER"></TD>   
<TD WIDTH="250"ALIGN="CENTER"><B>Formularios seleccionados</B></TD> 
<TD></TD> 
</TR> 
<TR> 
<TD WIDTH="144" ROWSPAN="2"> 
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="formd_2">
<?php
$clauseinf=0;
if ($op != 'a'){
	$query = "SELECT form_id FROM e_menu_form WHERE lenguaje_id=2 AND menu_id=".$id;
	if ($formd = $db->Execute($query)){
		while( !$formd->EOF ){
			if (strlen($clauseinf) > 0){
				$clauseinf = $clauseinf.",".$formd->fields[form_id];
			} else {
				$clauseinf = $formd->fields[form_id];
			}
			$formd->MoveNext();
		}
	}
}
if (strlen($clauseinf) > 0){
	$not_in = "e_formulars.id_form NOT IN (".$clauseinf.") ";
	$if_in = "e_formulars.id_form IN (".$clauseinf.") ";
	$query ="SELECT id_form,titulo FROM e_formulars WHERE $not_in ORDER BY titulo";
} else {
	$query = "SELECT id_form,titulo FROM e_formulars ORDER BY titulo";
}
if ($formr = $db->Execute($query)){
	while( !$formr->EOF ){
		echo "<OPTION VALUE=\"".$formr->fields[id_form]."\">".$formr->fields[titulo]."</OPTION>\n";
		$formr->MoveNext();
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER">
<A HREF="javascript:AddMod('forms_2','formd_2','forms_2');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('formd_2','forms_2','forms_2');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Excluir"></A>
</TD>
<TD>
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="forms_2">
<?php
if (strlen($if_in)>0) {
	$query = "SELECT id_form,titulo FROM e_formulars INNER JOIN e_menu_form ON e_menu_form.form_id = e_formulars.id_form
	WHERE $if_in AND e_menu_form.menu_id='$id' AND e_menu_form.lenguaje_id=2 ORDER BY posicion_form";
	if ($formselr = $db->Execute($query)){
		while( !$formselr->EOF ){
			echo "<OPTION VALUE=\"".$formselr->fields[id_form]."\">".$formselr->fields[titulo]."</OPTION>\n";
			$formselr->MoveNext();
		}
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'forms_2');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'forms_2');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Inferior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_down[2]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo $formdata[2]->fields['texto_down'] ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[2]','','<?echo $config[seccion]?>');">
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
if ($op != 'a'){
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
}
if (strlen($clauseinf) > 0){
	$not_in = "e_adicionales.id_adicional NOT IN (".$clauseinf.") ";
	$if_in = "e_adicionales.id_adicional IN (".$clauseinf.") ";
	$query ="SELECT id_adicional,titulo FROM e_adicionales WHERE $not_in AND lenguaje_id=2 AND activo=1 ORDER BY titulo";
} else {
	$query = "SELECT id_adicional,titulo FROM e_adicionales WHERE lenguaje_id=2 AND activo=1 ORDER BY titulo";
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
<A HREF="javascript:AddMod('adics_2','adicd_2','adics_2');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('adicd_2','adics_2','adics_2');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Excluir"></A>
</TD>
<TD>
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 250px" SIZE="7" NAME="adics_2">
<?php
if (strlen($if_in)>0) {
	$query = "SELECT id_adicional,titulo FROM e_adicionales INNER JOIN e-menu_adic ON e_menu_adic.adicional_id = e-adicionales.id_adicional
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
</TD>
</TR>
</TABLE>
</TD></TR></TABLE>
<?php
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"adics_lst\" VALUE=\"\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"adics_2_lst\" VALUE=\"\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"forms_lst\" VALUE=\"\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"forms_2_lst\" VALUE=\"\">\n";
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
