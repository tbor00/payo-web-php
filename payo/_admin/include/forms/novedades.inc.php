<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"titulo[1]\", 3, \"" . convert_str("el campo Título.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"sumario[1]\", 3, \"" . convert_str("el campo Sumario.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"novedades[1]\", 4, \"" . convert_str("el campo Contenido.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"fecha_inicio[1]\", 1, \"" . convert_str("Fecha de Publicación",$default->encode)."\", \"date\",'');\n";
echo "AddCampo(\"fecha_fin[1]\", 1, \"" . convert_str("Fecha de Caducidad",$default->encode)."\", \"date\",'');\n";
echo "</SCRIPT>\n";
?>
<SCRIPT LANGUAGE="Javascript">
var campo="";
//-----------------------------------------------------
function AddImage(fcampo) {
	campo = fcampo;
	var imgfolder = '../novedades/imagenes/';
	var url = 'brwimages.php?dest_fold=' + imgfolder;
	var imagePath = window.showModalDialog(url ,"","dialogHeight:340px;dialogWidth:480px;center:yes;help:no;scroll:yes;status:no;resizable:no ")
 	if ((imagePath != null) && (imagePath != "") && (imagePath != undefined)) {
		document.forms[0].elements['chimagen'].value = 1;
      document.forms[0].elements[campo].value = imagePath;
  	}
}
//-----------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_novedades" ONSUBMIT="return ChequeaForm(this);">
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
<TD><INPUT NAME='imagen[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['imagen']; ?>" TYPE='TEXT' SIZE='40' MAXLENGTH='60'></TD>
<TD>&nbsp;<INPUT TYPE="BUTTON" CLASS='button' NAME="Examinar" VALUE="Examinar" ONCLICK="AddImage('imagen[1]');"></TD>
</TR>
</TABLE>
</TR>
<TR>
<TD><STRONG>Alineaci&oacute;n de Imagen:</STRONG></TD> 
<TD COLSPAN="2">&nbsp;&nbsp;Izquierda&nbsp;&nbsp;<INPUT NAME="img_align[1]" ONCLICK="" TYPE="radio" VALUE="0"  <?php if($formdata[1]->fields['img_align']==0){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Derecha&nbsp;&nbsp; <INPUT NAME="img_align[1]" ONCLICK="" TYPE="radio" VALUE="1" <?php if($formdata[1]->fields['img_align']==1){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;
</TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Ep&iacute;grafe de Imagen:</STRONG></TD> 
<TD COLSPAN="2"><INPUT NAME='img_epigrafe[1]' CLASS="boxes" VALUE="<?php echo htmlspecialchars($formdata[1]->fields['img_epigrafe'],ENT_QUOTES,$default->encode); ?>" TYPE='TEXT' SIZE='78' MAXLENGTH='150'></TD> 
</TR> 
 
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Resumen:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="sumario[1]" CLASS="boxes" ROWS="6" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['sumario'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
<TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_sumario" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('sumario[1]','');"></TD>
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Novedad:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="novedades[1]" CLASS="boxes" ROWS="10" COLS="80"><?php echo htmlspecialchars($formdata[1]->fields['novedad'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
<TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="BUTTON" NAME="ed_novedad_es" CLASS="button" VALUE="Editor HTML" ONCLICK="javascript:OpenEditorHTML('novedades[1]','');"></TD>
</TR>

<TR>																																										  
<TD COLSPAN="2"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha de Publicaci&oacute;n:</STRONG>&nbsp;
<INPUT NAME='fecha_inicio[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_inicio']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_inicio[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD>
<TD></TD>
</TR>
<TR>																																										  
<TD COLSPAN="2"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha de Caducidad:</STRONG>&nbsp;&nbsp;
<INPUT NAME='fecha_fin[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_fin']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_fin[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD>
<TD></TD> 
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
<TR> 
<TD COLSPAN="3"><STRONG>Activo:</STRONG>
&nbsp;&nbsp;<INPUT TYPE="CHECKBOX" NAME="activo[1]" VALUE="1" <?php if($formdata[1]->fields['activo']==1){ echo "checked"; } ?>>
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
