<SCRIPT LANGUAGE="Javascript1.2">
// ------------------------------
function retorna(){
	doMenuSub('seccord');
	return true;
}
// ------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="GET" ONSUBMIT="return retorna();">
<TABLE>
<TR>
<TD VALIGN="TOP"><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Orden</STRONG></TD>
<TD><STRONG>General</STRONG>
<TABLE><TR>
<TD>
<SELECT STYLE="FONT-SIZE: 10px" SIZE="10" NAME="seccord">
<?php
$db = connect();
$db->debug = SDEBUG;
$menu_query = "SELECT id_menu,descripcion FROM e_menues WHERE $config[condicion] AND tipo=$config[tipo] AND lenguaje_id=1 ORDER BY posicion";
if ($result = $db->Execute($menu_query )) {
	while( !$result->EOF ){
		echo "<OPTION VALUE=\"p0~".$result->fields[id_menu]."\">".$result->fields[descripcion]."</OPTION>\n";
		$result->MoveNext();
	}
}
?>
</SELECT></TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'seccord');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'seccord');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
</TD>
</TR></TABLE>
</TD>
</TR>
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="SUBMIT"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="BUTTON" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
<TD></TD>
</TR>
</TABLE>
<?php
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"seccord_lst\" VALUE=\"\">\n";
form_hidden_fields($config, "","cmo");
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
