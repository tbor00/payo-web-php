<?php
$query = "SELECT * FROM e_carrousel WHERE activo=1 and (nivel=".$_SESSION[nivel]." or publico=1) ORDER by posicion";
$slide_r=$db->Execute($query);
if ($slide_r && !$slide_r->EOF){
	echo "<div id=\"carousel0\" class=\"owl-carousel\">\n";
	while (!$slide_r->EOF){
		$p_image = similar_file_exists("carrousel/imagenes/".$slide_r->fields['imagen']);
		$thumb_res = "300x300";
		$partes_ruta = pathinfo($p_image);
		if (file_exists("carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']))){
			$t_image = "carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']);
		}else{
			convert_image($p_image,"carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']),$thumb_res,"80");
			$t_image = "carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']);
		}
		echo "<div class=\"item text-center\">\n";
		if ($slide_r->fields['url']){
			echo "<a href=\"".$slide_r->fields['url']."\" target=\"_new\"><img src=\"".$t_image."\" alt=\"".$slide_r->fields['titulo']."\" class=\"img-responsive\" /></a>\n";
		} else {
			echo "<img src=\"".$t_image."\" alt=\"".$slide_r->fields['titulo']."\" class=\"img-responsive\" />\n";
		}
		echo "</div>\n";
		$slide_r->MoveNext();
	}
	echo "</div>\n";
	echo "<script type=\"text/javascript\"><!--\n";
	echo "$('#carousel0').owlCarousel({\n";
	echo "items: 4,\n";
	echo "autoPlay: 3000,\n";
	echo "navigation: true,\n";
	echo "navigationText: ['<i class=\"fa fa-chevron-left fa-5x\"></i>', '<i class=\"fa fa-chevron-right fa-5x\"></i>'],\n";
	echo "pagination: false\n";
	echo "});\n";
?>
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
<?php
	echo "--></script>\n";
}
?>