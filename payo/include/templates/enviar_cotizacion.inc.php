<?php
$comentario = $_POST['comentario'];
$descuento = $_POST['descuento'];
$referencia = $_POST['referencia'];
$checkout = $_POST['checkout'];
$total=0;
$i_descuento=0;
$tiva=0;
$enviar_ok = false;
if ($descuento){
	$query = "Select * from e_descuentos where id_descuento=$descuento";
	$desc_r=$db->Execute($query);
	if ($desc_r && !$desc_r->EOF){
		$leyenda=$desc_r->fields['leyenda'];
		$porcentaje=$desc_r->fields['porcentaje'];
		$desc_query = ", descuento=$porcentaje, leyenda_d='{$leyenda}'";
	}
}
$updquery = "UPDATE e_cotiza set estado=2, comentario='$comentario', referencia='$referencia', iva=".$_SESSION['iva']." $desc_query WHERE id_cotiza=$cotizanum AND (estado=0 OR estado=1)";
if ($db->Execute($updquery)){
	if ($_SESSION[nivel]==1){
		if ($_SESSION[iva]>0){
			$updquery = "UPDATE e_cotiza_lineas,e_pproductos SET iva=alicuota, unitario=round(precio2*(1+alicuota/100)*$_SESSION[coef]*$default->descuento,$default->decimales) where codigo_prod=codigo AND id_cot=$cotizanum";
		} else {
			$updquery = "UPDATE e_cotiza_lineas,e_pproductos SET iva=alicuota, unitario=round(precio2*$_SESSION[coef]*$default->descuento,$default->decimales) where codigo_prod=codigo AND id_cot=$cotizanum";
		}
	} else {
		if ($_SESSION[iva]>0){
			$updquery = "UPDATE e_cotiza_lineas,e_pproductos SET iva=alicuota, unitario=round(precio*(1+alicuota/100)*$_SESSION[coef]*$default->descuento,$default->decimales) where codigo_prod=codigo AND id_cot=$cotizanum";
		} else {
			$updquery = "UPDATE e_cotiza_lineas,e_pproductos SET iva=alicuota, unitario=round(precio*$_SESSION[coef]*$default->descuento,$default->decimales) where codigo_prod=codigo AND id_cot=$cotizanum";
		}
	}
	if ($db->Execute($updquery)){
		$enviar_ok = true;
	}
}
if ($enviar_ok){
	$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$cotizanum";
	$cotizacion_r=$db->Execute($query);
	if ($cotizacion_r && !$cotizacion_r->EOF){
		$user_query = "select * from e_webusers where user_id=$id_usuario" ;
		$user_res=$db->Execute($user_query);
		if($user_res && ! $user_res->EOF ){
			$FromName = $user_res->fields['nombres']." ".$user_res->fields['apellidos'];
			$MailFrom = $user_res->fields['email'];
			if ($user_res->fields['razonsocial']) {
				$Empresa =  $user_res->fields['razonsocial'];
			} else {
				$Empresa =  $user_res->fields['nombres']." ".$user_res->fields['apellidos'];
			}
			$Direccion = $user_res->fields['direccion'];
			$Localidad = $user_res->fields['ciudad'];
			$Cod_Postal = $user_res->fields['cp'];
			$Telefonos = $user_res->fields['telefonos'];
			$Mailto = txt_email_to(0,$user_res->fields['vendedor_id']);
			$vendedor=$user_res->fields['vendedor_id'];
			if ($Mailto==''){
				$Mailto=$mail->AddAddress(txt_email_to(2));
			}	
		} else {
		}


		$fin=0;
		$htmlbody .= "<HTML><HEAD>\n";
		$htmlbody .= "<TITLE>.:. PEDIDO .:.</TITLE>\n";
		$htmlbody .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";
		$htmlbody .= "<STYLE TYPE=\"text/css\">\n";

		$htmlbody .= "BODY {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000000;}\n";
		$htmlbody .= "P {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; margin-top: 0px; margin-bottom: 3px; color: #000000;}\n";
		$htmlbody .= "STRONG {font-family: Arial, Helvetica, sans-serif;font-weight: bold;}\n";
		$htmlbody .= "SMALL {font-family: Arial, Helvetica, sans-serif;font-size: 8pt;}\n";
		$htmlbody .= "TD, TH {font-family: Arial, Helvetica, sans-serif;font-size: 9pt;}\n";
		$htmlbody .= ".textobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;}\n";
		$htmlbody .= ".titulobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: normal;}\n";
		$htmlbody .= ".titulobrowses {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: bold;}\n";
		$htmlbody .= "</STYLE>\n";
		$htmlbody .= "</HEAD>\n";
		$htmlbody .= "<BODY BGCOLOR=\"#C7C7C7\">\n";

		$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"2\" BGCOLOR=\"#eeeeee\">\n";
		$htmlbody .= "<TR VALIGN=\"TOP\">";
		$htmlbody .= "<TD BGCOLOR=\"#FFFFFF\" VALIGN=\"TOP\" ALIGN=\"CENTER\">\n";
		$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\">\n";
		$htmlbody .= "<TR>\n";
		$htmlbody .= "<TD BGCOLOR=\"#eeeeee\" ALIGN=\"LEFT\"><IMG SRC=\"logo.gif\" ALT=\"\" BORDER=\"0\"></TD>\n";
		$htmlbody .= "</TR></TABLE><BR>\n";
		$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
		$htmlbody .= "<TR><TD CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">".translate("PEDIDO")." NRO.: ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</TD>";
		$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">".timest2dt($cotizacion_r->fields['fecha'])."</TD></TR>\n";
		$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>".translate("REFERENCIA").": </STRONG>".htmlentities($cotizacion_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD></TR>\n";
		$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>".translate("EMPRESA").": </STRONG>".htmlentities($Empresa,ENT_QUOTES,$default->encode)."</TD></TR>\n";
		$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>".translate("DIRECCION").": </STRONG>".htmlentities($Direccion,ENT_QUOTES,$default->encode)."</TD></TR>\n";
		$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>".translate("LOCALIDAD").": </STRONG>".htmlentities($Localidad,ENT_QUOTES,$default->encode)." - $Cod_Postal</TD></TR>\n";
		$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>".translate("TELEFONOS").": </STRONG>".htmlentities($Telefonos,ENT_QUOTES,$default->encode)."</TD></TR>\n";
		$htmlbody .= "</TABLE>\n";
		$htmlbody .= "<P><BR></P>";

		$fecha = floor(adodb_mktime(0,0,0,substr($cotizacion_r->fields['fecha'],5,2),substr($cotizacion_r->fields['fecha'],8,2),substr($cotizacion_r->fields['fecha'],0,4))- adodb_mktime(0,0,0,12,29,1800))/(60 * 60 * 24);
	} else {
		echo "<P>".translate("Pedido no encontrado")."</P>\n";
		$fin = 1;
	}
	if ($fin == 0){
		$query = "SELECT * FROM e_cotiza_lineas WHERE id_cot=$cotizanum order by descripcion_prod ASC";
		if ($items_r=$db->Execute($query)){
			$htmlbody .= "<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">\n";
			$htmlbody .= "<TR BGCOLOR=\"#eeeeee\">\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">C&oacute;digo</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Descripci&oacute;n</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Marca</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Modelo</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Unidad</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Cantidad</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Precio</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">IVA</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Subtotal</TH>\n";
			$htmlbody .= "</TR>\n";
			if ($items_r->EOF){
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD COLSPAN=\"9\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">".translate("No se encontraron items en el Pedido")."</TD>\n";
				$htmlbody .= "</TR>\n";
			}
			while (!$items_r->EOF){
				$nn++;
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['codigo_prod'],ENT_QUOTES,$default->encode)."</A></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['descripcion_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['marca_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['modelo_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['unidad_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['cantidad']."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".decimales($items_r->fields['unitario'])."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['iva']."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".sprintf("%1.2f",$items_r->fields['cantidad']*$items_r->fields['unitario'])."</TD>\n";
				$htmlbody .= "</TR>\n";
				$total = $total + ($items_r->fields['cantidad']*$items_r->fields['unitario']);
				if ($cotizacion_r->fields[iva]==0){
					$tiva = $tiva + ($items_r->fields['cantidad']*$items_r->fields['unitario']*$items_r->fields['iva']/100);
				}

				$eba_l .= "<ARTICULO>\n";
				$eba_l .= "<N_CONS>".$nn."</N_CONS>\n";
				$eba_l .= "<COD_PROD>".$items_r->fields['codigo_prod']."</COD_PROD>\n";
				$eba_l .= "<PRODUCTO>".$items_r->fields['descripcion_prod']."</PRODUCTO>\n";
				$eba_l .= "<UNIDAD>".$items_r->fields['unidad_prod']."</UNIDAD>\n";
				$eba_l .= "<MARCA>".$items_r->fields['marca_prod']."</MARCA>\n";
				$eba_l .= "<CANTIDAD>".$items_r->fields['cantidad']."</CANTIDAD>\n";
				$eba_l .= "<PRECIO>".round($items_r->fields['unitario'],$default->decimales)."</PRECIO>\n";
				$eba_l .= "<TOTAL>".sprintf("%1.2f",$items_r->fields['cantidad']*round($items_r->fields['unitario'],$default->decimales))."</TOTAL>\n";
				$eba_l .= "<IVA>".$items_r->fields['iva']."</IVA>\n";
				$eba_l .= "<SALIDAS>".""."</SALIDAS>\n";
				$eba_l .= "<DESC_AMP>".""."</DESC_AMP>\n";
				$eba_l .= "<DESC>".""."</DESC>\n";
				$eba_l .= "<CODFAB>".$items_r->fields['codfab']."</CODFAB>\n";
				$eba_l .= "<AIVA>"."0"."</AIVA>\n";
				$eba_l .= "<OIVA>".$items_r->fields['iva']."</OIVA>\n";
				$eba_l .= "<MONEDA_O>"."1"."</MONEDA_O>\n";
				$eba_l .= "<PRECIO_O>".round($items_r->fields['unitario'],$default->decimales)."</PRECIO_O>\n";
				$eba_l .= "<PRE_ORIG>".round($items_r->fields['unitario'],$default->decimales)."</PRE_ORIG>\n";
				$eba_l .= "<COEF>"."1"."</COEF>\n";
				$eba_l .= "<MODELO>".$items_r->fields['modelo_prod']."</MODELO>\n";
				$eba_1 .= "<MARCA_C>"."0"."</MARCA_C>\n";
				$eba_l .= "</ARTICULO>\n";
				$items_r->MoveNext();
			}
			if ($cotizacion_r->fields['iva']==0){
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total)."</TD>\n";
				$htmlbody .= "</TR>\n";
				if ($cotizacion_r->fields['descuento']>0){
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total-$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
				}
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total IVA</STRONG></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".decimales($tiva)."</TD>\n";
				$htmlbody .= "</TR>\n";
			} else {
				if ($cotizacion_r->fields['descuento']>0) {
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".decimales($i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".decimales($total-$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
				}
			}
			$htmlbody .= "<TR>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total</STRONG></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".decimales($total+$tiva-$i_descuento)."</TD>\n";
			$htmlbody .= "</TR>\n";

			$htmlbody .= "</TABLE>\n";

			if ($cotizacion_r->fields['comentario'] != ""){
				$htmlbody .= "<P><BR></P>";
				$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
				$htmlbody .= "<TR><TD CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">".translate("COMENTARIOS")."</TD></TR>\n";
				$htmlbody .= "<TR><TD>".nl2br($cotizacion_r->fields['comentario'])."</TD></TR></TABLE>\n";
			}
			$htmlbody .= "<P><BR></P>";
			$htmlbody .= "</BODY></HTML>\n";
			
			$eba = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($default->o_venta){
				$eba .= "<ORDENDEVENTA>\n";
			} else {
				$eba .= "<PRESUPUESTO>\n";
			}	
			$eba .= "<CABECERA>\n";
			$eba .= "<COD_CLIENTE>".$user_res->fields['eb_cod']."</COD_CLIENTE>\n";
			$eba .= "<RAZON_SOCIAL>".$user_res->fields['razonsocial']."</RAZON_SOCIAL>\n";
			$eba .= "<CUIT>".$user_res->fields['cuit']."</CUIT>\n";
			$eba .= "<FECHA>".$fecha."</FECHA>\n";
			$eba .= "<O_CPRA>"."WWW: ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</O_CPRA>\n";
			$eba .= "<VENDEDOR>".$user_res->fields['vendedor_id']."</VENDEDOR>\n";
			$eba .= "<OBRA>".""."</OBRA>\n";
			$eba .= "<USUARIO>".""."</USUARIO>\n";
			$eba .= "<COND_VTA>"."0"."</COND_VTA>\n";
			$eba .= "<REFERENCIA>".$cotizacion_r->fields['referencia']."</REFERENCIA>\n";
			$eba .= "<PARCIAL>".sprintf("%01.2f",$total)."</PARCIAL>\n";
			$eba .= "<PDESC>".$descuento."</PDESC>\n";
			$eba .= "<IDESC>".decimales($i_descuento)."</IDESC>\n";
			$eba .= "<SUBTOT>".decimales($total-$i_descuento)."</SUBTOT>\n";
			$eba .= "<PIVA>"."0"."</PIVA>\n";
			$eba .= "<IIVA>".decimales($tiva)."</IIVA>\n";
			$eba .= "<PIVA2>"."0.00"."</PIVA2>\n";
			$eba .= "<IIVA2>"."0.00"."</IIVA2>\n";
			$eba .= "<TOTALG>".decimales($total+$tiva-$i_descuento)."</TOTALG>\n";
			$eba .= "<OPERADOR>".""."</OPERADOR>\n";
			$eba .= "<GRB>"."1"."</GRB>\n";
			$eba .= "<COMIS_O>"."0"."</COMIS_O>\n";
			$eba .= "<COMIS_R>"."0"."</COMIS_R>\n";
			$eba .= "<COMIS_P>"."0"."</COMIS_P>\n";
			$eba .= "<MONEDA_ID>"."1"."</MONEDA_ID>\n";
			$eba .= "<COTIZACION>"."1"."</COTIZACION>\n";
			$eba .= "<COEF>"."1"."</COEF>\n";
			$eba .= "<DES_ID>"."0"."</DES_ID>\n";
			$eba .= "<MARCADA>"."0"."</MARCADA>\n";
			$eba .= "<AUTORIZO>".""."</AUTORIZO>\n";
			$eba .= "<COMENTARIO>".$cotizacion_r->fields['comentario']."</COMENTARIO>\n";
			$eba .= "</CABECERA>\n";

			$eba .= "<ARTICULOS>\n";
			$eba .= $eba_l;
			$eba .= "</ARTICULOS>\n";
			if ($default->o_venta){
				$eba .= "</ORDENDEVENTA>\n";
			} else {
				$eba .= "</PRESUPUESTO>\n";
			}

			
			$updquery = "UPDATE e_cotiza set subtotal=$total, tdescuento=".$i_descuento.", tiva=$tiva, total=".($total+$tiva-$i_descuento)." WHERE id_cotiza=$cotizanum";
			$updcotiza_r=$db->Execute($updquery);

			
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->AddEmbeddedImage("image/logo.gif", "logo.gif", "logo.gif");
			$mail->From = $MailFrom;
			$mail->FromName = $FromName;
			$mail->Mailer = "mail";
			$mail->Subject = "Pedido";
			$mail->AltBody="Debe habilitar html para ver este mensaje";
			$mail->Body = $htmlbody;
			$mail->AddAddress($Mailto);
			$mail->AddBCC($MailFrom);
			$mail->AddReplyTo($MailFrom);
			$mail->AddStringAttachment($eba, "WWW".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT).".eba");
			$mail->Send();
			$mail->ClearAddresses();
			$mail->ClearReplyTos();
			

			
			
			
		} else {
			echo "<P>ERROR: Al conectarse a la base de datos</P>";
		}
	}
} else {
	echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>&nbsp;Ocurrio un error al enviar el Pedido</div>\n";
	
}	
?>
