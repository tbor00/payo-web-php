<?php
echo "<div class=\"row\">";
echo "<div class=\"col-sm-12\">";
$checkout=$_GET['checkout'];
if ($_SESSION['logged']){
	$id_usuario=$_SESSION['uid'];
	if ($checkout=="pay"){
		if (floatval($_POST['importe']) > 0){
		} else {
		$checkout="";
		$mp_error = "Debe ingresar un importe v&aacute;lido";
		}
	}	
	if ($checkout=='pay'){
		$importe=decimales($_POST['importe']);
		$referencia=$_POST['referencia'];
		$db = connect();
		$db->debug = SDEBUG;		
		$query = "Select * from e_webusers where user_id={$_SESSION['uid']}";
		$user_r = $db->Execute($query);
		if ($user_r && !$user_r->EOF){
			$id_pago = $db->GenID('seq_e_pagos');
			$query = "insert into e_pagos (id_pago,user_id,importe,referencia) values ($id_pago,{$_SESSION['uid']},$importe,".$db->qstr($referencia,get_magic_quotes_gpc()).")";
			$pago_r=$db->Execute($query);
			$mp = new MP($default->mp_id, $default->mp_secret);
			$preference_data = array (
				"items" => array (
					array (
						"title" => "Pago de cuenta corriente Electropuerto",
						"quantity" => 1,
						"currency_id" => "ARS",
						"unit_price" => floatval($importe),
					)
				),
				"payer"=> array(
					"name" => $user_r->fields['nombres'],
					"surname" => $user_r->fields['apellidos'],
					"email" => $user_r->fields['email'],
				),
				"external_reference" => "$id_pago",
			);
			$preference = $mp->create_preference($preference_data);
			?>
			<div id="ctacte" class="form-group required">
			<label class="col-sm-12" for="input-importe">Importe Total a Abonar: <?php echo $importe; ?></label>
			<div class="col-sm-4">
			<?php
			?>
			<script type="text/javascript">
			$(document).ready(function(){
				$.ajax({
					context: document.body,
					success: function(){
					$('#modal-payment').modal('hide')
					$('#modal-payment').remove();
				}});
			});
			</script>
			<script type="text/javascript">
			$MPC.openCheckout ({
				url: "<?php echo $preference['response']['init_point'] ?>",
				mode: "modal",
				onreturn: function(data){ MP_onreturn(data)}
			});
			</script>
			</div>
			</div>
			<?php
		}
	} elseif ($checkout=='payed'){
		$status=$_GET['status'];
		if ($status=='approved'){
			echo "<div class=\"alert alert-success\"><i class=\"fa fa-exclamation-circle\"></i> El pago ha sido acreditado.<br>En breve ser&aacute; reflejado en su cuenta corriente.</div>";
		} elseif ($status=='pending'){
			echo "<div class=\"alert alert-success\"><i class=\"fa fa-exclamation-circle\"></i> El pago se encuentra pendiente.<br>Ser&aacute; reflejado en su cuenta corriente a su aprobaci&oacute;n</div>";
		} elseif ($status=='in_process'){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El pago est&aacute; siendo revisado.</div>";
		} elseif ($status=='rejected'){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El pago fu&eacute; rechazado, el usuario puede intentar nuevamente el pago.</div>";
		} else {
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El usuario no complet&oacute; el proceso de pago, no se ha generado ning&uacute;n pago.</div>";
		}
	} else {
		$db = connect();
		$db->debug = SDEBUG;		
		if ($default->enable_gestion){
			$gestion=$_SESSION['gestion'];
		} else {	
			$gestion='';
		}
		$user_query = "select * from e_webusers where user_id=$id_usuario" ;
		$user_res=$db->Execute($user_query);
		if($user_res && ! $user_res->EOF ){
			$cliente_eb=$user_res->fields['eb_cod'];
		}
		$query = "SELECT sum(debito)-sum(credito) as saldo from e_ctasctes WHERE cliente_eb=$cliente_eb AND gestion='".$gestion."'";
		if ($ctacte_r=$db->Execute($query)){
			$saldo_ctacte = decimales($ctacte_r->fields['saldo']);
		}
		?>
		<form method="post" name="ctacte"  id="ctacte" class="form-horizontal">
		<fieldset>
		<?php
		if ($default->mp_rate > 0){
			$rate = decimales($saldo_ctacte * $default->mp_rate,2);
			$importe = decimales($saldo_ctacte * (1+$default->mp_rate),2);
		?>
		<div class="form-group required">
		<label class="col-sm-4" for="input-importe">Importe: $</label>
		<div class="col-sm-6">
		<input type="text" name="input-importe" value="<?php echo $saldo_ctacte ?>" id="input-importe" class="form-control" onkeyup="MP_rate()"/>
		</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4">Recargo: <?php echo decimales($default->mp_rate*100,2) ?>%</label>
		<div class="col-sm-6">
		<input type="text" name="Rate" id="Rate" value="<?php echo $rate ?>" class="form-control" disabled />
		<input type="hidden" name="MPrate" value="<?php echo $default->mp_rate ?>" id="MPrate"/>
		</div>
		</div>
		<div class="form-group required">
		<label class="col-sm-4" for="importe">Importe a Abonar: $</label>
		<div class="col-sm-6">
		<input type="text" name="importe" value="<?php echo $importe ?>" id="importe" class="form-control" disabled/>
		</div>
		</div>
		<?php
		} else {	
		?>
		<div class="form-group required">
		<label class="col-sm-4" for="input-importe">Importe a Abonar: $</label>
		<div class="col-sm-8">
		<input type="text" name="importe" value="<?php echo $saldo_ctacte ?>" id="input-importe" class="form-control" />
		</div>
		</div>
		<?php
		}
		?>
		</fieldset>
		<?php
		if (strlen($mp_error)>0) {
			echo "<div id=\"alert-zone\"><span><div class=\"alert alert-danger\">$mp_error<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div></span></div>";
		}
		?>
		<div class="buttons">
		<div class="pull-right">
		<input id="button-payment-next" class="btn btn-primary" data-loading-text="Cargando..." type="button" value="Siguiente" />
		</div>
		</div>		
		</form>		
		<?php
	}
}
echo "</div>";
echo "</div>";
?>

