<?php
echo "<SCRIPT LANGUAGE=\"Javascript\">\n";
echo "AddCampo(\"titulo[1]\", 2, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
?>
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_adicionales" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR>
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR>  
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['titulo'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='60' MAXLENGTH='100'>
&nbsp;&nbsp;<STRONG>Ocultar t&iacute;tulo:</STRONG><INPUT TYPE="CHECKBOX" NAME="hidetitle[1]" VALUE='1' <?php if($formdata[1]->fields['hidetitle']==1){ echo "checked"; } ?>></TD> 
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto Superior:</STRONG></TD>
<TD><TEXTAREA NAME="texto_up[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_up'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_up" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_up[1]','','<?echo $config[seccion]?>');">
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
<TD><TEXTAREA NAME="texto_down[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto_down'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto_down" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto_down[1]','','<?echo $config[seccion]?>');">
</TD>
</TR>
<TR> 
<TD COLSPAN="3">
<STRONG>Todos:</STRONG><INPUT TYPE="RADIO" NAME="destino[1]" VALUE="0" <?php if($formdata[1]->fields['destino']==0){echo "CHECKED=\"CHECKED\"";} ?>>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<STRONG>S&oacute;lo P&uacute;blico:</STRONG><INPUT TYPE="RADIO" NAME="destino[1]" VALUE="1" <?php if($formdata[1]->fields['destino']==1){echo "CHECKED=\"CHECKED\"";} ?>>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <STRONG>S&oacute;lo Privado:</STRONG><INPUT TYPE="RADIO" NAME="destino[1]" VALUE="2" <?php if($formdata[1]->fields['destino']==2){echo "CHECKED=\"CHECKED\"";} ?>>
</TD>
</TR>
<TR> 
<TD COLSPAN="3"><STRONG>Activo:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
</TR>
</TABLE>
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
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"appfolder\" VALUE=\"$config[appfolder]\">\n";
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>

