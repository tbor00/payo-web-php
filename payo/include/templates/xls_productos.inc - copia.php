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

	$color1 = "#FFFFFF";  
	$color2 = "#F8F8F8";

	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_pproductos".$inner.$subquery." $ORDER";


	$producto_r=$db->Execute($query);
	if ($producto_r && !$producto_r->EOF){

		$i=0;
		$ws=1;
		HeaderingExcel('lista de precios.xls');
		$workbook = new Workbook("-");
		$worksheet[$ws] =& $workbook->add_worksheet("$ws");
		$formatot =& $workbook->add_format();
		$formatot->set_bold();
		$formatot->set_color('white');
		$formatot->set_align('center');
		$formatot->set_pattern();
		$formatot->set_fg_color('red');

		$worksheet[$ws]->write_string($i, 0, date('d')."/".date('m')."/".date('Y'));
		

		$i = $i+1;
		$worksheet[$ws]->write_string($i, 0, 'Cod', $formatot);
		$worksheet[$ws]->write_string($i, 1, 'Descripción', $formatot);
		$worksheet[$ws]->write_string($i, 2, 'Marca', $formatot);
		$worksheet[$ws]->write_string($i, 3, 'Modelo', $formatot);
		$worksheet[$ws]->write_string($i, 4, 'Cod Fab', $formatot);
		$worksheet[$ws]->write_string($i, 5, 'Precio', $formatot);
		$i = $i+1;
		
		while(!$producto_r->EOF) {
			if ($i==20000){
				$i=0;
				$ws=$ws+1;
				$worksheet[$ws] =& $workbook->add_worksheet("$ws");
				$worksheet[$ws]->write_string($i, 0, 'Cod', $formatot);
				$worksheet[$ws]->write_string($i, 1, 'Descripción', $formatot);
				$worksheet[$ws]->write_string($i, 2, 'Marca', $formatot);
				$worksheet[$ws]->write_string($i, 3, 'Modelo', $formatot);
				$worksheet[$ws]->write_string($i, 4, 'Cod Fab', $formatot);
				$worksheet[$ws]->write_string($i, 5, 'Precio', $formatot);
				$i = $i+1;
			}
			$worksheet[$ws]->write_string($i, 0, $producto_r->fields['codigo']);
			$worksheet[$ws]->write_string($i, 1, $producto_r->fields['descripcion']);
			$worksheet[$ws]->write_string($i, 2, $producto_r->fields['marca']);
			$worksheet[$ws]->write_string($i, 3, $producto_r->fields['modelo']);
			$worksheet[$ws]->write_string($i, 4, $producto_r->fields['codfab']);
			if ($_SESSION[nivel]==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			$worksheet[$ws]->write_number($i, 5, decimales($precio));
			$i = $i+1;
			$producto_r->MoveNext();
		}
		$workbook->close();
	}
}
?>