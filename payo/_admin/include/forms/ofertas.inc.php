<style>  
.ui-autocomplete {
	max-height: 100px;
	width: 500px;
	overflow-y: auto;
	/* prevent horizontal scrollbar */
	overflow-x: hidden;
}
/* IE 6 doesn't support max-height
 * we use height instead, but this forces the menu to always be this tall
 */
* html .ui-autocomplete {
	height: 100px;
}
.ui-autocomplete-loading {
	background: white url('img/ui-anim_basic_16x16.gif') right center no-repeat;  
}  
</style>  
<script>  
$(function() {    
	$( "#cod_prod" )      // don't navigate away from the field on tab when selecting an item      
	.bind( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "ui-autocomplete" ).menu.active ) {
			event.preventDefault();
		}
	})
	.autocomplete({        
		source: function( request, response ) {
			$.getJSON( "pproductos_search.php", {
				term: ( request.term )
			}, response );
		},
		search: function() {          // custom minLength
			var term = ( this.value );
			if ( term.length < 2 ) {
				return false;
			}
			$( "#cod_prod_desc" ).html( "" );
		},
		focus: function() {          // prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			this.value = (ui.item.id);
			$( "#cod_prod_desc" ).html( ui.item.value );
			return false;
		}
	})
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
			.append( "<a>" + item.label + "&nbsp;&nbsp;&nbsp;&nbsp;" + item.value + "</a>" )
			.appendTo( ul );
	};
});
</script>
<?php
//---------------------------------------------------------------------------
echo "<SCRIPT>\n";
echo "AddCampo(\"cod_prod[1]\", 4, \"" . convert_str("el Código.",$default->encode)."\", \"text\",'');\n";
echo "AddCampo(\"fecha_alta[1]\", 4, \"" . convert_str("en Fecha de Publicación.",$default->encode)."\", \"date\",'');\n";
echo "AddCampo(\"fecha_baja[1]\", 4, \"" . convert_str("en Fecha de Caducidad.",$default->encode)."\", \"date\",'');\n";
echo "</SCRIPT>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF ?>" AUTOCOMPLETE="off" METHOD="POST" NAME="form_ofertas" ONSUBMIT="return ChequeaForm(this);">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0">
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">C&oacute;digo:</STRONG></TD> 
<TD><div class="ui-widget"><INPUT NAME='cod_prod[1]' CLASS="boxes" VALUE="<?php echo $formdata[1]->fields['cod_prod']; ?>" TYPE='TEXT' SIZE='10' ID="cod_prod" MAXLENGTH='4'>
&nbsp;&nbsp;<SPAN ID="cod_prod_desc" STYLE="color:<?php echo $default->fieldrequired_color ?>"><?php  echo $formdata[1]->fields['descripcion'];?></SPAN></div></TD> 
</TR>
<TR> 
<TD VALIGN="TOP"><STRONG>Descripci&oacute;n:</STRONG></TD> 
<TD VALIGN="TOP"><TEXTAREA NAME="oferta[1]" CLASS="boxes" ROWS="6" COLS="60"><?php echo htmlspecialchars($formdata[1]->fields['oferta'],ENT_QUOTES,$default->encode); ?></TEXTAREA></TD>
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha Publicaci&oacute;n:</STRONG></TD> 
<TD><INPUT NAME='fecha_alta[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_alta']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_alta[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD> 
</TR>
<TR> 
<TD><STRONG STYLE="color:<?php echo $default->fieldrequired_color ?>">Fecha Caducidad:</STRONG></TD> 
<TD><INPUT NAME='fecha_baja[1]' CLASS="boxes" VALUE="<?php echo timesql2std($formdata[1]->fields['fecha_baja']); ?>" TYPE='TEXT' SIZE='12' MAXLENGTH='10' ONKEYUP="this.value=formateafecha(this.value);">&nbsp;<A
HREF="javascript:ShowCalendar('fecha_baja[1]');"><IMG SRC="img/icono-calendar.gif" BORDER="0" ALT="Calendario" ALIGN="ABSMIDDLE"></A></TD> 
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
</TABLE> 
<BR>
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>
<TD><INPUT CLASS="button" NAME="submit" VALUE="Aceptar" TYPE="submit"></TD>
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Cancelar" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
</TR>
</TABLE>
<?php
form_hidden_fields($config, $formdata[1]->fields['id_oferta']);
?>
<SCRIPT>First_Field_Focus();</SCRIPT>
</FORM>
