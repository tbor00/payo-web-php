<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\" SRC=\"jscripts/".strtolower($default->encode)."/hash.js\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"username[1]\", 4, \"" . convert_str("el campo Usuario.",$default->encode)."\", \"text\",'');\n";
if ($op=='a'){ 
	echo "AddCampo(\"pass1\", 5, \"" . convert_str("la Contraseña.",$default->encode)."\", \"text\",'');\n";
	echo "AddCampo(\"pass2\", 5, \"" . convert_str("la verificación de la Contraseña.",$default->encode)."\", \"text\",'');\n";
	$form_return="return control_form_user(this);";
} else {
	$form_return="return ChequeaForm(this);";
}
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" ONSUBMIT="<?php echo $form_return ?>">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Usuario:</STRONG></TD> 
<TD><INPUT TYPE="text" NAME="username[1]" SIZE="10" MAXLENGTH="10" CLASS="boxes" VALUE="<?php  echo $formdata[1]->fields['username']?>"></TD> 
</TR>
<?php
if ($op=='a'){ 
	echo "<TR>\n"; 
	echo "<TD><STRONG STYLE=\"color: $default->fieldrequired_color;\">Contrase&ntilde;a:</STRONG></TD>\n";
	echo "<TD><INPUT TYPE=\"password\" NAME=\"pass1\" SIZE=\"14\" MAXLENGTH=\"14\" CLASS=\"boxes\" VALUE=\"\"></TD>\n"; 
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD><STRONG STYLE=\"color: $default->fieldrequired_color;\">Repetir Contrase&ntilde;a:</STRONG></TD>\n";
	echo "<TD><INPUT TYPE=\"password\" NAME=\"pass2\" SIZE=\"14\" MAXLENGTH=\"14\" CLASS=\"boxes\" VALUE=\"\"></TD>\n";
	echo "</TR>\n";
}
?>
<TR>
<TD><STRONG>Descripci&oacute;n:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="descripcion[1]" SIZE="50" MAXLENGTH="120" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['descripcion']; ?>'></TD>
</TR>
<TR>
<TD><STRONG>Email:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="email[1]" SIZE="60" MAXLENGTH="120" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['email']?>'></TD>
</TR>
<!--
<TR>
<TD VALIGN="MIDDLE"><STRONG>Administrador?</STRONG></TD>
<TD><SELECT NAME="administrad[1]" SIZE="1" CLASS="select">
<OPTION VALUE="0" <?php if ($formdata[1]->fields['administrador']==0) echo 'SELECTED'?>>No</OPTION>
<OPTION VALUE="1" <?php if ($formdata[1]->fields['administrador']==1) echo 'SELECTED'?>>Si</OPTION>
</SELECT></TD>
</TR>
-->
</TABLE>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>            
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>           
</TABLE>        
<?php
if ($op=='a'){ 
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" VALUE=\"".$formdata[1]->fields['password']."\">\n";
}
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
