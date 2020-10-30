<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_marcas" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG>Proveedor:</STRONG></TD> 
<TD><STRONG><?php echo htmlspecialchars($formdata[1]->fields['proveedor'],ENT_QUOTES,$default->encode); ?></STRONG></TD> 
</TR>
<TR>
<TD><STRONG>Tipo:</STRONG></TD>
<TD>
<SELECT NAME="nivel[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0" <?php if ($formdata[1]->fields['nivel']==0){echo "SELECTED=\"SELECTED\"";} ?>>Ambos</OPTION>
<OPTION VALUE="1" <?php if ($formdata[1]->fields['nivel']==1){echo "SELECTED=\"SELECTED\"";} ?>>Minorista</OPTION>
<OPTION VALUE="2" <?php if ($formdata[1]->fields['nivel']==2){echo "SELECTED=\"SELECTED\"";} ?>>Distribuidor</OPTION>
</SELECT>
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
form_hidden_fields($config, $formdata[1]->fields['id_proveedor']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
