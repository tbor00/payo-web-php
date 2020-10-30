<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"email[1]\", 5, \"" . convert_str("el campo Email.",$default->encode)."\", \"mail\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_general" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Email:</STRONG>&nbsp;&nbsp;<INPUT NAME='email[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['email']; ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Email de Cotizaciones:</STRONG>&nbsp;&nbsp;<INPUT NAME='email_cotiza[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['email_cotiza']; ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Email de Cobros:</STRONG>&nbsp;&nbsp;<INPUT NAME='email_cobro[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['email_cobro']; ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD> 
</TR>
<TR>
<TD><BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR> 																																										  
<TD VALIGN="TOP"><STRONG>L&iacute;nea (1):</STRONG></TD>
<TD><INPUT NAME='pie_1[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['pie_1'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD>
</TR>
<TR>																																										  
<TD VALIGN="TOP"><STRONG>L&iacute;nea (2):</STRONG></TD>
<TD><INPUT NAME='pie_2[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['pie_2'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD>
</TR>
<TR>																																										  
<TD VALIGN="TOP"><STRONG>L&iacute;nea (3):</STRONG></TD>
<TD><INPUT NAME='pie_3[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['pie_3'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='90' MAXLENGTH='250'></TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>																																										  
<TD VALIGN="TOP"><STRONG>Data Fiscal:</STRONG>&nbsp;&nbsp;
<INPUT NAME='data_fiscal[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['data_fiscal'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='200' MAXLENGTH='500'></TD>
</TR>
<TR>																																										  
<TD VALIGN="TOP"><STRONG>Modo Mantenimiento:</STRONG>
<INPUT TYPE="CHECKBOX" NAME="mantenimiento[1]" VALUE="1" <?php echo ($formdata[1]->fields['mantenimiento']==1 ? " CHECKED" : "" ); ?>></TD>
<TD></TD>
</TR>
</TABLE> 
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF'"; ?>"></TD>
<TD></TD>
</TR>
</TABLE>
<?php
form_hidden_fields($config, $formdata[1]->fields['id_param']);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
