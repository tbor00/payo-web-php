<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"titulo[1]\", 3, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"contenido[1]\", 4, \"" . convert_str("el campo Contenido.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"titulo[2]\", 3, \"" . convert_str("el campo Título en Ingles.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_newsletter" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR>
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['titulo'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='100'></TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Contenido:</STRONG></TD>
<TD><TEXTAREA NAME="contenido[1]" CLASS="editboxes" ROWS="9" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['contenido'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP" ALIGN="LEFT"><INPUT TYPE="BUTTON" NAME="ed_contenido_1" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('contenido[1]','','true');">
<BR><INPUT CLASS="button" NAME="preview" VALUE="Vista Previa" TYPE="button" ONCLICK="javascript:PreviewHTML('titulo[1]','contenido[1]');"></TD>
</TR>
<TR> 
<TD COLSPAN="3"><STRONG>Activo:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
</TR>
</TABLE>
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
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='titulo[2]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[2]->fields['titulo'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='100'></TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Contenido:</STRONG></TD>
<TD><TEXTAREA NAME="contenido[2]" CLASS="editboxes" ROWS="9" COLS="80"><?php echo htmlspecialchars($formdata[2]->fields['contenido'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD> 
<TD VALIGN="TOP" ALIGN="LEFT"><INPUT TYPE="BUTTON" NAME="ed_contenido_2" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('contenido[2]','','true');">
<BR><INPUT CLASS="button" NAME="preview" VALUE="Vista Previa" TYPE="button" ONCLICK="javascript:PreviewHTML('titulo[2]','contenido[2]');"></TD>
</TR>
<TR> 
<TD COLSPAN="3"><STRONG>Activo:</STRONG>&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="activo[2]" VALUE="1" <?php if($formdata[2]->fields['activo']==1){ echo "checked"; } ?>></TD>
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
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
