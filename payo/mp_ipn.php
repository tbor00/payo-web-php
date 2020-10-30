<?php
require('include/site.lib.php');
require('include/phpmailer/class.phpmailer.php');

$mp = new MP($default->mp_id, $default->mp_secret);

if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}

// Get the payment and the corresponding merchant_order reported by the IPN.
if($_GET["topic"] == 'payment'){
	$payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
	$merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
// Get the merchant_order reported by the IPN.
} else if($_GET["topic"] == 'merchant_order'){
	$merchant_order_info = $mp->get("/merchant_orders/" . $_GET["id"]);
}

if ($merchant_order_info["status"] == 200) {
	// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items 
	$paid_amount = 0;
	$htmlbody .= "<HTML><HEAD>\n";
	$htmlbody .= "<TITLE>.:. PAGO .:.</TITLE>\n";
	$htmlbody .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";
	$htmlbody .= "<STYLE TYPE=\"text/css\">\n";

	$htmlbody .= "BODY {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000000;}\n";
	$htmlbody .= "P {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; margin-top: 0px; margin-bottom: 3px; color: #000000;}\n";
	$htmlbody .= "STRONG {font-family: Arial, Helvetica, sans-serif;font-weight: bold;}\n";
	$htmlbody .= "SMALL {font-family: Arial, Helvetica, sans-serif;font-size: 8pt;}\n";
	$htmlbody .= "TD, TH {font-family: Arial, Helvetica, sans-serif;font-size: 9pt;}\n";
	$htmlbody .= ".textobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;}\n";
	$htmlbody .= ".titulobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: normal;}\n";
	$htmlbody .= ".titulobrowses {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: bold;}\n";
	$htmlbody .= "</STYLE>\n";
	$htmlbody .= "</HEAD>\n";
	$htmlbody .= "<BODY BGCOLOR=\"#C7C7C7\">\n";

	$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"2\" BGCOLOR=\"#eeeeee\">\n";
	$htmlbody .= "<TR VALIGN=\"TOP\">";
	$htmlbody .= "<TD BGCOLOR=\"#FFFFFF\" VALIGN=\"TOP\" ALIGN=\"LEFT\">\n";
	$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\">\n";
	$htmlbody .= "<TR>\n";
	$htmlbody .= "<TD BGCOLOR=\"#eeeeee\" ALIGN=\"LEFT\"><IMG SRC=\"logo.gif\" ALT=\"\" BORDER=\"0\"></TD>\n";
	$htmlbody .= "</TR></TABLE><BR>\n";

	foreach ($merchant_order_info["response"]["payments"] as  $payment) {
		if ($payment['status'] == 'approved'){
			$status = 'Aprobado';
		} elseif ($payment['status'] == 'pending'){
			$status = 'Pendiente';
		} elseif ($payment['status'] == 'rejected'){
			$status = 'Rechazado';
		} elseif ($payment['status'] == 'in_process'){
			$status = 'En proceso';
		} elseif ($payment['status'] == 'cancelled'){
			$status = 'Cancelado';
		} elseif ($payment['status'] == 'refunded'){
			$status = 'Devuelto';
		} else {
			$status = $payment['status'];
		}
		$paid_amount += $payment['transaction_amount'];
		$currency = $payment['currency_id'];
		$c_id = $payment['id'];;
		$hbody.="<TR><TD><STRONG>Cobro #:</STRONG></TD><TD><STRONG STYLE=\"color: red\">$c_id</STRONG></TD></TR>\n";
		$hbody.="<TR><TD><STRONG>Importe:</STRONG></TD><TD><STRONG STYLE=\"color: red\">".$currency."&nbsp;".decimales($payment['transaction_amount'])."</STRONG></TD></TR>\n";
		$hbody.="<TR><TD><STRONG>Estado:</STRONG></TD><TD><STRONG STYLE=\"color: red\">$status</STRONG></TD></TR>\n";
	}
	$reference=$merchant_order_info["response"]['external_reference'];
	$db = connect();
	$db->debug = SDEBUG;
	$query="SELECT * from e_pagos where id_pago=$reference";
	$pago_r = $db->Execute($query);
	if ($pago_r && !$pago_r->EOF){
		$importe = $pago_r->fields['importe'];
		$usuario_id = $pago_r->fields['user_id']; 
		$query="UPDATE e_pagos set estado='$status' where id_pago=$reference";
		$pag_r=$db->Execute($query);
		$query = "Select * from e_webusers where user_id=$usuario_id";
		$user_r = $db->Execute($query);
		if ($user_r && !$user_r->EOF){
			if ($user_r->fields['razonsocial']) {
				$Empresa =  $user_r->fields['razonsocial'];
			} else {
				$Empresa =  $user_r->fields['nombres']." ".$user_r->fields['apellidos'];
			}
			$Subject = 'Se ha registrado un pago de '.$Empresa;
			$MailFrom = txt_email_to(1);
			$FromName = "Payo pagos WEB";
			$Mailto = txt_email_to(3);
			$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\">\n";
			//$htmlbody .= "<TR><TD><STRONG>Cliente:</STRONG></TD><TD><STRONG STYLE=\"color: red\">".htmlentities($Empresa,ENT_QUOTES,$default->encode)."</STRONG></TD></TR>\n";
			$htmlbody .= "<TR><TD><STRONG>Cliente:</STRONG></TD><TD><STRONG STYLE=\"color: red\">".htmlspecialchars($Empresa,ENT_QUOTES,$default->encode)."</STRONG></TD></TR>\n";
			$htmlbody .= "<TR><TD><STRONG>Email:</STRONG></TD><TD><STRONG STYLE=\"color: red\">".$merchant_order_info["response"]["payer"]["email"]."</STRONG></TD></TR>\n";
			if ($pago_r->fields['cotiza_id']==0){
				$htmlbody .= "<TR><TD><STRONG>Tipo:</STRONG></TD><TD><STRONG STYLE=\"color: red\">Pago de Cuenta Corriente</STRONG></TD></TR>\n";
			} else {
				$htmlbody .= "<TR><TD><STRONG>Tipo:</STRONG></TD><TD><STRONG STYLE=\"color: red\">Pedido de Cotizaci&oacute;n Nro. ".$pago_r->fields['cotiza_id']."</STRONG></TD></TR>\n";
			}
			$htmlbody .= "</TABLE>\n";
			$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\">\n";
			$htmlbody .= $hbody;
			$htmlbody .= "</TABLE>\n";
			$htmlbody .= "</BODY></HTML>";
			
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->Charset = "UTF-8";
			$mail->AddEmbeddedImage("image/logo.gif", "logo.gif", "logo.gif");
			$mail->From = $MailFrom;
			$mail->FromName = $FromName;
			$mail->Mailer = "mail";
			$mail->Subject = $Subject;
			$mail->AltBody="Debe habilitar html para ver este mensaje";
			$mail->Body = $htmlbody;
			$mail->AddAddress($Mailto);
			$mail->Send();
			$mail->ClearAddresses();
			$mail->ClearReplyTos();
		}
	}	
    file_put_contents('_admin/logs/mp_log.txt', print_r($merchant_order_info,true), FILE_APPEND);	
	
/*	htmlentities($items_r->fields['codigo_prod'],ENT_QUOTES,$default->encode)
	if ($status=='approved' || $status=='pending' || $status=='in_process'){
	}
	
	if($paid_amount >= $merchant_order_info["response"]["total_amount"]){
		if(count($merchant_order_info["response"]["shipments"]) > 0) { // The merchant_order has shipments
			if($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship"){
				print_r("Totally paid. Print the label and release your item.");
			}
		} else { // The merchant_order don't has any shipments
			print_r("Totally paid. Release your item.");
		}
	} else {
		print_r("Not paid yet. Do not release your item.");
	}
    file_put_contents('_admin/logs/mp_log.txt', print_r($merchant_order_info,true), FILE_APPEND);	
*/
}
?>
