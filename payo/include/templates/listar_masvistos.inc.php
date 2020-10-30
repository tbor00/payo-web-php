<SCRIPT>
//---------------------------------------------------------
$(document).ready(function() {
	$('.thumbnails').each(function() { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: 'a', // the selector for gallery item
			type: 'image',
			tClose: 'Cerrar (Esc)',
			tLoading: 'Cargando...',		
			gallery: {
				enabled:true,
				tPrev: 'Anterior',
				tNext: 'Siguiente',
				tCounter: '%curr% de %total%'		  
			}
		});
	});	
});
//---------------------------------------------------------
$(document).ready(function() {
	$('.owl-carousel').each(function() { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: 'a', // the selector for gallery item
			type: 'image',
			tClose: 'Cerrar (Esc)',
			tLoading: 'Cargando...',		
			gallery: {
				enabled:true,
				tPrev: 'Anterior',
				tNext: 'Siguiente',
				tCounter: '%curr% de %total%'		  
			}
		});
	});	
});
//---------------------------------------------------------
</SCRIPT>

<?php

$subquery="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).")";

$query = "SELECT e_pproductos.* FROM e_pproductos JOIN e_pprod_rank ON codigo=cod_prod ".$subquery." ORDER BY vistas DESC LIMIT 0, 6";
if ($producto_r=$db->Execute($query)){
	if(!$producto_r ->EOF){
		echo '<h1 class="text-center">Productos más <span style="color:##900d0d; margin-top:30px;">vistos<span> </h1>';
		echo '<hr>';
	}
		$col=0;
		while (!$producto_r->EOF){
			if($producto_r->fields['marca_a']==3){
				$bgc="";
			} elseif($producto_r->fields['marca_a']==1){
				$bgc="";
			} else {
				$bgc="";
			}
			if ($col==0){
				echo "<div class=\"row\">";
			}
			echo "<div class=\"product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12\">\n";
			echo "<div class=\"product-thumb $bgc\">\n";
			$r_image = array();
			if ($producto_r->fields['imagen']!=''){
				$thumb = create_thumb($default->products_dir."/imagenes/".$producto_r->fields['imagen'], $default->products_dir."/miniaturas", 234, 124);
				if ($thumb){
					$r_image[] = array(
					'popup' => $default->products_dir."/imagenes/".$producto_r->fields['imagen'],
					'thumb' => $thumb,
					'tittle' => htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode),
					);
				}
			}

			$iquery="select * from e_pprod_img where cod_prod='".$producto_r->fields['codigo']."'";
			$res_i = $db->Execute($iquery);
			while (!$res_i->EOF){
				$thumb = create_thumb($res_i->fields['imagen'], $default->products_dir."/miniaturas/extras", 234, 124);
				if ($thumb){
					if ($res_i->fields['principal']){
						array_unshift($r_image, array(
							'popup' => $res_i->fields['imagen'],
							'thumb' => $thumb,
							'tittle' => htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode),
						)); 
					} else {
						$r_image[] = array(
							'popup' => $res_i->fields['imagen'],
							'thumb' => $thumb,
							'tittle' => htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode),
						);
					}
				}
				$res_i->MoveNext();
			}

			if (sizeof($r_image)>0) {
				$q_img=0;
				echo "<div id=\"carousels_".$producto_r->fields['codigo']."\" class=\"owl-carousel ".$producto_r->fields['codigo']."\" style=\"width: 240px; height: 124px; opacity: 1; display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 15px;\">";
				foreach ($r_image as $imagenes) {
					echo "<div class=\"item\">";
					echo "<A HREF=\"".$imagenes['popup']."\" title=\"".$imagenes['tittle']."\"><img src=\"".$imagenes['thumb']."\" alt=\"".$imagenes['tittle']."\" class=\"img-responsive\"></A>";
					echo "</div>";
				}
				echo "</div>";
			
			?>
			<script type="text/javascript"><!--
			$('#carousels_<?php echo $producto_r->fields['codigo']; ?>').owlCarousel({
			items: 1,
			autoPlay: false,
			navigation: true,
			autoWidth: false,
			navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
			pagination: false
			});
			--></script>
			<?php				
			}

			echo "<div class=\"caption\">";
			echo "<h4><A CLASS=\"agree\" HREF=\"auxiliar.php?op=productos&idp=".$producto_r->fields['codigo']."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</A></h2>";
			//echo "<p><STRONG>C&oacute;digo: </STRONG><SPAN CLASS=\"textobrowse\">".$producto_r->fields['codigo']."</SPAN></p>";
			echo "<p><STRONG>Marca: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['marca'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Modelo: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['modelo'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Cod. Fab.: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['codfab'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Precio: </STRONG><SPAN CLASS=\"textobrowse\">".$producto_r->fields['moneda']." ";
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo decimales($precio)."</p>\n";
			echo "</div>";
			
			if (sizeof($r_image)==0) {
				echo "<div style=\"width: 240px; height: 124px; opacity: 1; display: block; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;\">";
				echo "</div>";
			}
			
			echo "</div>";			
			echo "</div>";			
			$col++;
			if ($col == 3 || $col==0){
				echo "</div>";			
				$col=0;
			}
			
			$producto_r->MoveNext();
		}
		echo "</div>\n";		
		//-----------------------------------------------------------------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------------------------------------------------------------
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>
<P><P>
