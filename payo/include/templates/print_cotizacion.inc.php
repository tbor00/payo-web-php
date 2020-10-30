<?php
require_once('include/fpdf/fpdf.php');
//----------------------------------------------------------------------------------------------------
class PDF extends FPDF {
	var $Titulo;
	var $SubTitulo;
	var $SubTitulo_2;
	var $SubTitulo_3;
	var $SubTitulo_4;
	// Cabecera de página
	function Header(){
		if ($this->PageNo() == 1){
			// Logo
			$this->Image('image/logo.gif',10,8,90);
			$this->SetY(5);
			$this->Cell(100);
			$this->SetFont('Arial','B',10);
			$this->Cell(30,10,$this->Titulo,0,1,'L');
			$this->SetFont('Arial','B',8);
			$this->Cell(100);
			$this->Cell(30,6,$this->SubTitulo,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,$this->SubTitulo_2,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,$this->SubTitulo_3,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,$this->SubTitulo_4,0,1,'L');
			$this->Ln(10);
		}	
		$this->SetFont('Arial','B',8);		
		$this->Cell(10,8,"Cód.","TB",0,'C');
		$this->Cell(60,8,"Descripción","TB",0,'L');
		$this->Cell(20,8,"Marca","TB",0,'L');
		$this->Cell(25,8,"Modelo","TB",0,'L');
		$this->Cell(10,8,"Unid.","TB",0,'L');
		$this->Cell(15,8,"Cant.","TB",0,'C');
		$this->Cell(20,8,"Precio","TB",0,'C');
		$this->Cell(10,8,"IVA","TB",0,'C');
		$this->Cell(0,8,"Subtotal","TB",1,'C');
	}

	// Pie de página
	function Footer(){
		// Posición: a 1 cm del final
		$this->SetY(-8);
		// Arial italic 6
		$this->SetFont('Arial','I',6);
		// Número de página
		//$this->Cell(0,10,,0,0,'R');
		$this->Cell(15,5,date('d/m/Y'),"T",0,'L');
		$this->Cell(0,5,'Pág. '.$this->PageNo().'/{nb}',"T",0,'C');
		$this->Cell(0,5,'ELECTROPUERTO',"T",0,'R');
	}
}
//----------------------------------------------------------------------------------------------------



