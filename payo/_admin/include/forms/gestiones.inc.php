<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"provincia[1]\", 4, \"" . convert_str("la Provincia.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_mensajes" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Gesti&oacute;n:</STRONG></TD> 
<TD><INPUT NAME='gestion[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['gestion'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='35' MAXLENGTH='40'></TD> 
</TR>
<TR> 
<TD><STRONG>Letra:</STRONG></TD> 
<TD><INPUT NAME='letra[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['letra'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='1' MAXLENGTH='1'></TD> 
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
form_hidden_fields($config, $formdata[1]->fields['id_gestion']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
