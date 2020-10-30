<SCRIPT>
$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:false
		}
	});

});
//---------------------------------------------------------
</SCRIPT>

<?php
$destino = $_SESSION['nivel']+1;
if ($novedad==''){

	if ($nanteriores=='yes'){
		$where = "where activo='1' AND fecha_fin < now() AND (destino=0 OR destino=$destino)";
		$orderby = "order by fecha_fin,fecha_inicio DESC";
	} else {
		$where = "where activo='1' AND fecha_fin > now() AND fecha_inicio <= now() AND (destino=0 OR destino=$destino)";
		$orderby = "ORDER BY fecha_inicio DESC";
	}

	$query = "SELECT count(id_novedad) FROM e_novedades $where";
	$rcount = $db->Execute($query); 
	if ($rcount && !$rcount->EOF){
		$cant_reg = $rcount->fields[0];
	}
	if ($pag == '' || $pag == 0 ){
	 	$pag = 1;
	}
	$from = ($pag-1) * $default->paginarpor;

	if ($nanteriores=='yes'){
		$limit = "limit $from, {$default->paginarpor}";
	} else {
		$limit = "";
	}


	if ($nanteriores=='yes'){
		$nov_query=" select id_novedad,titulo,imagen,img_align,img_epigrafe,sumario,fecha_inicio from e_novedades $where $orderby $limit";
	} else {
		$nov_query=" select id_novedad,titulo,imagen,img_align,img_epigrafe,sumario,fecha_inicio from e_novedades $where $orderby $limit";
   }

	$db = connect();
	$db ->debug = SDEBUG;
	$nov_res = $db->Execute( $nov_query );
	$nn=0;
	
	if ($nov_res && !$nov_res->EOF){
		echo "<div class=\"row\">";
	   echo "<div class=\"col-sm-12\">\n";

		echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">";
		echo "<TR>";
		echo "<TD>";


		while( !$nov_res->EOF ){
			$fini = timesql2std($nov_res->fields['fecha_inicio']);
			if ($nn){
				echo "<BR><TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\"><TR><TD BGCOLOR=\"#dedede\" HEIGHT=\"2\"></TD></TR></TABLE>";
			}
			echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\">";
			echo "<TR><TD ALIGN=\"RIGHT\"><SPAN CLASS=\"volanta\">$fini</SPAN></TD></TR>";
			echo "<TR>";
		   if ($nov_res->fields['imagen']==''){
				echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\"><h4><A HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&novedad={$nov_res->fields['id_novedad']}&nanteriores=$nanteriores\">{$nov_res->fields['titulo']}</A></h4>";
				echo "<SPAN CLASS=\"sumario\">{$nov_res->fields['sumario']}</SPAN>";
				echo "<DIV STYLE=\"padding-top: 10pt\"><A HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&novedad={$nov_res->fields['id_novedad']}&nanteriores=$nanteriores\" CLASS=\"btn\"><i class=\"fa fa-search\"></i>&nbsp; Ver m&aacute;s</A></DIV></TD>\n";
			} else {
				if ($nov_res->fields['img_align']=='0'){
					$styleimg="float: left; margin-right: 5pt; margin-left: 5pt; margin-top: 5pt; margin-bottom: 5pt";
				} else {
					$styleimg="float: right; margin-right: 5pt; margin-left: 5pt; margin-top: 5pt; margin-bottom: 5pt";
				}
				echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">";
				echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\">";
				echo "<TR>";
				echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">";
				echo "<ul class=\"thumbnails\" STYLE=\"{$styleimg}\">";
				echo "<li><A class=\"thumbnail\" HREF=\"novedades/imagenes/{$nov_res->fields['imagen']}\"><IMG SRC=\"novedades/miniaturas/{$nov_res->fields['imagen']}\" BORDER=\"0\" ALT=\"Ampliar\"></A></li>";
				echo "<li>{$nov_res->fields['img_epigrafe']}</li>";
				echo "</ul>";
				echo "<DIV CLASS=\"tit_noticia\"><h4><A CLASS=\"tit_noticia\" HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&novedad={$nov_res->fields['id_novedad']}&nanteriores=$nanteriores\">{$nov_res->fields['titulo']}</A></h4></DIV>";
				echo "<SPAN CLASS=\"sumario\">{$nov_res->fields['sumario']}</SPAN></TD>";
				echo "</TR>";
				echo "<TR><TD CLASS=\"text-left\"><BR><A HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&novedad={$nov_res->fields['id_novedad']}&nanteriores=$nanteriores\" CLASS=\"btn\"><i class=\"fa fa-search\"></i>&nbsp; Ver m&aacute;s</A></TD></TR>";
				echo "</TABLE></TD>";
			}
			echo "</TR>";
			echo "</TABLE>";

			$nn+=1;
			$nov_res->MoveNext();
		}
		echo "</TD>";
		echo "</TR>";

		echo "<TR><TD>&nbsp;</TD></TR>";		
		echo "<TR><TD>";

		echo "</TD></TR>\n";
		echo "</TABLE>";
		echo "</div>";
	   echo "</div>\n";
	}
} else {
	$db = connect();
	$db ->debug = SDEBUG;
	$nov_query = "select * from e_novedades where activo='1' AND id_novedad=$novedad";    
	$nov_res = $db->Execute( $nov_query );
	if ($nov_res && !$nov_res->EOF){				  
		$fini = timesql2std($nov_res->fields['fecha_inicio']);  // ??

		echo "<div class=\"row\">";
	   echo "<div class=\"col-sm-12\">\n";

		echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\">\n";
		echo "<TR><TD ALIGN=\"RIGHT\"><SPAN CLASS=\"volanta\">$fini</SPAN></TD></TR>\n";
		echo "<TR>";

	   if ($nov_res->fields['imagen']==''){
			echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\"><DIV CLASS=\"tit_noticia\"><h4>{$nov_res->fields['titulo']}</h4></DIV>";
			echo "<SPAN CLASS=\"sumario\">{$nov_res->fields['novedad']}</SPAN></TD>\n";
		} else {
			if ($nov_res->fields['img_align']=='0'){
				$styleimg="float: left; margin-right: 5pt; margin-left: 5pt; margin-top: 5pt; margin-bottom: 5pt";
			} else {
				$styleimg="float: right; margin-right: 5pt; margin-left: 5pt; margin-top: 5pt; margin-bottom: 5pt";
			}
			echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">\n";
			echo "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
			echo "<TR>\n";
			echo "<TD ALIGN=\"LEFT\" VALIGN=\"TOP\">";
			echo "<ul class=\"thumbnails\" STYLE=\"{$styleimg}\">";
			echo "<li><A class=\"thumbnail\" HREF=\"novedades/imagenes/{$nov_res->fields['imagen']}\"><IMG SRC=\"novedades/miniaturas/{$nov_res->fields['imagen']}\" BORDER=\"0\" ALT=\"Ampliar\"></A></li>";
			echo "<li>{$nov_res->fields['img_epigrafe']}</li>";
			echo "</ul>";
			echo "<DIV CLASS=\"tit_noticia\"><h4>{$nov_res->fields['titulo']}</h4></DIV><SPAN CLASS=\"noticia\">{$nov_res->fields['novedad']}</SPAN></TD>\n";
			echo "</TR>\n";
			echo "</TABLE></TD>\n";
		}
		echo "</TR>\n";
		echo "<TR><TD><BR><A HREF=\"{$_SERVER['PHP_SELF']}?op=$op&sop=$sop&nanteriores=$nanteriores\" CLASS=\"btn\"><i class=\"fa fa-chevron-circle-left\"></i>&nbsp;Volver</A></TD></TR>\n";
		echo "</TABLE>\n";
		echo "</div>";
	   echo "</div>\n";

	}
}	  
?>
