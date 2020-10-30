<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"titulo[1]\", 2, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
$urlimg = "../img/";
?>
<SCRIPT LANGUAGE="Javascript">
//-------------------------------------------------------------------
function expandIT(whichEl) { 
 if (whichEl == "2") {
		MPI.style.display = "block";
	} else {
		MPI.style.display = "none";
	}
}
//------------------------------------------------------------------------------
function Retorna(formulario){
	//doSub('adics');
	doSub('forms');
	if (ChequeaForm(formulario)){
		return true;
	}
	return false;
}
//-----------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_menues" ONSUBMIT="return Retorna(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR>  
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['titulo']); ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='100'>
</TD> 
<TD></TD>
</TR>
<!-- INICIO MENUES -->
<?php 
if($op=='a'){
  $formdata[1]->fields['tipo']=$config[menu_tipo];
}
if ($formdata[1]->fields['tipo']!=0 && $config[submenu]){
	echo "<TR>\n";
	echo "<TD><STRONG>Tipo:</STRONG></TD>\n";
	echo "<TD><SELECT NAME=\"tipo\" SIZE=\"1\" ONCHANGE=\"expandIT(this.value);\" CLASS=\"editselectboxes\">\n";
	echo "<OPTION VALUE=\"1\"";
	if ($formdata[1]->fields['tipo']==1) {
		echo " SELECTED=\"SELECTED\"";
	}
	echo ">MENU</OPTION>\n";
	echo "<OPTION VALUE=\"2\"";
	if ($formdata[1]->fields['tipo']==2) { 
		echo " SELECTED=\"SELECTED\"";
	}
	echo ">SUBMENU</OPTION>\n";
	echo "</SELECT></TD>\n";
	echo "<TD></TD>\n";
	echo "</TR>\n";
	if ($formdata[1]->fields['tipo']==1 ){
		$mpidisplay = "display = none";
	} elseif($result->fields['tipo']==2) {
		$mpidisplay = "display = block";
	}
	echo "<TR><TD COLSPAN=\"3\">\n";
	echo "<DIV STYLE=\"$mpidisplay\" ID=\"MPI\">\n";
	echo "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
	echo "<TR>";
	echo "<TD><STRONG>Menu:&nbsp;</STRONG>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</TD>";
	echo "<TD><SELECT NAME=\"menu_id\" SIZE=\"1\" CLASS=\"editselectboxes\">\n";
	$menudb = connect();
	$menudb->debug = SDEBUG;
	if (isset($formdata[1]->fields['menu_id'])){
		$menuq = "select id_menu,titulo from menues where (tipo=1) AND lenguaje_id=1 AND id_menu<>".$formdata[1]->fields['id_menu'];
	} else {
		$menuq = "select id_menu,titulo from menues where (tipo=1) AND lenguaje_id=1";
	}
	if ($menur = $menudb->Execute($menuq)){
		while( !$menur->EOF ){
			echo "<OPTION VALUE='".$menur->fields[id_menu]."'";
	 		if ($formdata[1]->fields['menu_id']==$menur->fields['id_menu']) {
				echo ' SELECTED="SELECTED"';
			}
	 		echo ">".$menur->fields[titulo]."</OPTION>\n";
			$menur->MoveNext();
		}
	}
	echo "</SELECT></TD>\n";
	echo "<TD></TD>";
	echo "</TR>";
	echo "</TABLE>";
	echo "</DIV>";

	echo "</TD></TR>\n";
} else {
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"tipo\" VALUE=\"".$formdata[1]->fields['tipo']."\">\n";

}
?>
<!-- FIN MENUES -->
<!--
<TR>
<TD><STRONG>Ejecutar:</STRONG></TD>
<TD><INPUT TYPE="TEXT" CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['ejecutar'],ENT_QUOTES,$default->encode); ?>" NAME="ejecutar[1]" SIZE="50" MAXLENGTH="250"></TD>
<TD></TD>
</TR>
-->
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_up'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
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
<TD><TEXTAREA NAME="texto_down[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_down'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[1]','','<?echo $config[seccion]?>');">
</TD>
</TR>
<TR>
<TD COLSPAN="3">
<?php
if ($formdata[1]->fields['tipo']!=0){

	echo "<TABLE CELLPADDING=\"1\" CELLSPACING=\"1\" BORDER=\"0\"><TR><TD>";
	echo "<STRONG>P&uacute;blico:</STRONG><INPUT TYPE=\"RADIO\" NAME=\"destino\" VALUE=\"0\"".($formdata[1]->fields['destino']==0 ? " CHECKED=\"CHECKED\"" : "").">";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<STRONG>Privado:</STRONG><INPUT TYPE=\"RADIO\" NAME=\"destino\" VALUE=\"1\"".($formdata[1]->fields['destino']==1 ? " CHECKED=\"CHECKED\"" : "").">";
	//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<STRONG>S&oacute;lo Privado:</STRONG><INPUT TYPE=\"RADIO\" NAME=\"destino\" VALUE=\"2\"".($formdata[1]->fields['destino']==2 ? " CHECKED=\"CHECKED\"" : "").">";
	echo "</TD></TR></TABLE>\n";
	echo "<TABLE CELLPADDING=\"1\" CELLSPACING=\"1\" BORDER=\"0\"><TR>";
	echo "<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;";
	echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"activo[1]\" VALUE=\"1\"".($formdata[1]->fields['activo']==1 ? " CHECKED" : "" )."></TD></TR></TABLE>\n";

} else {
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"activo[1]\" VALUE=\"1\"";
}
?>
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
//echo "<INPUT TYPE=\"HIDDEN\" NAME=\"destino\" VALUE=\"0\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"adics_lst\" VALUE=\"\">\n";
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"forms_lst\" VALUE=\"\">\n";
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
