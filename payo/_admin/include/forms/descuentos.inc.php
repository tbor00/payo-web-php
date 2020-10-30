<?php
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript1.2\" SRC=\"jscripts/".strtolower($default->encode)."/hash.js\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"leyenda[1]\", 1, \"" . convert_str("el campo Descuento.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<SCRIPT>
var campo="";
//-----------------------------------------------------
function AddImage(fcampo) {
	campo = fcampo;
	var imgfolder = '../descuentos/imagenes/';
	var url = 'brwimages.php?dest_fold=' + imgfolder;
	var imagePath = window.showModalDialog(url ,"","dialogHeight:340px;dialogWidth:480px;center:yes;help:no;scroll:yes;status:no;resizable:no ")
 	if ((imagePath != null) && (imagePath != "") && (imagePath != undefined)) {
		document.forms[0].elements['chimagen'].value = 1;
      document.forms[0].elements[campo].value = imagePath;
  	}
}
//-----------------------------------------------------

</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_rubrosp" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Descuento:</STRONG></TD> 
<TD><INPUT NAME='leyenda[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['leyenda']; ?>" TYPE='TEXT' SIZE='50' MAXLENGTH='50'></TD> 
</TR>
<TR> 
<TD><STRONG>Porcentaje:</STRONG></TD> 
<TD><INPUT NAME='porcentaje[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['porcentaje']; ?>" TYPE='TEXT' SIZE='10' MAXLENGTH='2'></TD> 
</TR>

<TR> 
<TD><STRONG>Tarjeta:</STRONG></TD> 
<TD><INPUT TYPE="CHECKBOX" NAME="tarjeta[1]" VALUE="1" <?php if($formdata[1]->fields['tarjeta']==1){ echo "checked"; } ?>></TD> 
</TR>

<TR> 
<TD><STRONG>Cuotas:</STRONG></TD> 
<TD><INPUT NAME='cuotas[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['cuotas']; ?>" TYPE='TEXT' SIZE='5' MAXLENGTH='2'></TD> 
</TR>

<TD><STRONG>Imagen:</STRONG></TD> 
<TD>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD><INPUT NAME='imagen[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['imagen']; ?>" TYPE='TEXT' SIZE='40' MAXLENGTH='60'></TD>
<TD>&nbsp;<INPUT TYPE="BUTTON" CLASS='button' NAME="Examinar" VALUE="Examinar" ONCLICK="AddImage('imagen[1]');"></TD>
</TR>
</TABLE>
</TD>
</TR>

<TR> 
<TD VALIGN="TOP"><STRONG>Informaci&oacute;n:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="informacion[1]" CLASS="boxes" ROWS="10" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['informacion'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
</TR>


<TR>
<TD><STRONG>Tipo:</STRONG></TD>
<TD>
<SELECT NAME="nivel[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0" <?php if ($formdata[1]->fields['nivel']==0){echo "SELECTED=\"SELECTED\"";} ?>>Minorista</OPTION>
<OPTION VALUE="1" <?php if ($formdata[1]->fields['nivel']==1){echo "SELECTED=\"SELECTED\"";} ?>>Distribuidor</OPTION>
</SELECT>
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
form_hidden_fields($config, $formdata[1]->fields['id_descuento']);
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"chimagen\" VALUE=\"0\">\n";
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
