<div id="cart" class="btn-group btn-block">
<button type="button" data-toggle="dropdown" data-loading-text="Cargando..." class="btn btn-inverse btn-block btn-lg dropdown-toggle">
<?php
$query = "SELECT e_cotiza_lineas.*,e_pproductos.*,e_cotiza.id_cotiza FROM e_cotiza,e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod 
WHERE id_cot=id_cotiza AND e_cotiza.estado=0 AND e_cotiza.user_id={$_SESSION['uid']}";
if ($items_r=$db->Execute($query)){
	$tart = 0;
	$total=0;
	$tiva=0;
	while (!$items_r->EOF){
		if ($_SESSION[nivel]==1){
			$loc_precio=$items_r->fields['precio2']*$_SESSION['coef'];
		} else {
			$loc_precio=$items_r->fields['precio']*$_SESSION['coef'];
		} 
		if ($_SESSION[iva]>0){
			$loc_precio = $loc_precio * (1+($items_r->fields['alicuota']/100));	
		}

		$loc_precio = round($loc_precio,$default->decimales);
		$total = $total + ($items_r->fields['cantidad']*$loc_precio);
		if ($_SESSION[iva]>0){
			$tiva = 0;	
		} else {
			$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
		}
		$products[] = array(
			'quantity' => $items_r->fields['cantidad'],
			'name'     => $items_r->fields['descripcion_prod'],
			'total'    => ($items_r->fields['cantidad']*$loc_precio),
			'code'     => $items_r->fields['codigo_prod'],
			'id_lin'   => $items_r->fields['id_lin'],

		);
		$nume=$items_r->fields['id_cotiza']; 
		$tart+=1;
		$items_r->MoveNext();
	}
	if ($tart > 0){
	  	echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">$tart art&iacute;culo(s) - $".sprintf("%01.2f",$total+$tiva)."</span></button>\n";
		echo "<ul class=\"dropdown-menu pull-right\">\n";
		echo "<li>\n";
		echo "<table class=\"table table-striped\">\n";
		foreach ($products as $product) {
			echo "<tr>\n";
			echo "<td class=\"text-left\">".$product['code']."</td>\n";
			echo "<td class=\"text-left\">".$product['name']."</td>\n";
			echo "<td class=\"text-right\">".$product['quantity']."</td>\n";
			echo "<td class=\"text-right\">$".sprintf("%01.2f",$product['total'])."</td>\n";
			echo "<td class=\"text-center\"><button type=\"button\" onclick=\"cart.remove('".$nume."','".$product['id_lin']."');\" title=\"Eliminar\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></button></td>\n";
			echo "</tr>\n";
	   }
		echo "</table>\n";
		echo "</li>\n";
		echo "<li>\n";
		echo "<div>\n";
		echo "<table class=\"table table-bordered\">\n";
		if ($_SESSION['iva']==0){
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
			echo "<td class=\"text-right\">$".sprintf("%01.2f",$total)."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class=\"text-right\"><strong>Total IVA</strong></td>\n";
			echo "<td class=\"text-right\">$".sprintf("%01.2f",$tiva)."</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td class=\"text-right\"><strong>Total</strong></td>\n";
		echo "<td class=\"text-right\">$".sprintf("%01.2f",$total+$tiva)."</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "<p class=\"text-right\"><a href=\"".$_SERVER['PHP_SELF']."?op=pedidos&cotizacion=show&cotizanum=$nume"."\"><strong><i class=\"fa fa-shopping-cart\"></i>&nbsp;&nbsp;&nbsp;Pedido</strong></a></p>\n";
		echo "</div>\n";
		echo "</li>\n";
		echo "</ul>\n";
	} else {
		echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">0 art&iacute;culo(s) - $0.00</span></button>\n";
		echo "<ul class=\"dropdown-menu pull-right\"><li><p class=\"text-center\">Su pedido se encuentra vac&iacute;o!</p></li></ul>\n";
	}
} else {
	echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">0 artículo(s) - $0.00</span></button>\n";
	echo "<ul class=\"dropdown-menu pull-right\"><li><p class=\"text-center\">No se encuentran pedidos abiertos!</p></li></ul>\n";
}
?>
</div>