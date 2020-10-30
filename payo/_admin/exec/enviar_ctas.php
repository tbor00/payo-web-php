<?php
require_once('core.lib.php');
require_once('../include/fpdf/fpdf.php');
require_once('../include/phpmailer/class.phpmailer.php');
//----------------------------------------------------------------------------------------------------
class PDF extends FPDF {
	var $Cliente;
	var $CUIT;
	var $Gestion;
	// Cabecera de página
	function Header(){
		if ($this->PageNo() == 1){
			// Logo
			$this->Image('../img/logo.gif',10,8,90);
			
			// Título
			$this->Cell(100);
			$this->SetFont('Arial','B',10);
			$this->Cell(30,10,"Comprobantes Pendientes",0,1,'L');
			$this->SetFont('Arial','B',8);
			$this->Cell(100);
			$this->Cell(30,6,"Cliente: ".$this->Cliente,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,"C.U.I.T.: ".$this->CUIT,0,1,'L');
			$this->Cell(100);
			$this->Cell(30,6,"Gestión: ".$this->Gestion,0,1,'L');
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


$sendmail = True;


//----------------------------------------------------------------------------------------------------
$db = connect();
$db->debug = 0;

$query = "SELECT * FROM e_webusers WHERE activo_u=1 and email<>'' and eb_cod>0";
$user_res=$db->Execute($query);
if ($user_res && !$user_res->EOF){
	//$MailFrom = txt_email_to();
	$MailFrom='cobranzas@electropuerto.com.ar';
	while(!$user_res->EOF){
		if ($user_res->fields['razonsocial']) {
			$Empresa =  utf8_decode($user_res->fields['razonsocial']);
		} else {
			$Empresa =  utf8_decode($user_res->fields['nombres'])." ".utf8_decode($user_res->fields['apellidos']);
		}
		$Mailto = $user_res->fields['email'];
		$cliente_eb = $user_res->fields['eb_cod'];
		$cuit = $user_res->fields['cuit'];
		$saldo_ctacte = 0;
		$id_usuario = $user_res->fields['user_id'];
		
		
		$g_query="select e_gestiones.letra,e_gestiones.gestion from e_gestiones LEFT JOIN e_user_gestiones ON e_gestiones.id_gestion=e_user_gestiones.gestion_id WHERE user_id=$id_usuario ORDER BY e_gestiones.gestion";
		$g_res=$db->Execute($g_query);
		if($g_res && ! $g_res->EOF ){
			while (!$g_res->EOF){
				$gestion = $g_res->fields['letra'];
		
				$query = "SELECT (credito*-1+debito) as importe,comprobante,numero, IF(credito>0,saldo*-1,saldo) as saldo ,referencia,fecha,vto FROM e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
						  AND cliente_eb=$cliente_eb AND gestion='".$gestion."' ORDER by fecha,id ";
				$ctacte_r=$db->Execute($query);
				if ($ctacte_r && !$ctacte_r->EOF){

					$pdf = new PDF( 'P', 'mm', 'A4' );
					$pdf->AliasNbPages();
					$pdf->Cliente = $Empresa;
					$pdf->CUIT = $cuit;
					$pdf->Gestion = $g_res->fields['gestion'];
					$pdf->SetAuthor("Electropuerto");
					$pdf->SetTitle("Comprobantes Pendientes");
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
				
					if ($sendmail){
						$eba = $pdf->Output("S");
						$mail = new PHPMailer();
						$mail->IsHTML(true);
						$mail->From = $MailFrom;
						$mail->FromName = "Electropuerto";
						$mail->Mailer = "mail";
						$mail->Subject = "Comprobantes Pendientes";
						$mail->AltBody="Se adjuntan comprobantes pendientes";
						$mail->Body = "<P>Se adjuntan comprobantes pendientes</P>";
						$mail->AddAddress($Mailto);
						$mail->AddReplyTo($MailFrom);
						$mail->AddStringAttachment($eba, "Comprobantes_Pendientes.pdf");
						$mail->Send();
						$mail->ClearAddresses();
						$mail->ClearReplyTos();
					} else {	
						$pdf->Output("F","cp_".$gestion."_".$cliente_eb.".pdf");
					}
				}
				$g_res->MoveNext();
			}
		}
		$user_res->MoveNext();
	}	
}	
?>