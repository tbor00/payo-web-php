<SCRIPT LANGUAGE="Javascript">
<?php echo "AddCampo(\"palabra[1]\", 2, \"" . convert_str("el campo Palabra.",$default->encode)."\", \"text\",'');\n"; ?>
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_mensajes" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<? echo $default->fieldrequired_color ?>">Palabra:</STRONG></TD> 
<TD><INPUT NAME='palabra[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['palabra'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='100'></TD> 
</TR>
</TABLE> 
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>
</TABLE>
<?php
form_hidden_fields($config, $formdata[1]->fields['id']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
