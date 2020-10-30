<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"email[1]\", 5, \"" . convert_str("el campo Email.",$default->encode)."\", \"mail\",'');\n";
echo "AddCampo(\"nombre[1]\", 5, \"" . convert_str("el campo Nombre.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_newsparam" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Email:</STRONG></TD>
<TD><INPUT NAME='email[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['email'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='250'></TD> 
</TR>
<TR> 																																										  
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Nombre:</STRONG></TD>
<TD><INPUT NAME='nombre[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['nombre'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='250'></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Cabecera:</STRONG></TD>
<TD><TEXTAREA NAME="head[1]" CLASS="editboxes" ROWS="9" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['head'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD> 
<TD VALIGN="TOP" ALIGN="LEFT"></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Plantilla:</STRONG></TD>
<TD><TEXTAREA NAME="plantilla[1]" CLASS="editboxes" ROWS="9" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['plantilla'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP" ALIGN="LEFT"><INPUT TYPE="BUTTON" NAME="ed_plantilla_1" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('plantilla[1]','','true');">
</TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Pie:</STRONG></TD>
<TD><TEXTAREA NAME="foot[1]" CLASS="editboxes" ROWS="4" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['foot'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD> 
<TD VALIGN="TOP" ALIGN="LEFT"></TD>
</TR>
</TABLE>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF'"; ?>"></TD>
<TD></TD>
</TR>
</TABLE>
<?php
form_hidden_fields($config, $formdata[1]->fields['id_newsparam']);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
