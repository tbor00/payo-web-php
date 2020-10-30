<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"lenguaje[1]\", 2, \"" . convert_str("el campo Lenguaje.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<SCRIPT LANGUAGE="Javascript">
//-----------------------------------------------------
//-----------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" ONSUBMIT="return Retorna(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Lenguaje:</STRONG></TD> 
<TD><INPUT TYPE="text" NAME="lenguaje[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE="<?php  echo $formdata[1]->fields['lenguaje']?>"></TD>
</TR>
<TR> 
<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;
<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
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
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
