<?php
$pag = $_GET['pag'];
$qlineas = 30;
$id_usuario=$_SESSION['uid'];
$saldo_ctacte = 0;
if ($default->enable_gestion){
	if (isset($_GET['gestion'])){
		if (strlen($_GET['gestion'])>1){
			$_SESSION['gestion'] = '';
			$gestion=$_SESSION['gestion'];
		} else {	
			$_SESSION['gestion'] = $_GET['gestion'];
			$gestion=$_SESSION['gestion'];
		}
	} else {
		$gestion=$_SESSION['gestion'];
	}
} else {	
	$gestion='';
	$_SESSION['gestion']=$gestion;
}

$user_query = "select * from e_webusers where user_id=$id_usuario" ;
$user_res=$db->Execute($user_query);
if($user_res && ! $user_res->EOF ){
	$cliente_eb=$user_res->fields['eb_cod'];
	$g_query="select e_gestiones.letra,e_gestiones.gestion from e_gestiones LEFT JOIN e_user_gestiones ON e_gestiones.id_gestion=e_user_gestiones.gestion_id WHERE user_id=$id_usuario ORDER BY e_gestiones.gestion";
	$g_res=$db->Execute($g_query);
	if($g_res && ! $g_res->EOF ){
		while (!$g_res->EOF){
			$ng++;
			$a_gestiones[] = array('letra'=>$g_res->fields['letra'],
				'gestion'=>$g_res->fields['gestion']);
			$g_res->MoveNext();
		}
		if ($ng==1){
			$_SESSION['gestion']=$a_gestiones[0]['letra'];
			$gestion=$_SESSION['gestion'];
		}	
	}	$query = "SELECT COUNT(id) FROM e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
	AND cliente_eb=$cliente_eb AND gestion='".$gestion."'";
	$count = $db->Execute($query); 
	if ($count && !$count->EOF){
		$cant_reg = $count->fields[0];
	}
	$maxpag = ceil($cant_reg/$qlineas);
	if ($maxpag==0){
	    $maxpag=1;
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
		$pag = $maxpag;
	}
	$from = ($pag-1) * $qlineas;
	if ($pag > 1){
		$query = "SELECT sum(saldo) as saldo_cte FROM (select IF(credito>0,saldo*-1,saldo) as saldo from e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
		 AND cliente_eb=$cliente_eb AND gestion='".$gestion."' ORDER by fecha,id limit 0,$from) as rowtt";
		if ($ctacte_r=$db->Execute($query)){
			$saldo_ctacte = $ctacte_r->fields['saldo_cte'];
		}	
	}
	$query = "SELECT (credito*-1+debito) as importe,comprobante,numero, IF(credito>0,saldo*-1,saldo) as saldo ,referencia,fecha,vto FROM e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
	          AND cliente_eb=$cliente_eb AND gestion='".$gestion."' ORDER by fecha,id limit $from, $qlineas";
	if ($ctacte_r=$db->Execute($query)){
		echo "<div class=\"row\">";
		if ($default->enable_gestion && sizeof($a_gestiones)>1){
			echo "<div class=\"col-sm-3 text-left\">\n";
			?>
<SCRIPT>
//---------------------------------------------------------
function filtragestion(form){
  var myindex1=form.gestion.selectedIndex;
  location="index.php?op="+form.op.value+"&gestion="+form.gestion.options[myindex1].value;
}
//---------------------------------------------------------			
</SCRIPT>
<?php			
			echo "<FORM ACTION=\"".$_SERVER[PHP_SELF]."\" METHOD=\"POST\" NAME=\"ctasctes\">\n";
			echo "<INPUT NAME=\"op\" VALUE=\"$op\" TYPE=\"HIDDEN\">\n";
			echo "<INPUT NAME=\"sop\" VALUE=\"$sop\" TYPE=\"HIDDEN\">\n";
			echo "<INPUT NAME=\"orden\" VALUE=\"$orden\" TYPE=\"HIDDEN\">\n";
			echo "<INPUT NAME=\"ord\" VALUE=\"$ord\" TYPE=\"HIDDEN\">\n";
			echo "<select name=\"gestion\" id=\"input-type\" class=\"form-control\" onchange=\"filtragestion(this.form)\">\n";
			foreach ($a_gestiones as $k => $gescu) {
				if ($gestion==$gescu['letra']){
					echo "<option value=\"".rawurlencode($gescu['letra'])."\" selected>".htmlentities($gescu['gestion'],ENT_QUOTES,$default->encode)."</option>\n";
				} else {	
					echo "<option value=\"".rawurlencode($gescu['letra'])."\">".htmlentities($gescu['gestion'],ENT_QUOTES,$default->encode)."</option>\n";
				}
			}			
			echo "</select>\n";
			echo "</div>";
			echo "<div class=\"col-sm-9 text-right\">\n";
			echo "<A HREF=\"auxiliar.php?op=caprint\" TARGET=\"_print\"  class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Versi&oacute;n para Imprimir\"><i class=\"fa fa-print\"></i></A>";
			echo "</div>";
		} else {
			echo "<div class=\"col-sm-12 text-right\">\n";
			echo "<A HREF=\"auxiliar.php?op=caprint\" TARGET=\"_print\"  class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Versi&oacute;n para Imprimir\"><i class=\"fa fa-print\"></i></A>";
			echo "</div>";
		}
		echo "</div>";
		echo "<br>\n";
	
		echo "<div class=\"row\">";
		echo "<div class=\"table-responsive\">";
		echo "<div class=\"col-sm-8 col-sm-offset-2\">";
		echo "<table class=\"table \">\n";
		echo "<thead>\n";
		echo "<TR>\n";
		echo Titulo_Browse("Comprobante","comprobante",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0);
		echo Titulo_Browse("Fecha","fecha",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
		echo Titulo_Browse("Vto.","vto",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
		echo Titulo_Browse("Ref.","referencia",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0);
		echo Titulo_Browse("Importe","credito",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
		echo Titulo_Browse("Saldo Comp.","saldo",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
		echo Titulo_Browse("Saldo","saldo_ctate",$orden,$ord,"$PHP_SELF?op=$op&sop=$sop&pag=$pag$url_busqueda",0,0,"center");
		echo "</TR>\n";
		echo "</thead>\n";
		
		if ($ctacte_r->EOF){
			echo "<TR>\n";
			echo "<TD COLSPAN=\"7\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">".translate("No se encontraron Comprobantes")."</TD>\n";
			echo "</TR>\n";
		}
		while (!$ctacte_r->EOF){
			$saldo_ctacte = $saldo_ctacte + $ctacte_r->fields['saldo'];
			echo "<TR>\n";
			echo "<TD class=\"text-left\">".$ctacte_r->fields['comprobante'].'&nbsp;'.$ctacte_r->fields[numero]."</TD>\n";
			echo "<TD class=\"text-right\">".timesql2std($ctacte_r->fields['fecha'])."</td>\n";
			echo "<TD class=\"text-right\">";
			if ($ctacte_r->fields['comprobante'] != 'FAC' && $ctacte_r->fields['comprobante'] != 'NDB' && $ctacte_r->fields['comprobante'] != 'TIQ'){
				echo "";
			} else {
				echo timesql2std($ctacte_r->fields['vto']);
			}
			echo "</TD>\n";		
			echo "<TD class=\"text-left\">".htmlentities($ctacte_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD class=\"text-right\">";
			echo sprintf('%01.2f',$ctacte_r->fields['importe']);
			echo "</TD>\n";		
			echo "<TD class=\"text-right\">";
			echo sprintf('%01.2f',$ctacte_r->fields['saldo']);
			echo "</TD>\n";		
			echo "<TD class=\"text-right\">".sprintf('%01.2f',$saldo_ctacte)."</TD>\n";		
			echo "</TR>\n";
			$ctacte_r->MoveNext();
		}
		$total_paginas = ceil($cant_reg/$qlineas);
		if ($total_paginas == $pag){
			$queryctactei = "Select * from e_ctasctes_import ORDER BY fecha DESC";
			$ctactei_r = $db->Execute($queryctactei);
			if (!$ctactei_r->EOF){
				echo "<tr><td colspan=\"7\">Saldo al ".timest2dt($ctactei_r->fields['fecha'])."</td></tr>";
			}
		}	
		echo "</TABLE>\n";
		echo "</div></div>";
		echo paginar($pag, $cant_reg, $qlineas, $_SERVER['PHP_SELF']."?op=$op&sop=$sop$url_orden$url_busqueda&pag=");
		echo "</div>";
	}
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>