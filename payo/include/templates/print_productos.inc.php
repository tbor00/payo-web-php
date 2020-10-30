<?php
require_once('include/fpdf/fpdf.php');
//----------------------------------------------------------------------------------------------------
class PDF extends FPDF {
	var $Titulo;
	var $SubTitulo;
	var $Subtitulo_2;
	var $Subtitulo_3;
	var $Subtitulo_4;
	// Cabecera de p�gina
	function Header(){
		if ($this->PageNo() == 1){
			// Logo
			$this->Image('image/logo.gif',10,8,90);
			$this->Cell(100);
			$this->SetFont('Arial','B',10);
			$this->Cell(30,10,$this->Titulo,0,1,'L');
			$this->SetFont('Arial','B',8);
			$this->Cell(100);
			$this->Cell(30,6,$this->SubTitulo,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,$this->Subtitulo_2,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,$this->Subtitulo_3,0,1,'L');
			$this->Cell(100);
			$this->SetFont('Arial','B',6);
			$this->Cell(30,6,$this->Subtitulo_4,0,1,'L');
			$this->Ln(10);
		}	
		$this->SetFont('Arial','B',8);		
		$this->Cell(10,8,"C�d.","TB",0,'C');
		$this->Cell(70,8,"Descripci�n","TB",0,'L');
		$this->Cell(30,8,"Marca","TB",0,'L');
		$this->Cell(35,8,"Modelo","TB",0,'L');
		$this->Cell(35,8,"C�d. Fab.","TB",0,'L');
		$this->Cell(0,8,"Precio","TB",1,'C');
	}

	// Pie de p�gina
	function Footer(){
		// Posici�n: a 1 cm del final
		$this->SetY(-8);
		// Arial italic 6
		$this->SetFont('Arial','I',6);
		// N�mero de p�gina
		//$this->Cell(0,10,,0,0,'R');
		$this->Cell(15,5,date('d/m/Y'),"T",0,'L');
		$this->Cell(0,5,'P�g. '.$this->PageNo().'/{nb}',"T",0,'C');
		$this->Cell(0,5,'ELECTROPUERTO',"T",0,'R');
	}
}
//----------------------------------------------------------------------------------------------------
if ($_SESSION[logged]){

	$db = connect();
	$db->debug = SDEBUG;

	$query = "SELECT * from e_parametros where id_param=1";
	$result = $db->Execute($query);
	if ($result && !$result->EOF){
		$subtitulo = $result->fields['pie_1'];
		$subtitulo_2 = $result->fields['pie_2'];
		$subtitulo_3 = $result->fields['pie_3'];
		$subtitulo_4 = $result->fields['email'];
	}


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
	$subquery.="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nive']+1).")";
	if ($buscar!=''){
		if ($subquery != ''){
			$subquery .= " AND (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}else{
			$subquery=" WHERE (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}
	}
	if ($tipo!=''){
		if ($subquery != ''){
			$subquery .= " AND e_pproductos.tipo='$tipo'";
		}else{
			$subquery = " WHERE e_pproductos.tipo='$tipo'";
		}
	}
	if ($marca!=''){
		if ($subquery != ''){
			 $subquery .= " AND e_pproductos.marca='$marca'";
		}else{
			 $subquery = " WHERE e_pproductos.marca='$marca'";
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
	$query = "SELECT * FROM e_pproductos".$inner.$subquery." $ORDER";
	$producto_r=$db->Execute($query);
	if ($producto_r && !$producto_r->EOF){
		
		$pdf = new PDF( 'P', 'mm', 'A4' );
		$pdf->AliasNbPages();
		$pdf->Titulo = "Listado de Productos";
		$pdf->SubTitulo = $subtitulo;
		$pdf->Subtitulo_2 = $subtitulo_2;
		$pdf->Subtitulo_3 = $subtitulo_3;
		$pdf->Subtitulo_4 = $subtitulo_4;
		$pdf->SetAuthor("PAYO");
		$pdf->SetTitle($pdf->Titulo);
		$pdf->SetAutoPageBreak(true,8);
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);		
		while(!$producto_r->EOF){
			if ($_SESSION[nivel]==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			$pdf->Cell(10,6,utf8_decode($producto_r->fields['codigo']),0,0,'L');
			$pdf->Cell(70,6,utf8_decode($producto_r->fields['descripcion']),0,0,'L');
			$pdf->Cell(30,6,utf8_decode($producto_r->fields['marca']),0,0,'L');
			$pdf->Cell(35,6,utf8_decode($producto_r->fields['modelo']),0,0,'L');
			$pdf->Cell(35,6,utf8_decode($producto_r->fields['codfab']),0,0,'L');
			$pdf->Cell(0,6,$producto_r->fields['moneda']." ".decimales($precio),0,1,'R');

			$producto_r->MoveNext();
		}	
		$pdf->Output("I","Listado de Productos.pdf");
	}
}
?>
