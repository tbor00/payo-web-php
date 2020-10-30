<?php
if ($_SESSION[logged]){

	$cotizanum = $_GET['cotizanum'];

	$color1 = "#FFFFFF";  
	$color2 = "#F8F8F8";

	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$cotizanum";
	$cotiza_r=$db->Execute($query);
	if ($cotiza_r && !$cotiza_r->EOF){
		$estado = $cotiza_r->fields['estado'];
		$civa = $cotiza_r->fields['iva'];

		$i=0;
		$ws=1;
		HeaderingExcel("pedido_".str_pad($cotiza_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT).".xls");
		$workbook = new Workbook("-");
		$worksheet[$ws] =& $workbook->add_worksheet("$ws");
		$formatot =& $workbook->add_format();
		$formatot->set_bold();
		$formatot->set_color('white');
		$formatot->set_align('center');
		$formatot->set_pattern();
		$formatot->set_fg_color('red');

		$worksheet[$ws]->write_string($i, 0, 'PEDIDO:');
		$worksheet[$ws]->write_string($i, 1, str_pad($cotiza_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT));
		$i = $i+1;
		$worksheet[$ws]->write_string($i, 0, 'FECHA:');
		$worksheet[$ws]->write_string($i, 1, timest2dt($cotiza_r->fields['fecha']));
		$i = $i+1;
		$worksheet[$ws]->write_string($i, 0, 'ESTADO:');
		if ($estado == 2){
			$worksheet[$ws]->write_string($i, 1, 'Enviado');
		} elseif ($estado==1){
			$worksheet[$ws]->write_string($i, 1, 'Borrador');
		} elseif ($estado==10){
			$worksheet[$ws]->write_string($i, 1, 'Anulado');
		} else {
			$worksheet[$ws]->write_string($i, 1, 'Activo');
		}
		$i = $i+1;
		$worksheet[$ws]->write_string($i, 0, 'REFERENCIA:');
		$worksheet[$ws]->write_string($i, 1, $cotiza_r->fields['referencia']);


		$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$cotizanum $ORDER";
		if ($items_r=$db->Execute($query)){

			$i = $i+1;
			$worksheet[$ws]->write_string($i, 0, 'Cod', $formatot);
			$worksheet[$ws]->write_string($i, 1, 'Descripción', $formatot);
			$worksheet[$ws]->write_string($i, 2, 'Marca', $formatot);
			$worksheet[$ws]->write_string($i, 3, 'Modelo', $formatot);
			$worksheet[$ws]->write_string($i, 4, 'Cod Fab', $formatot);
			$worksheet[$ws]->write_string($i, 5, 'Cantidad', $formatot);
			$worksheet[$ws]->write_string($i, 6, 'Precio', $formatot);
			$worksheet[$ws]->write_string($i, 7, 'IVA %', $formatot);
			$worksheet[$ws]->write_string($i, 8, 'Subtotal', $formatot);
			$i = $i+1;
			
			while(!$items_r->EOF) {
				$worksheet[$ws]->write_string($i, 0, $items_r->fields['codigo']);
				$worksheet[$ws]->write_string($i, 1, $items_r->fields['descripcion']);
				$worksheet[$ws]->write_string($i, 2, $items_r->fields['marca']);
				$worksheet[$ws]->write_string($i, 3, $items_r->fields['modelo']);
				$worksheet[$ws]->write_string($i, 4, $items_r->fields['codfab']);
				$worksheet[$ws]->write_number($i, 5, $items_r->fields['cantidad']);
				if ( $estado==2){
					$worksheet[$ws]->write_number($i, 6, decimales($items_r->fields['unitario']));
					$worksheet[$ws]->write_number($i, 7, decimales($items_r->fields['alicuota'],2));
					$worksheet[$ws]->write_number($i, 8, decimales($items_r->fields['cantidad']*$items_r->fields['unitario'],2));

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
					$worksheet[$ws]->write_number($i, 6, decimales($loc_precio));
					$worksheet[$ws]->write_number($i, 7, decimales($items_r->fields['alicuota'],2));
					$worksheet[$ws]->write_number($i, 8, decimales($items_r->fields['cantidad']*$loc_precio,2));
					$total = $total + ($items_r->fields['cantidad']*$loc_precio);
					if ($_SESSION[iva]>0){
						$tiva = 0;
					} else {
						$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
					}
				}
				$i = $i+1;
				$items_r->MoveNext();
			}
		}

		if ($_SESSION[iva]==0 || $civa==0){
			$i = $i+1;
			$worksheet[$ws]->write_string($i, 7, 'SUBTOTAL');
			$worksheet[$ws]->write_number($i, 8, decimales($total,2));
		}
		if ($cotiza_r->fields['descuento']>0){
			if ($cotiza_r->fields['iva']==1){
				$i = $i+1;
				$worksheet[$ws]->write_string($i, 7, 'SUBTOTAL');
				$worksheet[$ws]->write_number($i, 8, decimales($total,2));
			}
			$tiva = $tiva * (1-($cotiza_r->fields['descuento']/100));
			$subtotal = $total-($total*$cotiza_r->fields['descuento']/100);
			$i_descuento = ($total*$cotiza_r->fields['descuento']/100);

			$i = $i+1;
			$d_descuento = $cotiza_r->fields['leyenda_d'];
			if ($cotiza_r->fields['descuento'] > 0) {
				$d_descuento = $d_descuento." (".$cotiza_r->fields['descuento']."%)";
			}
			$worksheet[$ws]->write_string($i, 7, $d_descuento);
			$worksheet[$ws]->write_number($i, 8, decimal($i_descuento,2));
			
			if ($_SESSION[iva]==0 || $civa==0){
				$i = $i+1;
				$worksheet[$ws]->write_string($i, 7, 'SUBTOTAL');
				$worksheet[$ws]->write_number($i, 8, decimales($subtotal,2));
			}
		}	
		if ($_SESSION[iva]==0 || $civa==0){
			$i = $i+1;
			$worksheet[$ws]->write_string($i, 7, 'IVA');
			$worksheet[$ws]->write_number($i, 8, decimales($tiva,2));
		}	
		$i = $i+1;
		$worksheet[$ws]->write_string($i, 7, 'TOTAL');
		$worksheet[$ws]->write_number($i, 8, decimales($total+$tiva-$i_descuento,2));
	
		$workbook->close();
	}
}
?>