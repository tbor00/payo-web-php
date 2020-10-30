<?php
echo "<div class=\"row\">";
echo "<div class=\"col-sm-12\">";
if ($_SESSION['logged']){

	if ($_POST['idt']!=''){
		$idt = $_POST['idt'];			  
		$total_cc = $_POST['total_cc'];
	} elseif ($_GET['idt']!=''){
		$idt = $_GET['idt'];
		$total_cc = $_GET['total_cc'];
	}
	$id_usuario=$_SESSION['uid'];
	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_descuentos WHERE id_descuento='$idt'";
	$det_desc=$db->Execute($query);
	if ($det_desc && !$det_desc->EOF){
		echo "<script>";
	   echo "$( document ).ready(function() {";
		echo "var parentDoc = parent.document;";
		echo "var total_cc = parentDoc.getElementById('total_c').innerHTML;";
	   echo "document.getElementById('ccuotas').innerHTML = '$ ' + total_cc;";
	   echo "});";

		echo "function calcular_cuotas(form){";
		echo "var myindex2=form.qcuotas.selectedIndex;";
		echo "var mycuota=form.qcuotas.options[myindex2].value;";
		echo "if (mycuota>1){";
		echo "document.getElementById('tcuotas').innerHTML = '&nbsp; cuotas de &nbsp;';";
		echo "} else {";
		echo "document.getElementById('tcuotas').innerHTML = '&nbsp; cuota de &nbsp;';";
		echo "}";
		echo "var parentDoc = parent.document;";
		echo "var total_cc = parentDoc.getElementById('total_c').innerHTML;";
		echo "document.getElementById('ccuotas').innerHTML = '$ ' + (total_cc / mycuota).toFixed(2);";
		echo "}";
		echo "</script>";
		if($det_desc->fields['imagen']){
			echo "<div class=\"row\">";
			echo "<div class=\"col-sm-12 text-center\">";
			$p_image = similar_file_exists("descuentos/imagenes/".$det_desc->fields['imagen']);
		   $partes_ruta = pathinfo($p_image);
			if (file_exists("descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']))){
				$t_image = "descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']);
			}else{
				convert_image($p_image,"descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']),"130x200","80");
				$t_image = "descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']);
			}
			echo "<img src=\"".$t_image."\" alt=\"".htmlentities($det_desc->fields['leyenda'],ENT_QUOTES,$default->encode)."\" class=\"img-responsive\" />\n";
			echo "</div>";
			echo "</div>";
			echo "<br>";
		}
		echo "<div class=\"row\">";
		echo "<div class=\"col-md-12 text-center\">";
		echo "<form name=\"calculo_cuotas\">";
		echo "<select name=\"qcuotas\" id=\"qcuotas\" class=\"form-cuotas\" onchange=\"javascript:calcular_cuotas(this.form)\">";
		echo "<option value=\"1\" selected>1</option>";
		for ($n=2;$n<=$det_desc->fields['cuotas'];$n++){
			echo "<option value=\"$n\">$n</option>";
		}
		echo "</select>";
		echo "<span id=\"tcuotas\" style=\"font-size: 14px;font-weight: bold;\">&nbsp; cuota de &nbsp;</span><span id=\"ccuotas\" class=\"cuotas-amount\">".$total_cc."</span>";
		echo "</form>";
		echo "</div>";
		echo "</div>";
		if ($det_desc->fields['informacion']){
			echo "<br>";
			echo "<div class=\"row\">";
			echo "<div class=\"col-md-12\">";
			echo "<div class=\"cuotas-info\">".nl2br(htmlentities($det_desc->fields['informacion'],ENT_QUOTES,$default->encode))."</div>";
			echo "</div>";
			echo "</div>";
		}

	} else {
		echo "<div class=\"text-center\">ERROR: Descuento no encontrado.</div>";
	}
}
echo "</div>";
echo "</div>";
?>

