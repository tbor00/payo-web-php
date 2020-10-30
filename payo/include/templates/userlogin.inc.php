<?php
if ($_SESSION['logged']){
	echo "<BR><BR>\n";
	echo "<FORM METHOD=\"POST\" ACTION=\"{$_SERVER['PHP_SELF']}\" NAME=\"login_form\">\n";
	echo "<INPUT NAME=\"op\" VALUE=\"$op\" TYPE=\"HIDDEN\">\n";
	echo "<INPUT NAME=\"sop\" VALUE=\"$sop\" TYPE=\"HIDDEN\">\n";
	echo "<INPUT NAME=\"auth\" VALUE=\"login\" TYPE=\"HIDDEN\">\n";
	echo "<TABLE CELLPADDING=\"1\" CELLSPACING=\"1\" BORDER=\"0\">\n";
	echo "<TR>\n";
	echo "<TD COLSPAN=\"2\"><STRONG>".translate("Ingresar")."</STRONG></TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD COLSPAN=\"2\">&nbsp;</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD ALIGN=\"LEFT\">".translate("Usuario").":</TD>\n";
	echo "<TD><INPUT NAME=\"user\" CLASS=\"boxes\" SIZE=\"17\" MAXLENGTH=\"32\" VALUE=\"\"></TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD ALIGN=\"LEFT\">".translate("Contrase&ntilde;a").":</TD>\n";
	echo "<TD><INPUT NAME=\"passwd\" TYPE=\"password\" CLASS=\"boxes\" SIZE=\"17\" MAXLENGTH=\"32\"></TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD COLSPAN=\"2\">&nbsp;</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD COLSPAN=\"2\"><INPUT NAME=\"btnlog\" TYPE=\"submit\" CLASS=\"button\" VALUE=\"".translate("Ingresar")."\"></TD>\n";
	echo "</TR>\n";
	echo "</TABLE><BR>\n";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\">\n";
	echo "<TR>\n";
	echo "<TD><A HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&auth=forgotpwd\" STYLE=\"font-size: 7pt\">".translate("¿Olvid&oacute; su contrase&ntilde;a?")."</A></TD>\n";
	echo "</TR>\n";
	echo "</TABLE><BR>\n";
	echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
	echo "document.login_form.user.focus();\n";
	echo "</SCRIPT>\n";
	echo "</FORM>\n";
}
?>

