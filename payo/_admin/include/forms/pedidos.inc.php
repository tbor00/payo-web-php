<?php
$color1 = "#99CCFF";  
$color2 = "#E3F5FB";

$query = "SELECT e_cotiza.*,e_webusers.razonsocial,e_webusers.eb_cod,e_webusers.apellidos,e_webusers.nombres,e_webusers.nivel,e_webusers.coef  FROM e_cotiza JOIN e_webusers ON e_webusers.user_id=e_cotiza.user_id WHERE id_cotiza=$id";
$cotizacion_r=$db->Execute($query);
if ($cotizacion_r && !$cotizacion_r->EOF){
	echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"2\" BGCOLOR=\"#0082D6\">\n";
	echo "<TR VALIGN=\"TOP\">";
	echo "<TD BGCOLOR=\"#FFFFFF\" VALIGN=\"TOP\" ALIGN=\"CENTER\">\n";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
	echo "<TR><TD BGCOLOR=\"#0082D6\"><STRONG STYLE=\"color: white;\">PEDIDO</STRONG></TD></TR>\n";
	echo "<TR><TD><STRONG>NUMERO: </STRONG>".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</TD></TR>\n";
	echo "<TR><TD><STRONG>FECHA: </STRONG>".timest2dt($cotizacion_r->fields['fecha'])."</TD></TR>\n";
	if ($cotizacion_r->fields['razonsocial']){
		if ($cotizacion_r->fields['eb_cod']) {
			echo "<TR><TD><STRONG>CLIENTE: </STRONG>".$cotizacion_r->fields['razonsocial']." (".$cotizacion_r->fields['eb_cod'].") </TD></TR>\n";
		} else {
			echo "<TR><TD><STRONG>CLIENTE: </STRONG>".$cotizacion_r->fields['razonsocial']."</TD></TR>\n";
		}
	} else {
		echo "<TR><TD><STRONG>CLIENTE: </STRONG>".$cotizacion_r->fields['nombres']." ".$cotizacion_r->fields['apellidos']."</TD></TR>\n";
	}
	echo "<TR><TD><STRONG>ESTADO: </STRONG>";
	if ($cotizacion_r->fields['estado']==1){
		echo "Borrador";
	} elseif ($cotizacion_r->fields['estado']==2){
		echo "Enviado";
	} elseif ($cotizacion_r->fields['estado']==10){
		echo "Anulado";
	} else {
		echo "Pendiente";
	}
	echo "</TD></TR>\n";
	echo "</TABLE><BR>\n";
}


$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$id $ORDER";
if ($items_r=$db->Execute($query)){
	echo "<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">\n";
	echo "<TR BGCOLOR=\"#0082D6\">\n";
	echo "<TH></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Cod</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Descripción</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Marca</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Modelo</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Cod Fab</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Cantidad</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Precio</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">IVA</STRONG></TH>\n";
	echo "<TH><STRONG STYLE=\"color: white;\">Subtotal</STRONG></TH>\n";
	echo "</TR>\n";
	while (!$items_r->EOF){
		if ($colorx==$color1){
			$colorx=$color2;
		}else{
			$colorx=$color1;
		}
		echo "<TR BGCOLOR=\"$colorx\">\n";
		echo "<TD></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\">".$items_r->fields['codigo_prod']."</TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\">".$items_r->fields['descripcion_prod']."</A></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\">".$items_r->fields['marca_prod']."</TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\">".$items_r->fields['modelo_prod']."</TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\">".$items_r->fields['codfab']."</TD>\n";
		echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".$items_r->fields['cantidad']."</TD>\n";
		if ( $cotizacion_r->fields['estado']==2){
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".$items_r->fields['unitario']."</TD>\n";
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".$items_r->fields['iva']."</TD>\n";
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$items_r->fields['cantidad']*$items_r->fields['unitario'])."</TD>\n";
			$total = $total + ($items_r->fields['cantidad']*$items_r->fields['unitario']);
			$tiva = $tiva + ($items_r->fields['cantidad']*$items_r->fields['unitario']*$items_r->fields['iva']/100);
		} else {
			if ($cotizacion_r->fields['nivel']==1){
				$loc_precio=$items_r->fields['precio2']*$cotizacion_r->fields['coef'];
			} else {
				$loc_precio=$items_r->fields['precio']*$cotizacion_r->fields['coef'];
			} 
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".$loc_precio."</TD>\n";
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".$items_r->fields['alicuota']."</TD>\n";
			echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$items_r->fields['cantidad']*$loc_precio)."</TD>\n";
			$total = $total + ($items_r->fields['cantidad']*$loc_precio);
			$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
		}			    
		echo "</TR>\n";
		$items_r->MoveNext();
	}
	echo "<TR>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\"><STRONG>Subtotal</STRONG></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$total)."</TD>\n";
	echo "</TR>\n";
	if ($cotizacion_r->fields['descuento']>0){
		$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
		$subtotal = $total-($total*$cotizacion_r->fields['descuento']/100);
		$i_descuento = ($total*$cotizacion_r->fields['descuento']/100);
		echo "<TR>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
		echo "</TR>\n";
	
		echo "<TR>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\"><STRONG>Subtotal</STRONG></TD>\n";
		echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$subtotal)."</TD>\n";
		echo "</TR>\n";
	}
	echo "<TR>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\"><STRONG>Total IVA</STRONG></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$tiva)."</TD>\n";
	echo "</TR>\n";
	echo "<TR>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\"><STRONG>Total</STRONG></TD>\n";
	echo "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\">".sprintf("%01.2f",$total+$tiva-i_descuento)."</TD>\n";
	echo "</TR>\n";
	echo "</TABLE>\n";
}	
if (strlen($cotizacion_r->fields['comentario']) > 0){
	echo "<P><BR></P>\n\n";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
	echo "<TR><TD CLASS=\"titulobrowse\" BGCOLOR=\"#0082D6\"><STRONG STYLE=\"color: white;\">".("COMENTARIOS")."</STRONG></TD></TR>\n";
	echo "<TR><TD>";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
	echo "<TR><TD VALIGN=\"TOP\" ALIGN=\"LEFT\" CLASS=\"textobrowse\">";
	echo nl2br($cotizacion_r->fields['comentario']);
	echo "</TD>\n";
	echo "</TR></TABLE></TD></TR></TABLE>\n";
}
echo "<P><BR></P>";
echo "</TD></TR></TABLE>\n";
?>
<BR>
<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST">
<TABLE CELLPADDING="1" CELLSPACING="1" BORDER="0" WIDTH="100">
<TR>            
<TD><INPUT CLASS="button" NAME="cancel" VALUE="Volver" TYPE="button" ONCLICK="<?php echo "document.location='$PHP_SELF?$config[cancel_option]'"; ?>"></TD>
<?php
if ($cotizacion_r->fields['estado']==2){
	echo "<TD><INPUT CLASS=\"button\" NAME=\"submit\" VALUE=\"Re-Enviar\" TYPE=\"submit\"></TD>\n";
}
?>
</TR>           
</TABLE>        
<?php
form_hidden_fields($config, $id);
?>
</FORM>
