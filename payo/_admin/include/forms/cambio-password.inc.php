<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"pass1\", 5, \"" . convert_str("Contraseña",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"pass2\", 5, \"" . convert_str("Repetir Contraseña",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" ONSUBMIT="return control_form_user(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR>
<TD><STRONG>Usuario:</STRONG></TD>
<TD><STRONG><?php echo $formdata[1]->fields['username'] ?></STRONG></TD>
</TR>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Nueva Contase&ntilde;a:</STRONG></TD>
<TD><INPUT TYPE="PASSWORD" CLASS="boxes"  NAME="pass1" MAXLENGTH="14"></TD>
</TR>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Repetir Contrase&ntilde;a:</STRONG></TD>
<TD><INPUT TYPE="PASSWORD" CLASS="boxes"  NAME="pass2" MAXLENGTH="14"></TD>
</TR>
</TABLE>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0"  WIDTH="100">
<TR>
<TD><INPUT CLASS='button' NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS='button' NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>
</TABLE>
<?php
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" VALUE=\"\">\n";
form_hidden_fields($config, $id, "cp");
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
