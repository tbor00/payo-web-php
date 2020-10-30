<?php
$destino = $_SESSION[nivel]+1;
?>
<SCRIPT>
$(document).ready(function() {
	$('.image').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:false
		}
	});

});
//---------------------------------------------------------
</SCRIPT>
<?php




$query = "SELECT e_ofertas.*,e_pproductos.* FROM e_ofertas JOIN e_pproductos ON codigo=cod_prod WHERE (destino=0 OR destino=$destino) AND fecha_baja>now() ORDER BY rand()";
if ($producto_r=$db->Execute($query)){
	$col=0;
	while (!$producto_r->EOF){
			echo "<div class=\"row\">";
			echo "<div class=\"col-sm-12 text-right\">\n";
			echo "<A HREF=\"auxiliar.php?op=oprint\" TARGET=\"_print\"  class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Versi&oacute;n para Imprimir\"><i class=\"fa fa-print\"></i></A>";
			echo "</div>";
			echo "</div>";
			echo "<br>\n";
			echo "<div class=\"col-sm-12\">\n";			
			if ($col==0){
				echo "<div class=\"row\">";
			}
			echo "<div class=\"product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12\">\n";
         echo "<div class=\"product-thumb\">\n";
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
			   $partes_ruta = pathinfo($p_image);
				if (file_exists("products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']))){
					$t_image = "products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']);
				}else{
					convert_image($p_image,"products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']),"120x120","80");
					$t_image = "products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']);
				}
				echo "<div class=\"image\" style=\"margin-top:5px; margin-bottom:5px;\"><A HREF=\"".$p_image."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"><img src=\"".$t_image."\" border=\"0\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"></a></div>";
			}
			echo "<div class=\"caption\">";
 			//echo "<p><span class=\"code-ofert\">".$producto_r->fields['codigo']."</span></p>\n";
			echo "<h4><a class=\"agree\" href=\"auxiliar.php?op=productos&idp=".$producto_r->fields['codigo']."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</a></h2>";
			echo "<p>".$producto_r->fields['oferta']."</p>\n";
			echo "<p class=\"text-right\"><span class=\"price-ofert\">";
			echo $producto_r->fields['moneda']." ";
			if ($_SESSION["nivel"]==1){
				$precio=$producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio=$producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION['iva']>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo decimales($precio);
			echo "</span></p>";
			echo "<p style=\"font-size: 8pt; text-align: right;\">Oferta v&aacute;lida hasta el ".timesql2std($producto_r->fields['fecha_baja'])."</p>";
			echo "</div>";
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
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
echo "</div></div>";
?>

