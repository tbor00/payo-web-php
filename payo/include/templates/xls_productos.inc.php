<?php
if ($_SESSION[logged]){

	if ($_POST['buscar']!=''){
		$buscar = $_POST['buscar'];			  
	} elseif ($_GET['buscar']!=''){
		$buscar = $_GET['buscar'];
	}
	if ($_POST['tipo']!=''){
		$tipo = $_POST['tipo'];
	} elseif ($_GET['tipo']!=''){
		$tipo = $_GET['tipo'];
	}
	if ($_POST['marca']!=''){
		$marca = $_POST['marca'];
	} elseif ($_GET['marca']!=''){
		$marca = $_GET['marca'];
	}
	$ord = $_GET['ord'];
	$orden = $_GET['orden'];

	$subquery.="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).")";
	//$inner =" INNER JOIN e_pprod_marcas ON e_pproductos.marca = e_pprod_marcas.marca";

	if ($buscar!=''){
		if ($subquery != ''){
			$subquery .= " AND (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}else{
			$subquery=" WHERE (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}
	}
	if ($tipo!=''){
		if ($subquery != ''){
			$subquery .= " AND tipo='$tipo'";
		}else{
			$subquery = " WHERE tipo='$tipo'";
		}
	}
	if ($marca!=''){
		if ($subquery != ''){
			 $subquery .= " AND marca='$marca'";
		}else{
			 $subquery = " WHERE marca='$marca'";
		}
	}

	if ($orden == ""){
	  $orden="descripcion";
	}
	if ($ord != "ASC" && $ord != "DESC"){
	  $ord="ASC";
	}
	if (isset($orden) and strlen($orden)>0) {
		$ORDER="order by $orden $ord";
	}

	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_pproductos".$inner.$subquery." $ORDER";


	$producto_r=$db->Execute($query);
	if ($producto_r && !$producto_r->EOF){

		$i=1;
		$ws=0;

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Electropuerto")
									 ->setLastModifiedBy("ElectroPuerto")
									 ->setTitle("Lista de Precios Electropuerto")
									 ->setSubject("Lista de Precios Electropuerto")
									 ->setDescription("Lista de Precios Electropuerto Office 2007 XLSX.");

		$objPHPExcel->getActiveSheet()->setTitle('Precios');

		// Add some data
		$objPHPExcel->setActiveSheetIndex($ws)
            ->setCellValue('A'.$i, date('d')."/".date('m')."/".date('Y'))
            ->setCellValue('B'.$i, 'Lista de Precios Electropuerto');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setSize(18);


		$i = $i+1;
		$objPHPExcel->setActiveSheetIndex($ws)
			->setCellValue('A'.$i, mb_convert_encoding('Cod','UTF-8',$default->encode))
			->setCellValue('B'.$i, mb_convert_encoding('Descripción','UTF-8',$default->encode))
			->setCellValue('C'.$i, mb_convert_encoding('Marca','UTF-8',$default->encode))
			->setCellValue('D'.$i, mb_convert_encoding('Modelo','UTF-8',$default->encode))
			->setCellValue('E'.$i, mb_convert_encoding('Cod Fab','UTF-8',$default->encode))
			->setCellValue('F'.$i, mb_convert_encoding('Precio','UTF-8',$default->encode));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setBold(true);
		$i = $i+1;
		
		while(!$producto_r->EOF) {
			if ($i==20000){
				$i=1;
				$ws=$ws+1;
				$objPHPExcel->createSheet(NULL, "lll");
				$objPHPExcel->setActiveSheetIndex($ws)
					->setCellValue('A'.$i, mb_convert_encoding('Cod','UTF-8'))
					->setCellValue('B'.$i, mb_convert_encoding('Descripción','UTF-8',$default->encode))
					->setCellValue('C'.$i, mb_convert_encoding('Marca','UTF-8',$default->encode))
					->setCellValue('D'.$i, mb_convert_encoding('Modelo','UTF-8',$default->encode))
					->setCellValue('E'.$i, mb_convert_encoding('Cod Fab','UTF-8',$default->encode))
					->setCellValue('F'.$i, mb_convert_encoding('Precio','UTF-8',$default->encode));
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setBold(true);
				$i = $i+1;
				$objPHPExcel->getActiveSheet()->setTitle('Precios_'.$ws);
			}
			$objPHPExcel->setActiveSheetIndex($ws)
					->setCellValue('A'.$i, mb_convert_encoding($producto_r->fields['codigo'],'UTF-8',$default->encode))
					->setCellValue('B'.$i, mb_convert_encoding($producto_r->fields['descripcion'],'UTF-8',$default->encode))
					->setCellValue('C'.$i, mb_convert_encoding($producto_r->fields['marca'],'UTF-8',$default->encode))
					->setCellValue('D'.$i, mb_convert_encoding($producto_r->fields['modelo'],'UTF-8',$default->encode))
					->setCellValue('E'.$i, mb_convert_encoding($producto_r->fields['codfab'],'UTF-8',$default->encode));
					
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
					
			if ($_SESSION[nivel]==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			$objPHPExcel->setActiveSheetIndex($ws)
				->setCellValue('F'.$i, decimales($precio));
				$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$i = $i+1;
			$producto_r->MoveNext();
		}
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="lista de precios.xlsx"');
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