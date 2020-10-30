<SCRIPT>
//---------------------------------------------------------------------------------
function DeletePedido(pednum){
	var lineas=2;
	if (lineas > 1){
		jConfirm('Desea eliminar el Pedido?', 'Eliminar', function(r) {
			if(r) {
				url = "<?php echo $_SERVER['PHP_SELF']."?op=$op&sop=$sop&cotizacion=delete&cotizanum=" ?>" + pednum ;
				document.location=url;
			}
		})
	}
}
//---------------------------------------------------------------------------------
function DuplicatePedido(pednum){
	var lineas=2;
	if (lineas > 1){
		jConfirm('Desea duplicar el Pedido?', 'Duplicar', function(r) {
			if(r) {
				url = "<?php echo "auxiliar.php?op=procesar&idm=$op&idsm=$sop&cotizacion=duplicate&cotizanum=" ?>" + pednum ;
				document.location=url;
			}
		})
	}
}
//---------------------------------------------------------------------------------
</SCRIPT>
<?php
if ($_POST['buscar']!=''){
	$buscar = $_POST['buscar'];			  
} elseif ($_GET['buscar']!=''){
	$buscar = $_GET['buscar'];
}
if ($_POST['ord']!=''){
	$ord = $_POST['ord'];
} elseif ($_GET['ord']!=''){
	$ord = $_GET['ord'];
}
if ($_POST['orden']!=''){
	$orden = $_POST['orden'];
} elseif ($_GET['orden']!=''){
	$orden = $_GET['orden'];
}

$pag = $_GET['pag'];

if ($orden!='id_cotiza' && $orden!='referencia' && $orden!='fecha'){
  $orden="id_cotiza";
}

if ($ord != "ASC" && $ord != "DESC"){
  $ord="DESC";
}

if ($cotizacion=="delete"){
	$query = "DELETE FROM e_cotiza_lineas WHERE id_cot=$cotizanum";
	if ($delete_r = $db->Execute($query)){
		$query = "UPDATE e_cotiza set user_id=0, estado=10, comentario='ANULADA'  WHERE id_cotiza=$cotizanum";
		$delete_cl = $db->Execute($query);
	}
	echo "<script type=\"text/javascript\">\n";
	echo "$(window).bind(\"load\", function() {\n";
	echo "setTimeout(function () {\n";
	echo "$('#cart > button').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ ".$cotizanum." );\n";				
	echo "}, 100);\n";
	echo "$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+".$cotizanum." +' ul li');\n";
	echo "});\n";
	echo "</script>\n";	
} elseif ($cotizacion=="save"){
	echo "<script type=\"text/javascript\">\n";
	echo "$(window).bind(\"load\", function() {\n";
	echo "setTimeout(function () {\n";
	echo "$('#cart > button').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ ".$cotizanum." );\n";				
	echo "}, 100);\n";
	echo "$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+".$cotizanum." +' ul li');\n";
	echo "});\n";
	echo "</script>\n";	
}	

$query = "SELECT COUNT(*) FROM e_cotiza WHERE user_id=$id_usuario ";
$count = $db->Execute($query); 
if ($count && !$count->EOF){
	$cant_reg = $count->fields[0];
}
echo "<BR>\n";

if (isset($orden) and strlen($orden)>0) {
	$url_orden="&orden=$orden&ord=$ord";
	$ORDER="order by $orden $ord";
}
if (isset($gotopag)){
	if ($gotopag > ceil($cant_reg/$qlineas)) {
		$pag = ceil($cant_reg/$qlineas);
	} elseif ($gotopag < 0){
		$pag = 1;
	} else {
		$pag = $gotopag;
	}
}
if ($pag == '' || $pag == 0 || !is_numeric($pag)){
 	$pag = 1;
}
$from = ($pag-1) * $qlineas;

$query = "SELECT * FROM e_cotiza WHERE user_id=$id_usuario $ORDER limit $from, $qlineas";
if ($cotizac_r=$db->Execute($query)){

	echo "<div class=\"row\">";
	echo "<div class=\"col-sm-8 col-sm-offset-2 table-responsive\">";
	echo "<table class=\"table table-bordered table-hover\">\n";
	echo "<thead>\n";
	echo "<TR>\n";
	echo "<td></td>";
	echo Titulo_Browse("N&uacute;mero","id_cotiza",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",1);
	echo Titulo_Browse("Fecha","fecha",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",1);
	echo Titulo_Browse("Referencia","referencia",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",1);
	echo Titulo_Browse("Estado","estado",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
	echo "</TR>\n";
	echo "</thead>\n";
	
	if ($cotizac_r->EOF){
		echo "<TR>\n";
		echo "<TD COLSPAN=\"4\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">".translate("No se encontraron Pedidos")."</TD>\n";
		echo "</TR>\n";
	}
	while (!$cotizac_r->EOF){
		echo "<TR>\n";
		echo "<TD class=\"text-center\">";
		echo "<a href=\"".$_SERVER['PHP_SELF']."?op=$op&sop=$sop&cotizacion=show&cotizanum=".$cotizac_r->fields['id_cotiza']."\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-xs-v\" data-original-title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
		if ($cotizac_r->fields['estado'] == 0 || $cotizac_r->fields['estado'] == 1 ){
			echo "<button type=\"button\" onclick=\"javascript:DeletePedido('".$cotizac_r->fields['id_cotiza']."')\" data-toggle=\"tooltip\" title=\"Eliminar\" class=\"btn btn-danger btn-xs-v\" data-original-title=\"Eliminar\"><i class=\"fa fa-trash\"></i></button>\n";
		} else {	
			echo "<button type=\"button\" onclick=\"javascript:DuplicatePedido('".$cotizac_r->fields['id_cotiza']."')\" data-toggle=\"tooltip\" title=\"Duplicar\" class=\"btn btn-success btn-xs-v\" data-original-title=\"Duplicar\"><i class=\"fa fa-clone\"></i></button>\n";
		}	
		echo "</td>\n";
		echo "<TD class=\"text-left\">".str_pad($cotizac_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</td>\n";
		echo "<TD class=\"text-left\">".timest2dt($cotizac_r->fields['fecha'])."</TD>\n";
		echo "<TD class=\"text-left\">".htmlentities($cotizac_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD>\n";
		if ($cotizac_r->fields['estado'] == 2){
			$estado = translate("Enviado");
		} elseif ($cotizac_r->fields['estado'] == 1){
			$estado = translate("Borrador"); 
		} else {
			$estado = translate("Activo");
		}
		echo "<TD class=\"text-center\">$estado</TD>\n";
		echo "</TR>\n";
		$cotizac_r->MoveNext();
	}
	echo "</TABLE>\n";
	echo paginar($pag, $cant_reg, $qlineas, $_SERVER['PHP_SELF']."?op=$op&sop=$sop$url_orden$url_busqueda&pag=");
	echo "</div></div>";
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>