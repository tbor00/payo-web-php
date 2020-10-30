<?php
if ($op!=sea) {
	if ($op=='') {
		$op=1;
	}
	$db = connect();
	if ($sop!='') {
		$xop=$sop;
	} else {
		$xop=$op;
	}
	$infadic_query = "SELECT e_infadic.titulo, e_infadic.texto, e_infadic.mtitulo FROM e_infadic WHERE e_infadic.texto<>\"\" AND e_infadic.activo=1 ORDER BY e_infadic.posicion";
	$infadic_res = $db->Execute( $infadic_query );
	if( !$infadic_res->EOF ){ 
		while( !$infadic_res->EOF ){ 
			echo "<BR>";
			echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\" ALIGN=\"CENTER\" WIDTH=\"95%\">"; 
			if ($infadic_res->fields['mtitulo']==1){
				echo "<TR><TD CLASS=\"titinfadic\" ALIGN=\"CENTER\"><STRONG>{$infadic_res->fields['titulo']}</STRONG></TD></TR>"; 
			}
			echo "<TR><TD VALIGN=\"TOP\" ALIGN=\"CENTER\">{$infadic_res->fields['texto']}</TD></TR>\n"; 
			echo "</TABLE>";
			$infadic_res->MoveNext();
		}
	}
}
?>
