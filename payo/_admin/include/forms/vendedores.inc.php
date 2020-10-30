<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"idv[1]\", 1, \"" . convert_str("el campo Codigo.",$default->encode)."\", \"num\",'');\n";
echo "AddCampo(\"vendedor[1]\", 4, \"" . convert_str("El nombre.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"email[1]\", 4, \"" . convert_str("El Mail.",$default->encode)."\", \"mail\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_vendedores" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">C&oacute;digo EBASE:</STRONG></TD> 
<TD><INPUT NAME='id_vendedor[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['id_vendedor'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='10' MAXLENGTH='5'></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Nombre:</STRONG></TD> 
<TD><INPUT NAME='vendedor[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['vendedor'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='250'></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Email:</STRONG></TD> 
<TD><INPUT NAME='email[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['email'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='60' MAXLENGTH='250'></TD> 
</TR>
<TR> 
<TD><STRONG>Usuario:</STRONG></TD> 
<TD><INPUT TYPE="text" NAME="username[1]" SIZE="10" MAXLENGTH="10" CLASS="boxes" VALUE="<?php  echo $formdata[1]->fields['username']?>"></TD> 
</TR>
<TR> 
<TD><STRONG>Admin:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="CHECKBOX" NAME="adm[1]" VALUE="1" <?php if($formdata[1]->fields['adm']==1){ echo "checked"; } ?>>
</TD>
</TR>
<TR> 
<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
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
form_hidden_fields($config, $formdata[1]->fields['id_v']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
