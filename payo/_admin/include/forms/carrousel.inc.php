<?php
echo "<SCRIPT LANGUAGE=\"Javascript\">\n";
echo "AddCampo(\"titulo[1]\", 3, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<SCRIPT LANGUAGE="Javascript">
var campo="";
//-----------------------------------------------------
function AddImage(fcampo) {
	campo = fcampo;
	var imgfolder = '../carrousel/imagenes/';
	var url = 'brwimages.php?dest_fold=' + imgfolder;
	var imagePath = window.showModalDialog(url ,"","dialogHeight:340px;dialogWidth:480px;center:yes;help:no;scroll:yes;status:no;resizable:no ")
 	if ((imagePath != null) && (imagePath != "") && (imagePath != undefined)) {
		document.forms[0].elements['chimagen'].value = 1;
      document.forms[0].elements[campo].value = imagePath;
  	}
}
//-----------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_carrousel" ONSUBMIT="return ChequeaForm(this);">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" BGCOLOR="#CFCFCF">
<TR> 
<TD COLSPAN="3" ALIGN="RIGHT"><IMG SRC="img/flag_spain.png" WIDTH="16" HEIGHT="16" BORDER="0" ALIGN="ABSMIDDLE">&nbsp;<SPAN STYLE="color: white; font-weight: bold">Espa&ntilde;ol</SPAN></TD> 
</TR>
<TR>
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">T&iacute;tulo:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='titulo[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['titulo'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='100' MAXLENGTH='150'></TD> 
</TR>
<TR>
<TD><STRONG>Imagen:</STRONG></TD> 
<TD VALIGN="MIDDLE" COLSPAN="2">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
<TD><INPUT NAME='imagen[1]' CLASS="boxes" ID="imagen[1]" VALUE="<?php echo $formdata[1]->fields['imagen']; ?>" ID="imagen[1]" TYPE='TEXT' SIZE='40' MAXLENGTH='60'></TD>
<TD>&nbsp;<INPUT TYPE="BUTTON" CLASS='button' NAME="Examinar" VALUE="Examinar" ONCLICK="AddImage('imagen[1]');"></TD>
</TR>
</TABLE>
</TR>
<TR>
<TD VALIGN="TOP"><STRONG>URL:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='url[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['url'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='100' MAXLENGTH='150'></TD> 
</TR>
<TR>
<TD VALIGN="TOP"><STRONG>Posici&oacute;n:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='posicion[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['posicion']; ?>" TYPE='TEXT' SIZE='5' MAXLENGTH='3'></TD> 
</TR>
<TR> 
<TD COLSPAN="3"><STRONG>Activo:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
</TD>
</TR>
<TR> 
<TD COLSPAN="3"><STRONG>Publico:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="publico[1]" VALUE="1" <?php if($formdata[1]->fields['publico']==1){ echo "checked"; } ?>>
</TD>
</TR>


<TR>
<TD COLSPAN="3"><STRONG>Tipo:</STRONG>
&nbsp;&nbsp;
<SELECT NAME="nivel[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0" <?php if ($formdata[1]->fields['nivel']==0){echo "SELECTED=\"SELECTED\"";} ?>>Minorista</OPTION>
<OPTION VALUE="1" <?php if ($formdata[1]->fields['nivel']==1){echo "SELECTED=\"SELECTED\"";} ?>>Distribuidor</OPTION>
</SELECT>
</TD>
</TR>


</TABLE>
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
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"chimagen\" VALUE=\"0\">\n";
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
