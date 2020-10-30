<?php
echo "<SCRIPT LANGUAGE=\"Javascript\">";
//------------------------------------------------------------------------------
echo "function Retorna(formulario){";
echo "doSub('gess');";
if ($op=='a'){
	echo "if (ChequeaForm(formulario)){";
} else {
	echo "if (control_form_user(formulario)){";	
}	
echo "return true;";
echo "}";
echo "return false;";
echo "}";
//-----------------------------------------------------
echo "</SCRIPT>\n";
//---------------------------------------------------------------------------
echo "<SCRIPT LANGUAGE=\"Javascript\" SRC=\"jscripts/".strtolower($default->encode)."/hash.js\"></SCRIPT>\n";
echo "<SCRIPT LANGUAGE=\"Javascript\">\n";
echo "AddCampo(\"username[1]\", 4, \"" . convert_str("el campo Usuario.",$default->encode)."\", \"text\",'');\n";
if ($op=='a'){ 
	echo "AddCampo(\"pass1\", 5, \"" . convert_str("la Contraseña.",$default->encode)."\", \"text\",'');\n";
	echo "AddCampo(\"pass2\", 5, \"" . convert_str("la verificación de la Contraseña.",$default->encode)."\", \"text\",'');\n";
}
echo "AddCampo(\"coef[1]\", 1, \"" . convert_str("el campo Usuario.",$default->encode)."\", \"num\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST" ONSUBMIT="return Retorna(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Usuario:</STRONG></TD> 
<TD><INPUT TYPE="text" NAME="username[1]" SIZE="10" MAXLENGTH="10" CLASS="boxes" VALUE="<?php  echo $formdata[1]->fields['username']?>"></TD> 
</TR>
<?php
if ($op=='a'){ 
	echo "<TR>\n"; 
	echo "<TD><STRONG STYLE=\"color: $default->fieldrequired_color;\">Contrase&ntilde;a:</STRONG></TD>\n";
	echo "<TD><INPUT TYPE=\"password\" NAME=\"pass1\" SIZE=\"14\" MAXLENGTH=\"14\" CLASS=\"boxes\" VALUE=\"\"></TD>\n"; 
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD><STRONG STYLE=\"color: $default->fieldrequired_color;\">Repetir Contrase&ntilde;a:</STRONG></TD>\n";
	echo "<TD><INPUT TYPE=\"password\" NAME=\"pass2\" SIZE=\"14\" MAXLENGTH=\"14\" CLASS=\"boxes\" VALUE=\"\"></TD>\n";
	echo "</TR>\n";
}
?>
<TR>
<TD><STRONG>Nombre:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="nombres[1]" SIZE="40" MAXLENGTH="60" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['nombres'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>
<TR>
<TD><STRONG>Apellido:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="apellidos[1]" SIZE="40" MAXLENGTH="60" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['apellidos'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>
<TR>
<TD><STRONG>Email:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="email[1]" SIZE="60" MAXLENGTH="120" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['email'],ENT_QUOTES,$default->encode);?>'></TD>
</TR>

<TR>
<TD><STRONG>Raz&oacute;n Social:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="razonsocial[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['razonsocial'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>
<TR>
<TD><STRONG>Domicilio:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="direccion[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['direccion'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>
<TR>
<TD><STRONG>Ciudad:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="ciudad[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['ciudad'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>
<TR>
<TD><STRONG>C.P.:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="cp[1]" SIZE="10" MAXLENGTH="10" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['cp']?>'></TD>
</TR>
<TR>
<TD><STRONG>Provincia:</STRONG></TD>
<TD>
<SELECT NAME="provincia_id[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0">----------------------</OPTION>
<?php
$provdb = connect();
$provdb->debug = SDEBUG;
$provq = "select * from provincias;";
if ($provr = $provdb->Execute($provq)){
    while( !$provr->EOF ){
        echo "<OPTION VALUE=\"".$provr->fields[id_provincia]."\"";
        if ($formdata[1]->fields['provincia_id']==$provr->fields['id_provincia']) {
            echo ' SELECTED="SELECTED"';
        }
        echo ">".$provr->fields[provincia]."</OPTION>\n";
        $provr->MoveNext();
    }
}
?>
</SELECT>
</TD>
</TR>
<TR>
<TD><STRONG>Telefono:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="telefonos[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['telefonos'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>

<TR>
<TD><STRONG>WEB:</STRONG></TD>
<TD><INPUT TYPE="TEXT" NAME="web[1]" SIZE="50" MAXLENGTH="50" CLASS="boxes" VALUE='<?php echo htmlspecialchars($formdata[1]->fields['web'],ENT_QUOTES,$default->encode); ?>'></TD>
</TR>

<TR>
<TD><STRONG>Rubro:</STRONG></TD>
<TD>
<SELECT NAME="rubro_id[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0">----------------------</OPTION>
<?php
$provdb = connect();
$provdb->debug = SDEBUG;
$provq = "select * from e_rubrosw order by rubrow";
if ($provr = $provdb->Execute($provq)){
    while( !$provr->EOF ){
        echo "<OPTION VALUE=\"".$provr->fields[id_rubrow]."\"";
        if ($formdata[1]->fields['rubro_id']==$provr->fields['id_rubrow']) {
            echo ' SELECTED="SELECTED"';
        }
        echo ">".$provr->fields[rubrow]."</OPTION>\n";
        $provr->MoveNext();
    }
}
?>
</SELECT>
</TD>
</TR>

<TR> 
<TD><STRONG>C&oacute;digo EBASE:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="TEXT" NAME="eb_cod[1]" SIZE="10" MAXLENGTH="10" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['eb_cod']?>'>
</TD>
</TR>

<TR>
<TD><STRONG>I.V.A.:</STRONG></TD>
<TD>
<SELECT NAME="iva[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0">----------------------</OPTION>
<?php
$provdb = connect();
$provdb->debug = SDEBUG;
$provq = "select * from e_ivas";
if ($provr = $provdb->Execute($provq)){
    while( !$provr->EOF ){
        echo "<OPTION VALUE=\"".$provr->fields[id_iva]."\"";
        if ($formdata[1]->fields['iva']==$provr->fields['id_iva']) {
            echo ' SELECTED="SELECTED"';
        }
        echo ">".$provr->fields[descripcion]."</OPTION>\n";
        $provr->MoveNext();
    }
}
?>
</SELECT>
</TD>
</TR>
<TR> 
<TD><STRONG>C.U.I.T.:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="TEXT" NAME="cuit[1]" SIZE="13" MAXLENGTH="13" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['cuit']?>'>
</TD>
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
<TD><STRONG>Vendedor:</STRONG></TD>
<TD>
<SELECT NAME="vendedor_id[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0">----------------------</OPTION>
<?php
$vendb = connect();
$vendb->debug = SDEBUG;
$venq = "select * from e_vendedores where activo=1 order by vendedor";
if ($venr = $provdb->Execute($venq)){
    while( !$venr->EOF ){
        echo "<OPTION VALUE=\"".$venr->fields[id_vendedor]."\"";
        if ($formdata[1]->fields['vendedor_id']==$venr->fields['id_vendedor']) {
            echo ' SELECTED="SELECTED"';
        }
        echo ">".$venr->fields[vendedor]."</OPTION>\n";
        $venr->MoveNext();
    }
}
?>
</SELECT>
</TD>
</TR>

<TR> 
<TD><STRONG>Coficiente:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="TEXT" NAME="coef[1]" SIZE="4" MAXLENGTH="5" CLASS="boxes" VALUE='<?php echo $formdata[1]->fields['coef']?>'>
</TD>
</TR>
<TR>
<TD><STRONG>Pago:</STRONG></TD>
<TD>
<SELECT NAME="pago[1]" SIZE="1" CLASS="editselectboxes">
<OPTION VALUE="0" <?php if($formdata[1]->fields['pago']==0){ echo ' SELECTED="SELECTED"';} ?>>Cuenta Corriente</OPTION>
<OPTION VALUE="1" <?php if($formdata[1]->fields['pago']==1){ echo ' SELECTED="SELECTED"';} ?>>Mercado Pago</OPTION>
</SELECT>
</TD>
</TR>




<TR>
<TD COLSPAN="2">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0"> 
<TR> 
<TD WIDTH="144"ALIGN="CENTER"><B>Gestiones disponibles</B></TD>
<TD WIDTH="35" ALIGN="CENTER"></TD>   
<TD WIDTH="144"ALIGN="CENTER"><B>Gestiones seleccionadas</B></TD> 
</TR> 
<TR> 
<TD WIDTH="144" ROWSPAN="2"> 
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 144px" SIZE="7" NAME="gesd">
<?php
$db = connect();
$db->debug = SDEBUG;
$query = "SELECT id_gestion,gestion FROM e_gestiones WHERE id_gestion NOT IN (SELECT gestion_id from e_user_gestiones where user_id=$id) ORDER BY gestion";
if ($formr = $db->Execute($query)){
	while( !$formr->EOF ){
		echo "<OPTION VALUE=\"".$formr->fields[id_gestion]."\">".$formr->fields[gestion]."</OPTION>\n";
		$formr->MoveNext();
	}
}
?>
</SELECT>
</TD>
<TD WIDTH="35" VALIGN="MIDDLE" ALIGN="CENTER">
<A HREF="javascript:AddMod('gess','gesd','gess');"><IMG SRC="img/right.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Incluir"></A><BR><BR>
<A HREF="javascript:AddMod('gesd','gess','gess');"><IMG SRC="img/left.gif" WIDTH="12" HEIGHT="13"	BORDER="0" ALT="Excluir"></A>
</TD>
<TD WIDTH="144" ROWSPAN="2">
<SELECT STYLE="FONT-SIZE: 10px; WIDTH: 144px" SIZE="7" NAME="gess">
<?php
$query = "SELECT id_gestion,gestion FROM e_gestiones WHERE id_gestion IN (SELECT gestion_id from e_user_gestiones where user_id=$id) ORDER BY gestion";
if ($formselr = $db->Execute($query)){
	while( !$formselr->EOF ){
		echo "<OPTION VALUE=\"".$formselr->fields[id_gestion]."\">".$formselr->fields[gestion]."</OPTION>\n";
		$formselr->MoveNext();
	}
}
?>
</SELECT>
</TD>
</TR>
</TABLE>
</TD>
</TR>


<TR> 
<TD><STRONG>Activo:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="CHECKBOX" NAME="activo_u[1]" VALUE="1" <?php if($formdata[1]->fields['activo_u']==1){ echo "checked"; } ?>>
</TD>
</TR>
<?php
if ($op!='a'){ 
?>
<TR> 
<TD><STRONG>Notificar activaci&oacute;n:</STRONG>&nbsp;&nbsp;</TD>
<TD><INPUT TYPE="CHECKBOX" NAME="notificar[1]" VALUE="1">
</TD>
</TR>
<?php
}
?>
</TABLE>
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>            
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>           
</TABLE>        
<?php
if ($op=='a'){ 
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"password\" VALUE=\"".$formdata[1]->fields['password']."\">\n";
}
echo "<INPUT TYPE=\"HIDDEN\" NAME=\"gess_lst\" VALUE=\"\">\n";
form_hidden_fields($config, $id);
?>
<SCRIPT>First_Field_Focus()</SCRIPT>
</FORM>
