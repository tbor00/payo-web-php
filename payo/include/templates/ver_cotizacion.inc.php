<?php
$total = 0;
$tiva = 0;
$i_descuento = 0;
$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$cotizanum AND user_id=$id_usuario";
$cotizacion_r=$db->Execute($query);
if ($cotizacion_r && !$cotizacion_r->EOF){
	$user_query = "select * from e_webusers where user_id=$id_usuario" ;
	$user_res=$db->Execute($user_query);
	if($user_res && ! $user_res->EOF ){
		$cliente_eb=$user_res->fields['eb_cod'];
		$tipo_pago=$user_res->fields['pago'];	
	}	
	echo "<div class=\"row\">\n";
	echo "<div id=\"content\" class=\"col-sm-12\">\n";
	echo "<div class=\"row\">";								  
	echo "<span class=\"col-sm-12 text-right\">";
	echo "<A HREF=\"auxiliar.php?op=oprint&cotizanum=$cotizanum\" TARGET=\"_print\"  class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Versi&oacute;n para Imprimir\"><i class=\"fa fa-print\"></i></A>";
	echo "<A HREF=\"auxiliar.php?op=oxls_list&cotizanum=$cotizanum\" TARGET=\"_print\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Exportar\"><i class=\"fa fa-file-excel-o\"></i></A>";
	echo "</span>";
	echo "</div>";
	$estado = $cotizacion_r->fields['estado'];
	$civa = $cotizacion_r->fields['iva'];
	$referencia = $cotizacion_r->fields['referencia'];
	$comentario = $cotizacion_r->fields['comentario'];
	if ($estado==0 || $estado==1){
		echo "<FORM NAME=\"sendform\" id=\"sendform\" class=\"form-horizontal\" METHOD=\"POST\" ACTION=\"".$_SERVER[PHP_SELF]."?op=$op&sop=$sop\">\n";
	} else {
		echo "<div class=\"form-horizontal\">\n";
	}
	echo "<fieldset id=\"pedido-detail\">\n";
	echo "<legend>Detalles del Pedido</legend>\n";
	
	echo "<div class=\"form-group\">\n";
	echo "<label class=\"col-sm-1 control-label\"><strong>N&uacute;mero:</strong></label>\n";
	echo "<div class=\"col-sm-4\">";
	echo "<span class=\"form-control-readonly\">".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</span>";
	echo "</div></div>\n";
	
	
	echo "<div class=\"form-group\">\n";
	echo "<label class=\"col-sm-1 control-label\"><strong>Fecha:</strong></label>\n";
	echo "<div class=\"col-sm-4\">";
	echo "<span class=\"form-control-readonly\">".timest2dt($cotizacion_r->fields['fecha'])."</span>";
	echo "</div></div>\n";


	if ($estado==0 || $estado==1){
		echo "<div class=\"form-group\">\n";
		echo "<label class=\"col-sm-1 control-label\" for=\"input-referencia\"><strong>Referencia:</strong></label>\n";
		echo "<div class=\"col-sm-5\">";
		echo "<input type=\"text\" name=\"referencia\" value=\"".htmlentities($referencia,ENT_QUOTES,$default->encode)."\" maxlength='50' placeholder=\"Completar referencia\" id=\"input-referencia\" class=\"form-control\" />";
		echo "</div></div>\n";
	} else {
		echo "<div class=\"form-group\">\n";
		echo "<label class=\"col-sm-1 control-label\"><strong>Referencia:</strong></label>\n";
		echo "<div class=\"col-sm-5\">";
		echo "<span class=\"form-control-readonly\">".htmlentities($cotizacion_r->fields['referencia'],ENT_QUOTES,$default->encode)."</span>";
		echo "</div></div>\n";
	}
	echo "</fieldset>\n";

	echo "<BR>\n";
} else {
	echo "<P>".translate("Pedido no encontrado")."</P>\n";
	$fin = 1;
}

