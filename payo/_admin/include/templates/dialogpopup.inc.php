<CENTER> 
<TABLE WIDTH="99%" BORDER="0" CELLPADDING="0" CELLSPACING="1" BGCOLOR="<?php echo $default->border_color ?>"> 
<TR><TD> 
<?php
echo "<TABLE BORDER='0' WIDTH='100%' CELLPADDING='3' CELLSPACING='0'>\n"; 
echo "<TR><TH ALIGN='CENTER' BGCOLOR='$default->title_bgcolor' WIDTH='10%' VALIGN='MIDDLE'><STRONG STYLE=\"color: $default->title_color\">$config[titulo]</STRONG></TH></TR>\n"; 
echo "<TR>\n";
$config['mensaje'] = convert_str($config['mensaje'],$default->encode);
foreach($config['campos'][0] as $k){ 
    $config['mensaje'].= "<STRONG>" . $result->fields[$k] . "</STRONG>"; 
} 
echo "<TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'> $config[mensaje] <BR></TD></TR>\n"; 
echo "<TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'> <BR></TD></TR>\n"; 
echo "<TR><TD ALIGN='center' BGCOLOR='$default->dialog_bgcolor' WIDTH='100%' VALIGN='MIDDLE'>\n"; 
echo "<FORM ACTION=\"$PHP_SELF\" METHOD=POST><INPUT CLASS=\"button\" TYPE=SUBMIT VALUE=Aceptar>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT CLASS=\"button\" TYPE=BUTTON VALUE=Cancelar onClick='$config[cancel_option]'>\n"; 
foreach($config['submit_option'] as $key => $value){ 
    echo "<INPUT TYPE=HIDDEN NAME=\"$key\" VALUE=\"$value\">\n"; 
}
if ($config[parent]){
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"parent\" VALUE=\"$config[parent]\">\n";
}
echo "</FORM>\n"; 
echo "</TD>\n"; 
echo "</TR>\n"; 
echo "</TABLE>\n"; 
?> 
</TD></TR></TABLE></CENTER><BR><BR><BR>