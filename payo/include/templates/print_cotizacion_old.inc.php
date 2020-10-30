<?php
$loc_title = $default->web_title . " - ".translate("Pedido");
$db = connect();
$db->debug = SDEBUG;
$cotizanum=$_GET['cotizanum'];
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="es" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="es" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="es">
<!--<![endif]-->
<HEAD> 
<TITLE><?php echo $loc_title ?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1" />
<META NAME="Keywords" CONTENT="<?php echo mk_keywords() ?>" />
<META NAME="Copyright" CONTENT="<?php echo $default->copyright ?>" />
<META NAME="Author" CONTENT="<?php echo $default->author ?>" />
<META NAME="Description" CONTENT="<?php echo $default->site_description ?>">
<LINK HREF="favicon.ico" REL="Shortcut Icon" TYPE="image/x-icon" />
<LINK HREF="favicon.ico" REL="icon" TYPE="image/x-icon" />
<link href="theme/stylesheet/print.css" rel="stylesheet">
</HEAD>
<BODY style="font-size: 9pt;" BGCOLOR="#C7C7C7" ONLOAD="window.print();"> 

<?php
if ($_SESSION[logged]){

	$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$cotizanum";
	$cotizacion_r=$db->Execute($query);
	if ($cotizacion_r && !$cotizacion_r->EOF){
		echo  "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"2\" BGCOLOR=\"#eeeeee\">\n";
		echo  "<TR VALIGN=\"TOP\">";
		echo  "<TD BGCOLOR=\"#FFFFFF\" VALIGN=\"TOP\" ALIGN=\"CENTER\">\n";
		echo  "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\">\n";
		echo  "<TR>\n";
		echo  "<TD BGCOLOR=\"#eeeeee\" ALIGN=\"LEFT\"><IMG SRC=\"image/logo.gif\" ALT=\"\" BORDER=\"0\"></TD>\n";
		echo  "</TR></TABLE><BR>\n";
		echo  "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
		echo  "<TR><TD COLSPAN=\"2\"><STRONG>".translate("PEDIDO")." NRO.: ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</STRONG></TD></TR>";
		echo  "<TR><TD COLSPAN=\"2\"><STRONG>FECHA: ".timest2dt($cotizacion_r->fields['fecha'])."</STRONG></TD></TR>\n";
		echo  "<TR><TD COLSPAN=\"2\"><STRONG>".translate("REFERENCIA").": </STRONG>".htmlentities($cotizacion_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD></TR>\n";
		echo  "</TABLE>\n";
		echo  "<P><BR></P>";
		$estado = $cotizacion_r->fields['estado'];
		$civa = $cotizacion_r->fields['iva'];
		$fecha = floor(adodb_mktime(0,0,0,substr($cotizacion_r->fields['fecha'],5,2),substr($cotizacion_r->fields['fecha'],8,2),substr($cotizacion_r->fields['fecha'],0,4))- adodb_mktime(0,0,0,12,29,1800))/(60 * 60 * 24);
	} else {
		echo "<P>".translate("Pedido de Cotización no encontrado")."</P>\n";
		$fin = 1;
	}
	if ($fin == 0){
//		$query = "SELECT * FROM e_cotiza_lineas WHERE id_cot=$cotizanum";
		$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$cotizanum $ORDER";
		if ($items_r=$db->Execute($query)){
			echo  "<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">\n";
			echo  "<TR BGCOLOR=\"#eeeeee\">\n";
			echo  "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">C&oacute;digo</TH>\n";
			echo  "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Descripci&oacute;n</TH>\n";
			echo  "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Marca</TH>\n";
			echo  "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Modelo</TH>\n";
			echo  "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Unidad</TH>\n";
			echo  "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Cantidad</TH>\n";
			echo  "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Precio</TH>\n";
			echo  "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">IVA</TH>\n";
			echo  "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Subtotal</TH>\n";
			echo  "</TR>\n";
			if ($items_r->EOF){
				echo  "<TR>\n";
				echo  "<TD COLSPAN=\"9\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">".translate("No se encontraron items en el Pedido")."</TD>\n";
				echo  "</TR>\n";
			}
			while (!$items_r->EOF){
				$nn++;
				echo  "<TR>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['codigo_prod'],ENT_QUOTES,$default->encode)."</A></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['descripcion_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['marca_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['modelo_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['unidad_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['cantidad']."</TD>\n";
				if ( $estado==2){
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".decimales($items_r->fields['unitario'])."</TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['iva']."</TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".sprintf("%1.2f",$items_r->fields['cantidad']*$items_r->fields['unitario'])."</TD>\n";
					$total = $total + ($items_r->fields['cantidad']*$items_r->fields['unitario']);
					if ($civa==0){
						$tiva = $tiva + ($items_r->fields['cantidad']*$items_r->fields['unitario']*$items_r->fields['iva']/100);
					}
				} else {
					if ($_SESSION[nivel]==1){
						$loc_precio=$items_r->fields['precio2']*$_SESSION['coef'];
					} else {
						$loc_precio=$items_r->fields['precio']*$_SESSION['coef'];
					} 
					if ($_SESSION[iva]>0){
						$loc_precio = $loc_precio * (1+($items_r->fields['alicuota']/100));	
					}
					$loc_precio = round($loc_precio,$default->decimales);
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".decimales($loc_precio)."</TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['alicuota']."</TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".sprintf("%1.2f",$items_r->fields['cantidad']*$loc_precio)."</TD>\n";
					$total = $total + ($items_r->fields['cantidad']*$loc_precio);
					if ($_SESSION[iva]>0){
						$tiva = 0;
					} else {
						$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
					}
				}	
				echo  "</TR>\n";
				$items_r->MoveNext();
			}
			if ($_SESSION[iva]==0 || $civa==0){
				echo  "<TR>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total)."</TD>\n";
				echo  "</TR>\n";
				if ($cotizacion_r->fields['descuento']>0){
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
					echo  "<TR>\n"; 
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
					echo  "</TR>\n";
					echo  "<TR>\n"; 
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total-$i_descuento)."</TD>\n";
					echo  "</TR>\n";
				}
				echo  "<TR>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total IVA</STRONG></TD>\n";
				echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$tiva)."</TD>\n";
				echo  "</TR>\n";
			} else {
				if ($cotizacion_r->fields['descuento']>0) {
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					echo  "<TR>\n"; 
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
					echo  "</TR>\n";
					echo  "<TR>\n"; 
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total-$i_descuento)."</TD>\n";
					echo  "</TR>\n";
				}
			}
			echo  "<TR>\n";
			echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
			echo  "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total</STRONG></TD>\n";
			echo  "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total+$tiva-$i_descuento)."</TD>\n";
			echo  "</TR>\n";

			echo  "</TABLE>\n";

			if ($cotizacion_r->fields['comentario'] != ""){
				echo  "<P><BR></P>";
				echo  "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
				echo  "<TR><TD><STRONG>".translate("COMENTARIOS:")."</STRONG></TD></TR>\n";
				echo  "<TR><TD>".nl2br($cotizacion_r->fields['comentario'])."</TD></TR></TABLE>\n";
			}
			echo  "<P><BR></P>";
		}		
	}	
}
?>
</BODY>
</HTML>