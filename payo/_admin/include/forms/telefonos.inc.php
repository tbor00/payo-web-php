<?php
echo "<SCRIPT LANGUAGE=\"Javascript\">\n";
echo "AddCampo(\"nombre[1]\", 2, \"" . convert_str("el campo Nombre.",$default->encode)."\", \"text\",'');\n";
?>
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_telefonos" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Nombre:</STRONG></TD> 
<TD><INPUT NAME='nombre[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['nombre'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='60' MAXLENGTH='100'>
</TD> 
</TR>
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Tel&eacute;fono:</STRONG></TD> 
<TD><INPUT NAME='telefono[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['telefono'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='60' MAXLENGTH='100'>
</TD> 
</TR>
<TR>
<TD><STRONG>Incluye:</STRONG></TD>
<TD><SELECT NAME="id_seccion[1]" CLASS="select" SIZE="1">
<OPTION VALUE="1" <?php if ($formdata[1]->fields['id_seccion']==1){echo "SELECTED=\"SELECTED\"";} ?>>Ventas</OPTION>
<OPTION VALUE="2" <?php if ($formdata[1]->fields['id_seccion']==2){echo "SELECTED=\"SELECTED\"";} ?>>Administraci&oacute;n</OPTION>
</SELECT></TD>
<TD></TD>
</TR>

<TR> 
<TD COLSPAN="3"><STRONG>Whasapp:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="wapp[1]" VALUE="1" <?php if($formdata[1]->fields['wapp']==1){ echo "checked"; } ?>>
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
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>

