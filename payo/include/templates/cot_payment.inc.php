<?php
echo "<div class=\"row\">";
echo "<div class=\"col-sm-12\">";
$checkout=$_GET['checkout'];
if ($_SESSION['logged']){
	$id_usuario=$_SESSION['uid'];
	if ($checkout=='pay'){
		$importe=decimales($_POST['importe']);
		$cotizanum=$_POST['cotizanum'];
		$db = connect();
		$db->debug = SDEBUG;		
		$query = "Select * from e_webusers where user_id={$_SESSION['uid']}";
		$user_r = $db->Execute($query);
		if ($user_r && !$user_r->EOF){
			$id_pago = $db->GenID('seq_e_pagos');
			$query = "insert into e_pagos (id_pago,user_id,importe,cotiza_id) values ($id_pago,{$_SESSION['uid']},$importe,$cotizanum)";
			$pago_r=$db->Execute($query);
			$mp = new MP($default->mp_id, $default->mp_secret);
			$preference_data = array (
				"items" => array (
					array (
						"title" => "Pago de Pedido Nro. " . str_pad($cotizanum,8,"0",STR_PAD_LEFT),
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
				onreturn: function(data){ MP_conreturn(data)}
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
			echo "<script type=\"text/javascript\">";
			echo "$(function(){";
			echo "$.ajax({";
			echo "context: document.body,";
			echo "success: function(){";
			echo "document.getElementById(\"sendform\").submit();";
			echo "}});";
			echo "});";
			echo "</script>";
		} elseif ($status=='pending'){
			echo "<div class=\"alert alert-success\"><i class=\"fa fa-exclamation-circle\"></i> El pago se encuentra pendiente.<br>Ser&aacute; reflejado en su cuenta corriente a su aprobaci&oacute;n</div>";
			echo "<script type=\"text/javascript\">";
			echo "$(function(){";
			echo "$.ajax({";
			echo "context: document.body,";
			echo "success: function(){";
			echo "document.getElementById(\"sendform\").submit();";
			echo "}});";
			echo "});";
			echo "</script>";
		} elseif ($status=='in_process'){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El pago est&aacute; siendo revisado.</div>";
			echo "<script type=\"text/javascript\">";
			echo "$(function(){";
			echo "$.ajax({";
			echo "context: document.body,";
			echo "success: function(){";
			echo "document.getElementById(\"sendform\").submit();";
			echo "}});";
			echo "});";
			echo "</script>";
		} elseif ($status=='rejected'){
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El pago fu&eacute; rechazado, el usuario puede intentar nuevamente el pago.</div>";
		} else {
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> El usuario no complet&oacute; el proceso de pago, no se ha generado ning&uacute;n pago.</div>";
		}
	} else {
		$cotizanum=$_GET['cotizanum'];
		$importe = decimales($_GET['importe']);
		?>
		<form method="post" name="ctacte" id="ctacte" class="form-horizontal">
		<input type="hidden" name="cotizanum" value="<?php echo $cotizanum ?>" id="cotizanum"/>
		<fieldset>
		<?php
		
		if ($default->mp_rate > 0){
			$cot_importe=decimales($_GET['importe']);
			$rate = decimales($cot_importe * $default->mp_rate,2);
			$importe = decimales($cot_importe * (1+$default->mp_rate),2);
		?>
		<div class="form-group required">
		<label class="col-sm-4" for="input-importe">Importe: $</label>
		<div class="col-sm-6">
		<input type="text" name="input-importe" value="<?php echo $cot_importe ?>" id="input-importe" class="form-control" disabled />
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
		<input type="text" name="importe" value="<?php echo $importe ?>" id="importe" class="form-control" disabled />
		</div>
		</div>
		<?php
		} else {	
		?>
		<div class="form-group required">
		<label class="col-sm-4" for="input-importe">Importe a Abonar: $</label>
		<div class="col-sm-8">
		<input type="text" name="importe" value="<?php echo $importe ?>" id="input-importe" class="form-control" />
		</div>
		</div>
		<?php
		}
		?>
		</fieldset>
		<div class="buttons">
		<div class="pull-right">
		<input id="button-cot-payment-next" class="btn btn-primary" data-loading-text="Cargando..." type="button" value="Siguiente" />
		</div>
		</div>		
		</form>		
		<?php
	}
}
echo "</div>";
echo "</div>";
?>

