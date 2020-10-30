<?php
echo "<SCRIPT LANGUAGE=\"Javascript1.2\">\n";
echo "AddCampo(\"text_orig[1]\", 1, \"" . convert_str("el campo Texto.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"text_tran[1]\", 1, \"" . convert_str("el campo Text.",$default->encode)."\", \"text\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" NAME="form_trans" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Texto:</STRONG></TD> 
<TD><INPUT NAME='text_orig[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['text_orig']; ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='200'></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Traducci&oacute;n:</STRONG></TD> 
<TD><INPUT NAME='text_tran[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['text_tran']; ?>" TYPE='TEXT' SIZE='80' MAXLENGTH='200'></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Idioma:</STRONG></TD> 
<TD>
<?php
if ($op=="a"){
	echo "<SELECT SIZE=\"1\" NAME=\"id_lang\">\n";
	$db = connect();
	$db->debug = SDEBUG;
	$qry = "SELECT * FROM lenguajes WHERE id_lenguaje>1 ORDER BY lenguaje";
	if ($respu = $db->Execute($qry)){
		while( !$respu->EOF ){
			if ($respu->fields[id_lenguaje]==$formdata[1]->fields['lenguaje_id']){
				$SELECTED="SELECTED";
			} else {
				$SELECTED="";
			}
			echo "<OPTION VALUE=\"".$respu->fields[id_lenguaje]."\" $SELECTED>".$respu->fields[lenguaje]."</OPTION>\n";
			$respu->MoveNext();
		}
	}
	echo "</SELECT>\n";
} else {
	$db = connect();
	$db->debug = SDEBUG;
	$qry = "SELECT * FROM lenguajes WHERE id_lenguaje>={$formdata[1]->fields['lenguaje_id']}";
	if ($respu = $db->Execute($qry)){
		echo "<STRONG>{$respu->fields[lenguaje]}</STRONG>";
	}
}
?>
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
form_hidden_fields($config, $formdata[1]->fields['id_trans']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
