<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"marca[1]\", 1, \"" . convert_str("el campo Marca.",$default->encode)."\", \"select\",'');\n";
echo "AddCampo(\"fecha[1]\", 1, \"" . convert_str("Fecha de Actualización",$default->encode)."\", \"date\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_historial" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR>
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Marca:</STRONG></TD> 
<TD COLSPAN="2">
<SELECT NAME="marca[1]" SIZE="1" CLASS="selectboxes">
<OPTION VALUE="">-------------------</OPTION>
<?
$db=connect();
$db->debug=SDEBUG;
$query = "SELECT DISTINCT e_pprod_marcas.* FROM e_pprod_marcas,e_pproductos WHERE e_pproductos.marca = e_pprod_marcas.marca ORDER BY marca";
$marca_r=$db->Execute($query);
while (!$marca_r->EOF){
    if ($marca_r->fields['marca']==$formdata[1]->fields['marca']){
	echo "<OPTION VALUE=\"".$marca_r->fields['marca']."\" SELECTED>".$marca_r->fields['marca']."</OPTION>\n";
    }else{
	echo "<OPTION VALUE=\"".$marca_r->fields['marca']."\">".$marca_r->fields['marca']."</OPTION>\n";
    }
    $marca_r->MoveNext();
}
?>
</SELECT>
</TD>                                                                                    
</TR>

<TR>																																										  
<TD COLSPAN="2"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha de Actualizaci&oacute;n:</STRONG>&nbsp;
<INPUT NAME='fecha[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD>
<TD></TD>
</TR>
 
<TR> 
<TD VALIGN="TOP"><STRONG>Observaciones:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="observaciones[1]" CLASS="boxes" ROWS="6" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['observaciones'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
<TD ALIGN="LEFT" VALIGN="TOP"></TD>
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
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"chimagen\" VALUE=\"0\">\n";
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
