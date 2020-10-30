<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_mensajes" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR> 
<TD><STRONG>Mensaje:</STRONG></TD> 
<TD STYLE="color:blue"><?php echo htmlspecialchars($formdata[1]->fields['seccion'],ENT_QUOTES,$default->encode); ?></TD> 
<TD></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Texto:</STRONG></TD>
<TD><TEXTAREA NAME="texto[1]" CLASS="editboxes" ROWS="9" COLS="75"><?php echo htmlspecialchars($formdata[1]->fields['texto'],ENT_QUOTES,$default->encode) ?></TEXTAREA></TD> 
<TD VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_texto" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('texto[1]','','<?echo $config[seccion]?>');">
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
</TD>
</TR>
</TABLE>
<?php
form_hidden_fields($config, $formdata[1]->fields['id_seccion']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
