<SCRIPT LANGUAGE="Javascript1.2">
// ------------------------------
function retorna(){
	doMenuSub('smenuord');
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
<SELECT STYLE="FONT-SIZE: 10px" SIZE="10" NAME="smenuord">
<?php
$db = connect();
$db->debug = SDEBUG;
$menu_query = "SELECT id_menu,titulo FROM e_menues WHERE $config[condicion] AND tipo=5 AND lenguaje_id=1 ORDER BY posicion";
if ($result = $db->Execute($menu_query )) {
	while( !$result->EOF ){
		echo "<OPTION VALUE=\"p0~".$result->fields[id_menu]."\">".$result->fields[titulo]."</OPTION>\n";
		/*
		$submenu_query = "SELECT id_menu,titulo FROM menues WHERE $config[condicion] AND tipo=3 AND lenguaje_id=1 AND menu_id=".$result->fields[id_menu]." ORDER BY posicion";
		if ($subresult = $db->Execute($submenu_query )) {
			while( !$subresult->EOF ){
				echo "<OPTION value=\"p".$result->fields[id_menu]."~".$subresult->fields[id_menu]."\">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;".$subresult->fields[titulo]."</OPTION>\n";
				$subresult->MoveNext();
			}
		}
		*/
		$result->MoveNext();
	}
}
?>
</SELECT></TD>
<TD WIDTH="50" VALIGN="MIDDLE" ALIGN="CENTER" ROWSPAN="2">
<A HREF="javascript:orderMenu(0,'smenuord');"><IMG SRC="img/up.gif" BORDER="0" ALT="Subir" VSPACE="2"></A><BR>
<A HREF="javascript:orderMenu(1,'smenuord');"><IMG SRC="img/down.gif" BORDER="0" ALT="Bajar" VSPACE="2"></A>
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
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"smenuord_lst\" VALUE=\"\">\n";
form_hidden_fields($config, "","cmo");
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
