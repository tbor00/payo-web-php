<?php
require_once('include/fpdf/fpdf.php');
//----------------------------------------------------------------------------------------------------
class PDF extends FPDF {
	var $Titulo;
	var $SubTitulo;
	var $Subtitulo_2;
	var $Subtitulo_3;
	// Cabecera de página
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
			$this->Ln(10);
		}	
	}

	// Pie de página
	function Footer(){
		// Posición: a 1 cm del final
		$this->SetY(-10);
		// Arial italic 6
		$this->SetFont('Arial','I',6);
		// Número de página
		//$this->Cell(0,10,,0,0,'R');
		$this->Cell(15,10,date('d/m/Y'),0,0,'L');
		$this->Cell(0,10,'Pág. '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Cell(0,10,'ELECTROPUERTO',0,0,'R');
	}
}
//----------------------------------------------------------------------------------------------------
if ($_SESSION[logged]){
	$db = connect();
	$db->debug = SDEBUG;
	$gestion=$_SESSION['gestion'];
	$id_usuario=$_SESSION['uid'];
	$saldo_ctacte = 0;
	$g_query="select e_gestiones.letra,e_gestiones.gestion from e_gestiones where e_gestiones.letra='".$gestion."'";
	$g_res=$db->Execute($g_query);
	if($g_res && ! $g_res->EOF ){
		$ngestion = $g_res->fields['gestion'];
	}

	$user_query = "select * from e_webusers where user_id=$id_usuario" ;
	$user_res=$db->Execute($user_query);
	if($user_res && ! $user_res->EOF ){
		if ($user_res->fields['razonsocial']) {
			$Empresa =  utf8_decode($user_res->fields['razonsocial']);
		} else {
			$Empresa =  utf8_decode($user_res->fields['nombres'])." ".utf8_decode($user_res->fields['apellidos']);
		}
		$cliente_eb = $user_res->fields['eb_cod'];
		$cuit = $user_res->fields['cuit'];
		$query = "SELECT (credito*-1+debito) as importe,comprobante,numero, IF(credito>0,saldo*-1,saldo) as saldo ,referencia,fecha,vto FROM e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
	          AND cliente_eb=$cliente_eb AND gestion='".$gestion."' ORDER by fecha,id ";
		$ctacte_r=$db->Execute($query);
		if ($ctacte_r && !$ctacte_r->EOF){
			$pdf = new PDF( 'P', 'mm', 'A4' );
			$pdf->AliasNbPages();
			$pdf->Titulo = "Comprobantes Pendientes";
			$pdf->SubTitulo = "Cliente: ".$Empresa;
			$pdf->Subtitulo_2 = "C.U.I.T.: ".$cuit;
			$pdf->Subtitulo_3 = "Gestión: ".$ngestion;
			$pdf->SetAuthor("Electropuerto");
			$pdf->SetTitle($pdf->Titulo);
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',8);		
			$pdf->Cell(30,8,"Comprobante","TB",0,'L');
			$pdf->Cell(20,8,"Fecha","TB",0,'C');
			$pdf->Cell(20,8,"Vto.","TB",0,'C');
			$pdf->Cell(35,8,"Ref.","TB",0,'L');
			$pdf->Cell(35,8,"Importe","TB",0,'C');
			$pdf->Cell(30,8,"Saldo Comp.","TB",0,'C');
			$pdf->Cell(0,8,"Saldo","TB",1,'C');
			$pdf->SetFont('Arial','',8);		
			while(!$ctacte_r->EOF){
				$saldo_ctacte = $saldo_ctacte + $ctacte_r->fields['saldo'];
				$comprobante = $ctacte_r->fields['comprobante'].' '.$ctacte_r->fields[numero];
				$fecha = timesql2std($ctacte_r->fields['fecha']);
				if ($ctacte_r->fields['comprobante'] != 'FAC' && $ctacte_r->fields['comprobante'] != 'NDB' && $ctacte_r->fields['comprobante'] != 'TIQ'){
					$vto = "";
				} else {
					$vto = timesql2std($ctacte_r->fields['vto']);
				}			
				$ref = $ctacte_r->fields['referencia'];
				$importe = sprintf('%01.2f',$ctacte_r->fields['importe']);
				$saldo = sprintf('%01.2f',$ctacte_r->fields['saldo']);
				$saldo_cta = sprintf('%01.2f',$saldo_ctacte);
				
				$pdf->Cell(30,6,$comprobante,0,0,'L');
				$pdf->Cell(20,6,$fecha,0,0,'R');
				$pdf->Cell(20,6,$vto,0,0,'R');
				$pdf->Cell(35,6,$ref,0,0,'L');
				$pdf->Cell(35,6,$importe,0,0,'R');
				$pdf->Cell(30,6,$saldo,0,0,'R');
				$pdf->Cell(0,6,$saldo_cta,0,1,'R');
				$ctacte_r->MoveNext();
			}
			$queryctactei = "Select * from e_ctasctes_import ORDER by fecha DESC";
			$ctactei_r = $db->Execute($queryctactei);
			if (!$ctactei_r->EOF){
				$pdf->SetFont('Arial','',8);		
				$pdf->Cell(0,6,"Saldo al ".timest2dt($ctactei_r->fields['fecha']),'T',1,'L');
			}
			$pdf->Output("I","Comprobantes_pendientes.pdf");
		}	
	}
}
?>