if ($_SESSION[logged]){
	$cotizanum=$_GET['cotizanum'];
	$id_usuario=$_SESSION['uid'];
	$db = connect();
	$db->debug = SDEBUG;

	$cotizacion_r=$db->Execute("SELECT * FROM e_cotiza WHERE id_cotiza=? AND user_id=?",array($cotizanum,$id_usuario));
	if ($cotizacion_r && !$cotizacion_r->EOF){
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
			$estado = $cotizacion_r->fields['estado'];
			if ($estado == 2){
				$str_estado = translate("Enviado");
			} elseif ($estado == 1){
				$str_estado = translate("Borrador"); 
			} else {
				$str_estado = translate("Activo");
			}
			$civa = $cotizacion_r->fields['iva'];
			$fecha = floor(adodb_mktime(0,0,0,substr($cotizacion_r->fields['fecha'],5,2),substr($cotizacion_r->fields['fecha'],8,2),substr($cotizacion_r->fields['fecha'],0,4))- adodb_mktime(0,0,0,12,29,1800))/(60 * 60 * 24);
		
			$pdf = new PDF( 'P', 'mm', 'A4' );
			$pdf->AliasNbPages();
			$pdf->Titulo = "Pedido NRO ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT);
			$pdf->SubTitulo = "Fecha: ".timest2dt($cotizacion_r->fields['fecha']);
			$pdf->SubTitulo_2 = "Cliente: ".$Empresa;
			$pdf->SubTitulo_3 = "Ref.: ".$cotizacion_r->fields['referencia'];
			$pdf->SubTitulo_4 = "Estado: ".$str_estado;
			$pdf->SetAuthor("Electropuerto");
			$pdf->SetTitle($pdf->Titulo);
			$pdf->SetAutoPageBreak(true,8);
			$pdf->AddPage();
			$pdf->SetFont('Arial','',6);		
		
		
			$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod WHERE id_cot=$cotizanum $ORDER";
			if ($items_r=$db->Execute($query)){
				while (!$items_r->EOF){
					$nn++;
					if ( $estado==2){
						$unitario = decimales($items_r->fields['unitario']);
						$iva = $items_r->fields['iva'];
						$subtotal = sprintf("%1.2f",$items_r->fields['cantidad']*$items_r->fields['unitario']);
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
						$unitario = decimales($loc_precio);
						$iva = $items_r->fields['alicuota'];
						$subtotal = sprintf("%1.2f",$items_r->fields['cantidad']*$loc_precio);
						$total = $total + ($items_r->fields['cantidad']*$loc_precio);
						if ($_SESSION[iva]>0){
							$tiva = 0;
						} else {
							$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
						}
					}	
					$pdf->Cell(10,6,utf8_decode($items_r->fields['codigo_prod']),0,0,'L');
					$pdf->Cell(60,6,utf8_decode($items_r->fields['descripcion_prod']),0,0,'L');
					$pdf->Cell(20,6,utf8_decode($items_r->fields['marca_prod']),0,0,'L');
					$pdf->Cell(25,6,utf8_decode($items_r->fields['modelo_prod']),0,0,'L');
					$pdf->Cell(10,6,utf8_decode($items_r->fields['unidad_prod']),0,0,'C');
					$pdf->Cell(15,6,utf8_decode($items_r->fields['cantidad']),0,0,'R');
					$pdf->Cell(20,6,$unitario,0,0,'R');
					$pdf->Cell(10,6,$iva,0,0,'C');
					$pdf->Cell(0,6,$subtotal,0,1,'R');
					$items_r->MoveNext();
				}
				if ($_SESSION[iva]==0 || $civa==0){
					$pdf->Cell(120);
					$pdf->Cell(30,6,"Subtotal",1,0,'L');
					$pdf->Cell(0,6,sprintf("%01.2f",$total),1,1,"R");
					if ($cotizacion_r->fields['descuento']>0){
						$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
						$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
						$pdf->Cell(120);
						$pdf->Cell(30,6,$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)",1,0,'L');
						$pdf->Cell(0,6,sprintf("%01.2f",$i_descuento),1,1,"R");
						$pdf->Cell(120);
						$pdf->Cell(30,6,"Subtotal",1,0,'L');
						$pdf->Cell(0,6,sprintf("%01.2f",$total-$i_descuento),1,1,"R");
					}
					$pdf->Cell(120);
					$pdf->Cell(30,6,"Total IVA",1,0,'L');
					$pdf->Cell(0,6,sprintf("%01.2f",$tiva),1,1,"R");
				} else {
					if ($cotizacion_r->fields['descuento']>0) {
						$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
						$pdf->Cell(120);
						$pdf->Cell(30,6,$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)",1,0,'L');
						$pdf->Cell(0,6,sprintf("%01.2f",$i_descuento),1,1,"R");
						$pdf->Cell(120);
						$pdf->Cell(30,6,"Subtotal",1,0,'L');
						$pdf->Cell(0,6,sprintf("%01.2f",$total-$i_descuento),1,1,"R");
					}
				}
				$pdf->Cell(120);
				$pdf->Cell(30,6,"Total",1,0,'L');
				$pdf->Cell(0,6,sprintf("%01.2f",$total+$tiva-$i_descuento),1,1,"R");

				if ($cotizacion_r->fields['comentario'] != ""){
					$pdf->Ln(10);
					$pdf->Cell(0,6,"COMENTARIOS:",0,1,'L');
					$pdf->MultiCell(0,6,utf8_decode($cotizacion_r->fields['comentario']),1);
				}
				$pdf->Output("I",$pdf->Titulo.".pdf");
			}		
		}	
	}	
}
?>
</BODY>
</HTML>