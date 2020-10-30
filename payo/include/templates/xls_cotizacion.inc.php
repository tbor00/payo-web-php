<?php
if ($_SESSION[logged]){

	$cotizanum = $_GET['cotizanum'];

	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$cotizanum";
	$cotiza_r=$db->Execute($query);
	if ($cotiza_r && !$cotiza_r->EOF){
		$estado = $cotiza_r->fields['estado'];
		$civa = $cotiza_r->fields['iva'];

		$i=0;
		$ws=1;
		
		$i=1;
		$ws=0;

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Electropuerto")
									 ->setLastModifiedBy("ElectroPuerto")
									 ->setTitle("Pedido Electropuerto")
									 ->setSubject("Pedido Electropuerto")
									 ->setDescription("Pedido Electropuerto Office 2007 XLSX.");

		$objPHPExcel->getActiveSheet()->setTitle('Precios');

		// Add some data
		$objPHPExcel->setActiveSheetIndex($ws)
            ->setCellValue('B'.$i, 'Electropuerto');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setSize(18);


		$i = $i+1;
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('A'.$i, mb_convert_encoding('PEDIDO:','UTF-8'))
			->setCellValue('B'.$i, mb_convert_encoding(str_pad($cotiza_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT),'UTF-8',$default->encode));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
		$i = $i+1;	
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('A'.$i, mb_convert_encoding('FECHA:','UTF-8'))
			->setCellValue('B'.$i, mb_convert_encoding(timest2dt($cotiza_r->fields['fecha']),'UTF-8',$default->encode));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
			
		$i = $i+1;	
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('A'.$i, mb_convert_encoding('ESTADO:','UTF-8'));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		if ($estado == 2){
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('B'.$i, mb_convert_encoding('Enviado','UTF-8',$default->encode));
		} elseif ($estado==1){
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('B'.$i, mb_convert_encoding('Borrador','UTF-8',$default->encode));
		} elseif ($estado==10){
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('B'.$i, mb_convert_encoding('Anulado','UTF-8',$default->encode));
		} else {
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('B'.$i, mb_convert_encoding('Activo','UTF-8',$default->encode));
		}
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
		
		$i = $i+1;
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('A'.$i, mb_convert_encoding('REFERENCIA:','UTF-8',$default->encode))
			->setCellValue('B'.$i, mb_convert_encoding($cotiza_r->fields['referencia'],'UTF-8',$default->encode));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);



		$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$cotizanum $ORDER";
		if ($items_r=$db->Execute($query)){

			$i = $i+1;
			
			
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('A'.$i, mb_convert_encoding('Cod','UTF-8',$default->encode))
				->setCellValue('B'.$i, mb_convert_encoding('Descripción','UTF-8',$default->encode))
				->setCellValue('C'.$i, mb_convert_encoding('Marca','UTF-8',$default->encode))
				->setCellValue('D'.$i, mb_convert_encoding('Modelo','UTF-8',$default->encode))
				->setCellValue('E'.$i, mb_convert_encoding('Cod Fab','UTF-8',$default->encode))
				->setCellValue('F'.$i, mb_convert_encoding('Cantidad','UTF-8',$default->encode))
				->setCellValue('G'.$i, mb_convert_encoding('Precio','UTF-8',$default->encode))
				->setCellValue('H'.$i, mb_convert_encoding('IVA %','UTF-8',$default->encode))
				->setCellValue('I'.$i, mb_convert_encoding('Subtotal','UTF-8',$default->encode));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getFont()->setBold(true);

			$i = $i+1;
			while(!$items_r->EOF) {
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('A'.$i, mb_convert_encoding($items_r->fields['codigo'],'UTF-8',$default->encode))
				->setCellValue('B'.$i, mb_convert_encoding($items_r->fields['descripcion'],'UTF-8',$default->encode))
				->setCellValue('C'.$i, mb_convert_encoding($items_r->fields['marca'],'UTF-8',$default->encode))
				->setCellValue('D'.$i, mb_convert_encoding($items_r->fields['modelo'],'UTF-8',$default->encode))
				->setCellValue('E'.$i, mb_convert_encoding($items_r->fields['codfab'],'UTF-8',$default->encode))
				->setCellValue('F'.$i, mb_convert_encoding($items_r->fields['cantidad'],'UTF-8',$default->encode));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

				
				if ( $estado==2){
					$objPHPExcel->setActiveSheetIndex($ws)
						->setCellValue('G'.$i, mb_convert_encoding(decimales($items_r->fields['unitario']),'UTF-8',$default->encode))
						->setCellValue('H'.$i, mb_convert_encoding(decimales($items_r->fields['alicuota'],2),'UTF-8',$default->encode))
						->setCellValue('I'.$i, mb_convert_encoding(decimales($items_r->fields['cantidad']*$items_r->fields['unitario'],2),'UTF-8',$default->encode));
					$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);


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
					$objPHPExcel->setActiveSheetIndex($ws)
						->setCellValue('G'.$i, mb_convert_encoding(decimales($loc_precio),'UTF-8',$default->encode))
						->setCellValue('H'.$i, mb_convert_encoding(decimales($items_r->fields['alicuota'],2),'UTF-8',$default->encode))
						->setCellValue('I'.$i, mb_convert_encoding(decimales($items_r->fields['cantidad']*$loc_precio,2),'UTF-8',$default->encode));
					$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						
						
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
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('H'.$i, mb_convert_encoding('SUBTOTAL','UTF-8',$default->encode))
				->setCellValue('I'.$i, mb_convert_encoding(decimales($total,2),'UTF-8',$default->encode));
				$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		}
		if ($cotiza_r->fields['descuento']>0){
			if ($cotiza_r->fields['iva']==1){
				$i = $i+1;
				$objPHPExcel->setActiveSheetIndex($ws)
					->setCellValue('H'.$i, mb_convert_encoding('SUBTOTAL','UTF-8',$default->encode))
					->setCellValue('I'.$i, mb_convert_encoding(decimales($total,2),'UTF-8',$default->encode));
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			}
			$tiva = $tiva * (1-($cotiza_r->fields['descuento']/100));
			$subtotal = $total-($total*$cotiza_r->fields['descuento']/100);
			$i_descuento = ($total*$cotiza_r->fields['descuento']/100);

			$i = $i+1;
			$d_descuento = $cotiza_r->fields['leyenda_d'];
			if ($cotiza_r->fields['descuento'] > 0) {
				$d_descuento = $d_descuento." (".$cotiza_r->fields['descuento']."%)";
			}
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('H'.$i, mb_convert_encoding($d_descuento,'UTF-8',$default->encode))
				->setCellValue('I'.$i, mb_convert_encoding(decimales($i_descuento,2),'UTF-8',$default->encode));
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			
			if ($_SESSION[iva]==0 || $civa==0){
				$i = $i+1;
				$objPHPExcel->setActiveSheetIndex($ws)
					->setCellValue('H'.$i, mb_convert_encoding('SUBTOTAL','UTF-8'))
					->setCellValue('I'.$i, mb_convert_encoding(decimales($subtotal,2),'UTF-8'));
				$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			}
		}	
		if ($_SESSION[iva]==0 || $civa==0){
			$i = $i+1;
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('H'.$i, mb_convert_encoding('IVA','UTF-8',$default->encode))
				->setCellValue('I'.$i, mb_convert_encoding(decimales($tiva,2),'UTF-8',$default->encode));
				$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		}	
		$i = $i+1;
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('H'.$i, mb_convert_encoding('TOTAL','UTF-8'))
			->setCellValue('I'.$i, mb_convert_encoding(decimales($total+$tiva-$i_descuento,2),'UTF-8',$default->encode));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);


		$objPHPExcel->setActiveSheetIndex(0);

		////HeaderingExcel("pedido_".str_pad($cotiza_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT).".xlsx");

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'."pedido_".str_pad($cotiza_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT).".xlsx".'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;


			
	}
}
?>