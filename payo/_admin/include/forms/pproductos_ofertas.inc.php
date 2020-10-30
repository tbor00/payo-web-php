<?php
$db=connect();
$db->debug = SDEBUG;
if ($op=="om"){
	$query = "SELECT e_ofertas.* from e_ofertas where cod_prod='$id'";
	$formdata[1] = $db->Execute($query);
	echo "<SCRIPT>";
	echo "function Eliminar_Oferta(formu){";
	echo "var is_confirmed = confirm('Esta seguro que desea eliminar\\nla oferta seleccionada ?');";
	echo "if (is_confirmed) {";
	echo "formu.op.value='oce';";
	echo "formu.submit();";
	echo "}";
	echo "}";
	echo "</SCRIPT>\n";
}
$query = "SELECT descripcion from e_pproductos where codigo='$id'";
$presultado = $db->Execute($query);
if ($presultado && !$presultado->EOF){
	$descripcion=$presultado->fields[descripcion];
} 
//---------------------------------------------------------------------------
echo "<SCRIPT>\n";
echo "AddCampo(\"cod_prod[1]\", 4, \"" . convert_str("el Código.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"fecha_alta[1]\", 4, \"" . convert_str("en Fecha de Publicación.",$default->encode)."\", \"date\",'');\n";
echo "AddCampo(\"fecha_baja[1]\", 4, \"" . convert_str("en Fecha de Caducidad.",$default->encode)."\", \"date\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" AUTOCOMPLETE="off" METHOD="POST" NAME="form_ofertas" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">C&oacute;digo:</STRONG></TD> 
<TD><STRONG><?php echo $id ?></STRONG>
&nbsp;&nbsp;<SPAN ID="cod_prod_desc" STYLE="color:<?php echo $default->fieldrequired_color ?>"><?php  echo $descripcion;?></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php 
if ($op=="om"){
	echo "<INPUT CLASS=\"button\" NAME=\"eliminar\" VALUE=\"Eliminar\" TYPE=\"button\" ONCLICK=\"Eliminar_Oferta(this.form)\">\n";
}
?>
</TD>
</TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Descripci&oacute;n:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="oferta[1]" CLASS="boxes" ROWS="6" COLS="60"><?php echo htmlspecialchars($formdata[1]->fields['oferta'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha Publicaci&oacute;n:</STRONG></TD> 
<TD><INPUT NAME='fecha_alta[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_alta']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_alta[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha Caducidad:</STRONG></TD> 
<TD><INPUT NAME='fecha_baja[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_baja']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_baja[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD> 
</TR>
<TR>
<TD><STRONG>Tipo:</STRONG></TD>
<TD>
<SELECT NAME="destino[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0" <?php if ($formdata[1]->fields['destino']==0){echo "SELECTED=\"SELECTED\"";} ?>>Todos</OPTION>
<OPTION VALUE="1" <?php if ($formdata[1]->fields['destino']==1){echo "SELECTED=\"SELECTED\"";} ?>>Minoristas</OPTION>
<OPTION VALUE="2" <?php if ($formdata[1]->fields['destino']==2){echo "SELECTED=\"SELECTED\"";} ?>>Distribuidores</OPTION>
</SELECT>
</TD>
</TR>

</TABLE> 
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="aceptar" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>
</TABLE>
<INPUT TYPE="HIDDEN" NAME="cod_prod[1]" VALUE="<?php echo $id ?>">
<?php
form_hidden_fields($config, $formdata[1]->fields['cod_prod']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
