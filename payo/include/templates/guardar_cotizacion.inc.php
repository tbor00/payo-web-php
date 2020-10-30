<?php
$comentario = $_POST['comentario'];
$descuento = $_POST['descuento'];
$referencia = $_POST['referencia'];
$total=0;
$i_descuento=0;
$tiva=0;
if ($descuento){
	$query = "Select * from e_descuentos where id_descuento=$descuento";
	$desc_r=$db->Execute($query);
	if ($desc_r && !$desc_r->EOF){
		$leyenda=$desc_r->fields['leyenda'];
		$porcentaje=$desc_r->fields['porcentaje'];
		$desc_query = ", descuento=$porcentaje, leyenda_d='{$leyenda}'";
	}
}
$updquery = "UPDATE e_cotiza set estado=1, comentario='$comentario', referencia='$referencia', iva=".$_SESSION['iva']." $desc_query WHERE id_cotiza=$cotizanum AND (estado=0 Or estado=1)";
if ($db->Execute($updquery)){

} else {
	echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>&nbsp;Ocurrio un error al enviar el Pedido</div>\n";
}	
?>
