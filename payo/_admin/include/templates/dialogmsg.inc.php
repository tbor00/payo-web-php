<BR><BR><BR><BR>
<CENTER>
<TABLE WIDTH="40%" BORDER="0" CELLPADDING="0" CELLSPACING="1" BGCOLOR="<?php echo $default->border_color ?>">
<TR><TD>
<?php
echo "<TABLE BORDER='0' WIDTH='100%' CELLPADDING='3' CELLSPACING='0'>\n";
echo "<TR><TH ALIGN='CENTER' BGCOLOR='$default->title_bgcolor' WIDTH='10%' VALIGN='MIDDLE'><STRONG STYLE=\"color: $default->title_color\">".convert_str($config[titulo],$default->encode)."</STRONG></TH></TR>\n";
echo "<TR>\n";
/*
foreach($config['campos'] as $k => $v){
    $config['mensaje'] .= " ".$result->fields[$v];
}
*/
$config['mensaje'] .= "por otra tabla del sistema.";
echo "<TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'>";
echo $config[mensaje];
if ($msg != ""){
	echo "<BR>TABLA: ".convert_str($msg,$default->encode);
}
echo "<BR></TD></TR>\n";
echo "<TR><TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'> <BR></TD></TR>\n";
echo "<TR><TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'>\n";
echo "<FORM ACTION=\"{$_SERVER['PHP_SELF']}\" METHOD=POST>";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">";
echo "<TR>";
echo "<TD>";
echo "<INPUT CLASS=\"button\" TYPE=SUBMIT VALUE=Aceptar>";
foreach($config['submit_option'] as $key => $value){
	echo "<INPUT TYPE=HIDDEN NAME=\"$key\" VALUE=\"$value\">\n";
}
if ($config[parent]){
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"parent\" VALUE=\"$config[parent]\">\n";
}
echo "<INPUT TYPE=HIDDEN NAME=\"op\" VALUE=\"$config[op]\">\n";
echo "</TD>\n";
echo "</TR>\n";		
echo "</TABLE>\n";
echo "</FORM>";
echo "</TD>\n";
echo "</TR>\n";		
echo "</TABLE>\n";
?>
</TD></TR></TABLE></CENTER><BR><BR><BR>