<?php
echo "<div class=\"row\">";
echo "<div class=\"col-sm-12\">";
echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\">\n";
//if ($_SESSION[logged]){
	if ($_POST['idp']!=''){
		$idp = $_POST['idp'];			  
		$no_cart = $_POST['nc'];			  
		$itemnum = $_POST['id_lin'];			  
		$cotizanum = $_POST['cotizanum'];			  
	} elseif ($_GET['idp']!=''){
		$idp = $_GET['idp'];
		$no_cart = $_GET['nc'];			  
		$itemnum = $_GET['id_lin'];			  
		$cotizanum = $_GET['cotizanum'];			  
		$nop = $_GET['oop'];			  
		$nop = $_GET['sop'];			  
	}
	$id_usuario=$_SESSION['uid'];
	$db = connect();
	$db->debug = SDEBUG;
	$det_producto=$db->Execute("SELECT * FROM e_pproductos WHERE codigo=?",array($idp));
	if ($det_producto && !$det_producto->EOF){
		echo "<TR>\n";
		if ($_SESSION[logged]){
			$r_favorito = $db->Execute("SELECT COUNT(id_user) FROM e_prod_favoritos WHERE id_user=$id_usuario AND cod_prod='$idp'");
			if ($r_favorito && !$r_favorito->EOF){
				$estrella = $r_favorito->fields[0];
			}
			echo "<TD CLASS=\"text-right\">";
			if ($estrella > 0){
				echo "<span id=\"wishlist\"><button type=\"button\" data-toggle=\"tooltip\" title=\"Eliminar de Favoritos\" onclick=\"wishlist.remove('".$idp."');\"><i class=\"fa fa-star\" style=\"color:orange\"></i></button></span>";
			} else {
				echo "<span id=\"wishlist\"><button type=\"button\" data-toggle=\"tooltip\" title=\"Agregar a Favoritos\" onclick=\"wishlist.add('".$idp."');\"><i class=\"fa fa-star\"></i></button></span>";
			}
			echo "</TD>";
		}
		echo "</TR>\n";
		echo "<TR><TD CLASS=\"text-left\">";
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"2\">";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Producto:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
//		echo "<TD CLASS=\"text-left\"><STRONG>C&oacute;digo:</STRONG></TD>";
//		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
//		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['codigo'],ENT_QUOTES,$default->encode)."</TD>\n";	
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Modelo:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['modelo'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Tipo:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['tipo'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Unidad:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['unidad'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>C&oacute;d. F&aacute;brica:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['codfab'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Origen:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['origen'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Marca:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['marca'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Unidades x Caja:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['unven'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Stock:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">";
		if ($det_producto->fields['stock'] > 0) {
			echo htmlentities(sprintf('%01.0f',$det_producto->fields['stock']),ENT_QUOTES,$default->encode);
		} else {
			echo "7 D&iacute;as";
		}
		echo "</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Precio:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		if ($_SESSION[nivel]==1){
			$precio = $det_producto->fields['precio2']*$_SESSION['coef']*$default->descuento;
		} else {
			$precio = $det_producto->fields['precio']*$_SESSION['coef']*$default->descuento;
		}
		if ($_SESSION[iva]>0){
			$precio = $precio * (1+($det_producto->fields['alicuota']/100));	
		}
		echo "<TD CLASS=\"text-left\"><STRONG>".$det_producto->fields['moneda']." ".decimales($precio)."</STRONG></TD>\n";
		echo "</TR>";
		if ($det_producto->fields['observ']!=''){
		    echo "<TR>";
          echo "<TD CLASS=\"text-left\"><STRONG></STRONG></TD>";
          echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
          echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['observ'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		}
		echo "</TABLE>";
		echo "</TD>";
		echo "</TR>";

		echo "<TR>";
		echo "<TD>";

		echo "<TABLE WIDTH=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"2\" BORDER=\"0\" BGCOLOR=\"#FFFFFF\">";
		if ($det_producto->fields['imagen']!=''){
			$p_image = similar_file_exists("products/imagenes/".$det_producto->fields['imagen']);
		} else {
			$p_image = '';
		}
		if ($p_image!='') {
		   $partes_ruta = pathinfo($p_image);
			if (file_exists("products/miniaturas/".strtolower($partes_ruta['filename'])."-234x124.".strtolower($partes_ruta['extension']))){
				$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-234x124.".strtolower($partes_ruta['extension']);
			}else{
				convert_image($p_image,"products/miniaturas/".strtolower($partes_ruta['filename'])."-234x124.".strtolower($partes_ruta['extension']),"234x124","80");
				$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-234x124.".strtolower($partes_ruta['extension']);
			}
			echo "<TR>";
			echo "<TD CLASS=\"text-center\" COLSPAN=\"3\">";
			echo "<IMG SRC=\"".$t_image."\" BORDER=\"0\"";
			echo ">";
			echo "</TD>";
			echo "</TR>";
		}else{
			echo "<TR>";
			echo "<TD CLASS=\"text-center\" COLSPAN=\"3\">";
			echo "<IMG width=\"234\" height=\"124\" SRC=\"image/blank.gif\" BORDER=\"0\"";
			echo ">";
			echo "</TD>";
			echo "</TR>";
		}
		echo "</TABLE></TD></TR>";
		echo "<TR><TD><div id=\"alert-zone\"><P>&nbsp;</P><span></span></div><TD></TR>";
		if ($_SESSION['logged']){
			if ($no_cart == ""){
				echo "<tr>\n";
				echo "<td class=\"text-center\">";
				echo "<div class=\"col-sm-4\">";
				echo "</div>";
				echo "<div class=\"col-sm-4 text-center\">";
				echo "<div id=\"product\" class=\"input-group btn-block\" style=\"max-width: 200px; align:center\">\n";
				echo "<input type=\"number\" name=\"quantity\" value=\"\" size=\"2\" id=\"input-quantity\" class=\"form-control\" />\n";
				echo "<input type=\"hidden\" name=\"product_id\" value=\"$idp\" />\n";
				echo "<span class=\"input-group-btn\">\n";
				echo "<button type=\"button\" id=\"button-cart\" data-loading-text=\"Cargando...\" class=\"btn btn-primary\"><i class=\"fa fa-shopping-cart\"></i> Agregar</button>\n";
				echo "</span>\n";
				echo "</div>\n";
				echo "</div>";
				echo "<div class=\"col-sm-4\">";
				echo "</div>";
				echo "</td></tr>\n";
			} elseif ($no_cart<>'off' && $no_cart<>""){
				$query = "SELECT * FROM e_cotiza_lineas WHERE id_cot=$cotizanum AND id_lin=$itemnum";	
				$cotizal_r=$db->Execute($query);
				echo "<tr>\n";
				echo "<td class=\"text-center\">";
				echo "<div class=\"col-sm-4\">";
				echo "</div>";
				echo "<form method=\"post\" action=\"index.php?op=$oop&sop=$sop&cotizacion=show&itemnum=$itemnum&cotizanum=$cotizanum&action=edit\">\n";
				echo "<div class=\"col-sm-4 text-center\">";
				echo "<div id=\"product\" class=\"input-group btn-block\" style=\"max-width: 200px; align:center\">\n";
				echo "<input type=\"number\" name=\"quantity\" min=\"1\" step=\"1\" value=\"".$cotizal_r->fields['cantidad']."\" size=\"3\" id=\"input-quantity\" class=\"form-control\" />\n";
				echo "<input type=\"hidden\" name=\"product_id\" value=\"$idp\" />\n";
				echo "<span class=\"input-group-btn\">\n";
				echo "<button type=\"submit\" id=\"button-cart-edit\" data-loading-text=\"Cargando...\" class=\"btn btn-primary\"><i class=\"fa fa-shopping-cart\"></i> Modificar</button>\n";
				echo "</span>\n";
				echo "</div>\n";
				echo "</div>";
				echo "</form>\n";
				echo "<div class=\"col-sm-4\">";
				echo "</div>";
				echo "</td></tr>\n";
			}
		}
	} else {
		echo "<TR><TD>ERROR: Producto no encontrado.</TD></TR>";
	}
//}
echo "</TABLE>\n";
echo "</div></div>";
?>