if ($fin == 0){
	
	if ($action=="delete"){
		$query = "DELETE FROM e_cotiza_lineas WHERE id_cot=$cotizanum AND id_lin=$itemnum";
		$delete_r = $db->Execute($query);
	} elseif ($action=="edit"){
		$cantidad = $_POST['quantity'];
		$query = "UPDATE e_cotiza_lineas set cantidad=$cantidad WHERE id_cot=$cotizanum AND id_lin=$itemnum";
		$delete_r = $db->Execute($query);
	}
	
	$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$cotizanum $ORDER";
	if ($items_r=$db->Execute($query)){
		if ($estado == 0 || $estado==1){
			echo "<script type=\"text/javascript\">";
			echo "function DeleteItem(itemnum){";
			echo "var lineas=2;";
			echo "if (lineas > 1){";
			echo "jConfirm('".translate("Desea eliminar el Item")."?', 'Eliminar Item', function(r) {";
			echo "if(r) {";
			echo "url = \"".$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&cotizacion=show&cotizanum=$cotizanum&action=delete&itemnum=\" + itemnum ;";
			echo "\t document.location=url;";
			echo "}";
			echo "});";
			echo "}} ";
			echo "function SaveCot(){";
			echo "var lineas=2;";
			echo "if (lineas > 1){";
			echo "jConfirm('".translate("Desea guardar el pedido")."?', 'Guardar Pedido', function(r) {";
			echo "if(r) {";
			echo "document.getElementById(\"action\").value=\"draft\";";
			echo "document.getElementById(\"cotizacion\").value=\"save\";";
			echo "document.getElementById(\"sendform\").submit();";
			echo "}";
			echo "});";
			echo "}}";
			echo "</script>";
		}

		echo "<script type=\"text/javascript\">\n";
		echo "$(window).bind(\"load\", function() {\n";
		echo "setTimeout(function () {\n";
		echo "$('#cart > button').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ ".$cotizanum." );\n";				
		echo "}, 100);\n";
		echo "$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+".$cotizanum." +' ul li');\n";
 		echo "});\n";
		echo "</script>\n";


		echo "<div class=\"table-responsive\">";

		echo "<table class=\"table table-bordered table-hover\">\n";
		echo "<thead>\n";
		echo "<tr>\n";
		if ($estado == 0 || $estado==1){
			echo "<td class=\"text-center\"></td>\n";
		}
		echo "<td class=\"text-left\"><strong>C&oacute;d</strong></td>\n";
		echo "<td class=\"text-left\"><strong>Descripci&oacute;n</strong></td>\n";
		if ($estado == 0 || $estado==1){
			echo "<td class=\"text-center\"><strong>Stock</strong></td>\n";
		}
		echo "<td class=\"text-left\"><strong>Marca</strong></td>\n";
		echo "<td class=\"text-left\"><strong>Modelo</strong></td>\n";
		echo "<td class=\"text-left\"><strong>C&oacute;d Fab</strong></td>\n";
		echo "<td class=\"text-center\"><strong>Cantidad</strong></td>\n";
		echo "<td class=\"text-center\"><strong>Precio</strong></td>\n";
		echo "<td class=\"text-center\"><strong>IVA %</strong></td>\n";
		echo "<td class=\"text-center\"><strong>Subtotal</strong></td>\n";
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";

	
		if ($items_r->EOF){
			echo "<tr>\n";
			echo "<td colspan=\"10\" align=\"center\" class=\"textobrowse\">".translate("No se encontraron Articulos en su Pedido")."</TD>\n";
			echo "</tr>\n";
		}
		while (!$items_r->EOF){
			echo "<TR>\n";
			if ($estado == 0 || $estado ==1){
				echo "<td class=\"text-center\">";
				echo "<button type=\"button\" onclick=\"javascript:DeleteItem('".$items_r->fields['id_lin']."')\" title=\"Eliminar\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></button>\n";
				//echo "<button type=\"button\" onclick=\"javascript:EditItem('".$items_r->fields['id_lin']."')\" title=\"Editar\" class=\"btn btn-success btn-xs\"><i class=\"fa fa-pencil-square-o\"></i></button>\n";
				echo "<a href=\"auxiliar.php?op=productos&idp=".$items_r->fields['codigo_prod']."&id_lin=".$items_r->fields['id_lin']."&cotizanum=".$cotizanum."&nc=on&sop=$sop&oop=$op\" id=\"edit_product\" title=\"Editar\" class=\"btn btn-success btn-xs\"><i class=\"fa fa-pencil-square-o\"></i></a>\n";
				echo "</TD>\n";
			}
			echo "<td class=\"text-left\">".$items_r->fields['codigo_prod']."</td>\n";
			echo "<TD class=\"text-left\"><A CLASS=\"view_product\" HREF=\"auxiliar.php?op=productos&nc=off&idp=".$items_r->fields['codigo_prod']."\">".htmlentities($items_r->fields['descripcion_prod'],ENT_QUOTES,$default->encode)."</a>";
			echo "</td>\n";
			if ($estado == 0 || $estado == 1){
				echo "<TD class=\"text-right\">";
				if ($items_r->fields['stock'] > 0) {
					echo htmlentities(sprintf('%01.0f',$items_r->fields['stock']),ENT_QUOTES,$default->encode);
				} else {
					$sinstock=1;
					echo "7 D&iacute;as";
				}
				echo "</td>\n";
			}	
			echo "<TD class=\"text-left\">".htmlentities($items_r->fields['marca_prod'],ENT_QUOTES,$default->encode)."</td>\n";
			echo "<TD class=\"text-left\">".htmlentities($items_r->fields['modelo_prod'],ENT_QUOTES,$default->encode)."</td>\n";
			echo "<TD class=\"text-left\">".htmlentities($items_r->fields['codfab'],ENT_QUOTES,$default->encode)."</td>\n";
			echo "<TD class=\"text-right\">".htmlentities($items_r->fields['cantidad'],ENT_QUOTES,$default->encode)."</td>\n";
			if ( $estado==2){
				echo "<TD class=\"text-right\">$".$items_r->fields['unitario']."</TD>\n";
				echo "<TD class=\"text-right\">".$items_r->fields['iva']."</TD>\n";
				echo "<TD class=\"text-right\">$".sprintf("%01.2f",$items_r->fields['cantidad']*$items_r->fields['unitario'])."</TD>\n";
				$total = $total + ($items_r->fields['cantidad']*$items_r->fields['unitario']);
				if ($civa==0){
					$tiva = $tiva + ($items_r->fields['cantidad']*$items_r->fields['unitario']*$items_r->fields['iva']/100);
				}
			} else {
				if ($_SESSION[nivel]==1){
					$loc_precio=$items_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
				} else {
					$loc_precio=$items_r->fields['precio']*$_SESSION['coef']*$default->descuento;
				} 
				if ($_SESSION[iva]>0){
					$loc_precio = $loc_precio * (1+($items_r->fields['alicuota']/100));	
				}
				$loc_precio = round($loc_precio,$default->decimales);
				echo "<TD class=\"text-right\">$".decimales($loc_precio)."</TD>\n";
				echo "<TD class=\"text-right\">".$items_r->fields['alicuota']."</TD>\n";
				echo "<TD class=\"text-right\">$".sprintf("%01.2f",$items_r->fields['cantidad']*$loc_precio)."</TD>\n";
				$total = $total + ($items_r->fields['cantidad']*$loc_precio);
				if ($_SESSION[iva]>0){
					$tiva = 0;
				} else {
					$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
				}
			}			    
			echo "</tr>\n";
			$items_r->MoveNext();
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "</div>\n";
		if ($estado == 0 || $estado == 1){
			echo "<input type=\"hidden\" name=\"subtotal\" id=\"subtotal\" value=\"".sprintf("%01.2f",$total)."\">\n";
			echo "<input type=\"hidden\" name=\"iva\" id=\"iva\" value=\"".sprintf("%01.2f",$tiva)."\">\n";
			echo "";
			echo "";
			$query = "SELECT * from e_descuentos where activo=1 and nivel=".$_SESSION[nivel]." order by porcentaje ASC";
			if ($desc_r=$db->Execute($query)){
				while (!$desc_r->EOF){
					$desc_a[] = array(
						'leyenda'    => $desc_r->fields['leyenda'],
						'porcentaje' => $desc_r->fields['porcentaje'],
						'id_descuento' => $desc_r->fields['id_descuento'],
						'tarjeta' => $desc_r->fields['tarjeta'],
						'cuotas' => $desc_r->fields['cuotas'],
					);
					$desc_r->MoveNext();
				}
			}
		}

		if (sizeof($desc_a)>0 && (estado==0 || $estado==1)){
			echo "<script>";
			echo "$(document).ready(function(){";
			echo "$('#sendform').change(function(){";
			echo "var desc_a = [];";
			echo "desc_a[0]=0;";
			foreach ($desc_a as $descu) {
				echo "desc_a[".$descu['id_descuento']."]=".$descu['porcentaje'].";";
			}			
			echo "var subtotal = $('#subtotal').val();";
			echo "var desc_id = $('[name=\"descuento\"]:checked').val();";
			echo "var porcentaje = desc_a[desc_id];";
			echo "var iva = $('#iva').val();";
			echo "var descuento_c = ((subtotal * porcentaje) / 100).toFixed(2);";
			echo "var iva_c = (iva * (1-(porcentaje/100))).toFixed(2);";
			echo "var subtotal_c = (subtotal - descuento_c).toFixed(2);"; 
			echo "var total_c = ((subtotal - descuento_c)+(iva * (1-(porcentaje/100)))).toFixed(2);"; 
			echo "$('#descuento_c').html(descuento_c);";
			if ($_SESSION[iva]==0){
				echo "$('#subtotal_c').html(subtotal_c);";
				echo "$('#iva_c').html(iva_c);";
			}
			echo "$('#total_c').html(total_c);";
			echo "});";
			echo "});";
			echo "</script>";
		}


		echo "<div class=\"row\">\n";
		echo "<div class=\"col-sm-5 col-sm-offset-7\">\n";
		echo "<table class=\"table table-bordered\">\n";
		echo "<tbody>\n";
		if ($_SESSION[iva]==0 || $civa==0){
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
			echo "<td class=\"text-right\">$".sprintf("%01.2f",$total)."</td>\n";
			echo "</tr>\n";
			if ($estado==0 || $estado==1){
				if (sizeof($desc_a)>0){
					echo "<tr>\n";
					echo "<td class=\"text-right\">";
					echo "<strong>Sin descuento </strong>";
					echo "<input type=\"radio\" name=\"descuento\" value=\"0\" checked=\"checked\" /><br>\n";
					foreach ($desc_a as $descu) {
						echo "<strong>".$descu['leyenda'];
						if ($descu['porcentaje']>0){
							echo " (".$descu['porcentaje']."%) ";
						}
						if ($descu['tarjeta'] && $descu['cuotas']){
							echo " - <a class=\"cuotas_lnk\" href=\"auxiliar.php?op=cuotas&idt=".$descu['id_descuento']."\" title=\"C&aacute;lculo de cuotas ".$descu['leyenda']."\">Hasta ".$descu['cuotas']." cuotas</a> ";
						}
						echo " </strong>";
						echo "<input type=\"radio\" name=\"descuento\" value=\"".$descu['id_descuento']."\" /><br>";
				   }
					echo "</td>\n";
					echo "<td class=\"text-right\">$<span id=\"descuento_c\">0.00</span></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
					echo "<td class=\"text-right\">$<span id=\"subtotal_c\">".sprintf("%01.2f",$total)."</span></td>\n";
					echo "</tr>\n";
				}
	  		} else {
				if ($cotizacion_r->fields['descuento']>0){
					$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
					$subtotal = $total-($total*$cotizacion_r->fields['descuento']/100);
					$i_descuento = ($total*$cotizacion_r->fields['descuento']/100);
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>".$cotizacion_r->fields['leyenda_d'];
					if ($cotizacion_r->fields['descuento'] > 0) {
						" (".$cotizacion_r->fields['descuento']."%)";
					}
					echo "</strong></td>\n";
					echo "<td class=\"text-right\">$<span id=\"descuento_c\">".sprintf("%01.2f",$i_descuento)."</span></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
					echo "<td class=\"text-right\">$<span id=\"subtotal_c\">".sprintf("%01.2f",$subtotal)."</span></td>\n";
					echo "</tr>\n";
				}
			}
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Total IVA</strong></td>\n";
			echo "<td class=\"text-right\">$<span id=\"iva_c\">".sprintf("%01.2f",$tiva)."</span></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Total</strong></td>\n";
			echo "<td class=\"text-right\">$<span id=\"total_c\">".sprintf("%01.2f",$total+$tiva-$i_descuento)."</span></td>\n";
			echo "</tr>\n";

		} else {
			if ($estado==0 || $estado==1){
				if (sizeof($desc_a)>0){
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
					echo "<td class=\"text-right\">$".sprintf("%01.2f",$total+$tiva)."</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "<td class=\"text-right\">";
					echo "<strong>Sin descuento </strong>";
					echo "<input type=\"radio\" name=\"descuento\" value=\"0\" checked=\"checked\" /><br>\n";
					foreach ($desc_a as $descu) {
						echo "<strong>".$descu['leyenda'];
						if ($descu['porcentaje']>0){
							echo " (".$descu['porcentaje']."%) ";
						}
						if ($descu['tarjeta'] && $descu['cuotas']){
							echo " - <a class=\"cuotas_lnk\" href=\"auxiliar.php?op=cuotas&idt=".$descu['id_descuento']."\" title=\"C&aacute;lculo de cuotas ".$descu['leyenda']."\">Hasta ".$descu['cuotas']." cuotas</a> ";
						}
						echo " </strong>";
						echo "<input type=\"radio\" name=\"descuento\" value=\"".$descu['id_descuento']."\" /><br>";
				   }
					echo "</td>\n";
					echo "<td class=\"text-right\">$<span id=\"descuento_c\">0.00</span></td>\n";
					echo "</tr>\n";
				}
	  		} else {
				if ($cotizacion_r->fields['descuento']>0){
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</strong></td>\n";
					echo "<td class=\"text-right\">$<span id=\"descuento_c\">".sprintf("%01.2f",$i_descuento)."</span></td>\n";
					echo "</tr>\n";
				}
			}
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Total</strong></td>\n";
			echo "<td class=\"text-right\">$<span id=\"total_c\">".sprintf("%01.2f",$total+$tiva-$i_descuento)."</span></td>\n";
			echo "</tr>\n";
		}
		echo "</tbody>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</div>\n";

		

		if (($estado == 0 || $estado == 1 )&& $total > 0){
			if ($sinstock > 0){
				echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> ".section_text("NOSTOCKPED",0)."</div>\n";
			}
			echo "<span><strong>A&ntilde;adir comentarios a su pedido</strong></span>\n\n";
			echo "<p><TEXTAREA NAME=\"comentario\" ROWS=\"5\" COLS=\"60\" class=\"form-control\">".nl2br($comentario)."</TEXTAREA></p>\n";
			echo "<div class=\"buttons\">\n";
			echo "<div class=\"pull-left\"><a href=\"".$_SERVER['PHP_SELF']."?op=$op\" class=\"btn btn-default\"><i class=\"fa fa-close\"></i> Cancelar</a></div>\n";
			echo "<div class=\"pull-right\">\n";
			if ($tipo_pago==1){
				echo "<button type=\"button\" title=\"Pagar\" onclick=\"PayPedido($cotizanum,".sprintf("%01.2f",$total+$tiva-$i_descuento).")\" class=\"btn btn-primary\"><i class=\"fa fa-money\"></i> Pagar</button>\n";
			} else {
				echo "<button type=\"submit\" title=\"Enviar\" class=\"btn btn-primary\"><i class=\"fa fa-envelope\"></i> Enviar</button>\n";
			}
	
			
			//echo "<input type=\"submit\" value=\"Enviar\" class=\"btn btn-primary\" />\n";
  		    echo "<button type=\"button\" onclick=\"javascript:SaveCot()\" title=\"Guardar\" class=\"btn btn-primary\"><i class=\"fa fa-archive\"></i> Guardar</button>\n";

			echo "</div>\n";
			echo "</div>\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"action\" id=\"action\" VALUE=\"send\">\n";
			echo "<input type=\"HIDDEN\" name=\"cotizacion\" id=\"cotizacion\" value=\"show\">\n";
			echo "<input type=\"HIDDEN\" name=\"cotizanum\" id=\"cotizanum\" value=\"$cotizanum\">\n";
			echo "</FORM>\n";
		} else {
			if (strlen($comentario) > 0){
				echo "<span><strong>Comentarios del Pedido</strong></span>\n";
				echo "<div class=\"col-sm-12 form-control-readonly\">".nl2br($comentario)."</div>\n";
				echo "<div><span>&nbsp;</span></div>";
			}
			echo "<div class=\"buttons\">\n";
			echo "<div class=\"pull-left\"><a href=\"".$_SERVER['PHP_SELF']."?op=$op\" class=\"btn btn-default\">Volver</a></div>\n";
			echo "</div>\n";
			echo "</div>\n";
		}

		echo "</div>\n";
		echo "</div>\n";

	} else {
		echo "<P>ERROR: Al conectarse a la base de datos</P>";
	}
}
?>