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
		
		$rank_producto=$db->Execute("SELECT * FROM e_pprod_rank WHERE cod_prod=?",array($idp));
		if ($rank_producto && !$rank_producto->EOF){
			$rank_producto=$db->Execute("UPDATE e_pprod_rank set vistas=vistas+1 WHERE cod_prod=?",array($idp));
		} else {
			$rank_producto=$db->Execute("INSERT INTO e_pprod_rank (cod_prod,vistas) VALUES(?,1)",array($idp));
		}
		echo "<TR>\n";
		echo "<TD CLASS=\"text-right\">";
		if ($_SESSION[logged]){
			$r_favorito = $db->Execute("SELECT COUNT(id_user) FROM e_prod_favoritos WHERE id_user=$id_usuario AND cod_prod='$idp'");
			if ($r_favorito && !$r_favorito->EOF){
				$estrella = $r_favorito->fields[0];
			}
			if ($estrella > 0){
				echo "<span id=\"wishlist\"><button class=\"btn\" type=\"button\" data-toggle=\"tooltip\" title=\"Eliminar de Favoritos\" onclick=\"wishlist.remove('".$idp."');\"><i class=\"fa fa-star\" style=\"color:orange\"></i></button></span>";
			} else {
				echo "<span id=\"wishlist\"><button class=\"btn\" type=\"button\" data-toggle=\"tooltip\" title=\"Agregar a Favoritos\" onclick=\"wishlist.add('".$idp."');\"><i class=\"fa fa-star\"></i></button></span>";
			}
		}
		?>
	<span class="nav pull-right">
      <ul class="list-inline">
      <li class="dropdown"><a href= "" title="Compartir" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share"></i></a>
          <ul class="dropdown-menu dropdown-menu-<?php echo ($default->is_mobile ? "right" : "left")?>">
			<li><a href="javascript:void();" data-sharer="facebook" data-title="<?php echo $default->web_title." ".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>" data-url="<?php echo make_absoluteURI("index.php?op=productos&idp=".$idp); ?>"><i class="fa fa-facebook"></i>&nbsp;&nbsp;Facebook</a></li>
            <li><a href="javascript:void();" data-sharer="twitter" data-title="<?php echo $default->web_title." ".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>" data-url="<?php echo make_absoluteURI("index.php?op=productos&idp=".$idp); ?>"><i class="fa fa-twitter"></i>&nbsp;&nbsp;Twitter</a></li>
            <li><a href="javascript:void();" data-sharer="linkedin" data-title="<?php echo $default->web_title." ".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>" data-url="<?php echo make_absoluteURI("index.php?op=productos&idp=".$idp); ?>"><i class="fa fa-linkedin"></i>&nbsp;&nbsp;Linkedin</a></li>
			<li><a href="javascript:void();" data-sharer="whatsapp" data-title="<?php echo $default->web_title." ".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>" data-url="<?php echo make_absoluteURI("index.php?op=productos&idp=".$idp); ?>" <?php echo ( $default->is_mobile ? "" : "data-web" )?>><i class="fa fa-whatsapp"></i>&nbsp;&nbsp;Whatsapp</a></li>
			<li><a href="javascript:void();" data-sharer="email" data-title="<?php echo $default->web_title." ".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>" data-url="<?php echo make_absoluteURI("index.php?op=productos&idp=".$idp); ?>" data-to"" data-subject="Electropuerto - <?php echo htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode) ?>"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Mail</a></li>
          </ul>
        </li>
      </ul>
    </span>
		<?php
		echo "</TD>";
		echo "</TR>\n";
		echo "<TR><TD CLASS=\"text-left\">";
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"2\">";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>Producto:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode)."</TD>\n";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=\"text-left\"><STRONG>C&oacute;digo:</STRONG></TD>";
		echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
		echo "<TD CLASS=\"text-left\">".htmlentities($det_producto->fields['codigo'],ENT_QUOTES,$default->encode)."</TD>\n";	
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
		if ($default->show_stock=="ICON" || $default->show_stock=="NUMBER"){
			echo "<TR>";
			echo "<TD CLASS=\"text-left\"><STRONG>Stock:</STRONG></TD>";
			echo "<TD CLASS=\"text-left\">&nbsp;</TD>";
			echo "<TD CLASS=\"text-left\">";
			if ($default->show_stock=="NUMBER"){
				if ($det_producto->fields['stock'] > 0) {
					echo htmlentities(sprintf('%01.0f',$det_producto->fields['stock']),ENT_QUOTES,$default->encode);
				} else {
					echo $default->sin_stock_str;
				}
			} elseif ($default->show_stock=="ICON") {
				if ($det_producto->fields['stock'] > 0) {
					if ($det_producto->fields['stock_ideal'] > 0 && $det_producto->fields['stock_ideal'] > $det_producto->fields['stock']){
						echo "<i class=\"fa fa-battery-half\" style=\"color:green;\"></i>";
					} else {	
						echo "<i class=\"fa fa-battery-full\" style=\"color:green;\"></i>";
					}
				} else {
					echo "<i class=\"fa fa-battery-quarter\" style=\"color:red;\"></i>";
				}
			}			
			echo "</TD>\n";
			echo "</TR>";
		}
		
		
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

		$r_image = array();
		echo "<TABLE WIDTH=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"2\" BORDER=\"0\" BGCOLOR=\"#FFFFFF\">";
		if ($det_producto->fields['imagen']!=''){
			$thumb = create_thumb($default->products_dir."/imagenes/".$det_producto->fields['imagen'], $default->products_dir."/miniaturas", 234, 124);
			if ($thumb){
				$r_image[] = array(
				'popup' => $default->products_dir."/imagenes/".$det_producto->fields['imagen'],
				'thumb' => $thumb,
				'tittle' => htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode),
				);
			}
		}

		$iquery="select * from e_pprod_img where cod_prod='".$det_producto->fields['codigo']."'";
		$res_i = $db->Execute($iquery);
		while (!$res_i->EOF){
			$thumb = create_thumb($res_i->fields['imagen'], $default->products_dir."/miniaturas/extras", 234, 124);
			if ($thumb){
				if ($res_i->fields['principal']){
					array_unshift($r_image, array(
						'popup' => $res_i->fields['imagen'],
						'thumb' => $thumb,
						'tittle' => htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode),
					)); 
				} else {
					array_push($r_image, array(
						'popup' => $res_i->fields['imagen'],
						'thumb' => $thumb,
						'tittle' => htmlentities($det_producto->fields['descripcion'],ENT_QUOTES,$default->encode),
					));
				}
			}
			$res_i->MoveNext();
		}
			
		if (sizeof($r_image)>0) {
			echo "<TR>";
			echo "<TD CLASS=\"text-center\" COLSPAN=\"3\">";
			echo "<div id=\"carouselp_".$det_producto->fields['codigo']."\" class=\"owl-carousel\" style=\"width: 240px; height: 124px; opacity: 1; display: block; margin: 0 auto; margin-top: 5px;\">";
			foreach ($r_image as $imagenes) {
				echo "<div class=\"item\">";
				echo "<img src=\"".$imagenes['thumb']."\" alt=\"".$imagenes['tittle']."\" class=\"img-responsive\">";
				echo "</div>";
			}
			echo "</div>";
			?>
			<script type="text/javascript"><!--
			$('#carouselp_<?php echo $det_producto->fields['codigo']; ?>').owlCarousel({
			items: 1,
			autoPlay: false,
			navigation: true,
			autoWidth: false,
			navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
			pagination: false
			});
			--></script>
			<?php

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
echo "<script>window.Sharer.init();</script>";
echo "</div></div>";
?>