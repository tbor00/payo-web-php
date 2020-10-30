<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\" SRC=\"jscripts/".strtolower($default->encode)."/hash.js\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
if ($config[op]=='cp'){ 
	echo "AddCampo(\"pass0\", 5, \"" . convert_str("la Contraseña Anterior.",$default->encode)."\", \"text\",'');\n";
	echo "AddCampo(\"pass1\", 5, \"" . convert_str("la Nueva Contraseña",$default->encode)."\", \"text\",'');\n";
	echo "AddCampo(\"pass2\", 5, \"" . convert_str("la verificación de la Contraseña.",$default->encode)."\", \"text\",'');\n";
	$form_return="return control_passwd_form_user(this);";
} else {
	$form_return="return true;";
}
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" ONSUBMIT="<?php echo $form_return ?>">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG>Usuario:</STRONG></TD>
<TD><STRONG><?php echo $formdata[1]->fields['username'] ?></STRONG>
<?php
if ($config[op]!="cp"){
	echo "&nbsp;&nbsp&nbsp;[ <SMALL><A HREF=\"$PHP_SELF?accion=misdatos&id=$id&op=p\">Cambiar contrase&ntilde;a</A></SMALL> ]";
}
?>
</TD> 
</TR>
<?php
if ($config[op]!="cp"){
?>
<TR>
<TD><STRONG>Descripci&oacute;n:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="descripcion[1]" SIZE="50" MAXLENGTH="120" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['descripcion']; ?>'></TD>
</TR>
<TR>
<TD><STRONG>Email:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="email[1]" SIZE="60" MAXLENGTH="120" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['email']?>'></TD>
</TR>
<?php 
} else { 
?>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Contase&ntilde;a Anterior:</STRONG></TD>
<TD><INPUT TYPE="PASSWORD" CLASS="boxes"  NAME="pass0" MAXLENGTH="14"></TD>
</TR>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Nueva Contase&ntilde;a:</STRONG></TD>
<TD><INPUT TYPE="PASSWORD" CLASS="boxes"  NAME="pass1" MAXLENGTH="14"></TD>
</TR>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Repetir Contrase&ntilde;a:</STRONG></TD>
<TD><INPUT TYPE="PASSWORD" CLASS="boxes"  NAME="pass2" MAXLENGTH="14"></TD>
</TR>
<?php
}
?>
</TABLE>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>            
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF'" ?>"></TD>
</TR>           
</TABLE>
<?php
if ($config[op]=='cp'){ 
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"oldpassword\" VALUE=\"".md5($formdata[1]->fields['password'])."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" VALUE=\"\">\n";
}
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>


