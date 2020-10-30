<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" BGCOLOR="<?php echo $default->border_color ?>">
<TR><TD>
<?php
$column = 1;
$upimg = "<IMG SRC='img/up.gif' BORDER='0' ALT='Orden descendente' ALIGN='ABSMIDDLE'>";
$downimg = "<IMG SRC='img/down.gif' BORDER='0' ALT='Orden descendente' ALIGN='ABSMIDDLE'>";
$upimg_s = "<IMG SRC='img/up_s.gif' BORDER='0' ALT='Orden ascendente' ALIGN='ABSMIDDLE'>";
$downimg_s = "<IMG SRC='img/down_s.gif' BORDER='0' ALT='Orden ascendente' ALIGN='ABSMIDDLE'>";
echo "<TABLE BORDER='0' WIDTH='100%' CELLPADDING='2' CELLSPACING='1'>\n";
echo "<TR><TH ALIGN='CENTER' BGCOLOR='$default->table_bgheadercolor' WIDTH='10%' VALIGN='MIDDLE'></TH>\n";
foreach($config['campos'][0] as $k => $v){
	if($v!=""){
		$column++;
		if (preg_match("/binario/i",$k) || preg_match("/state/i",$k)){
			$campo = $config['campos'][0][$k]['campo'];
			$nombre = $config['campos'][0][$k]['nombre'];
			echo "<TH ALIGN='LEFT' BGCOLOR='$default->table_bgheadercolor' WIDTH='' VALIGN='MIDDLE' STYLE='color: $default->table_headercolor'>";
			echo convert_str($nombre,$default->encode);
			if ($campo==$config['orden']) {
				if ($config['ord'] == "ASC" ) {
					echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg_s</A>&nbsp;";
					echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=DESC$url_busqueda$url_idioma$url_parent'>$downimg</A>";
				} else {
					echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg</A>&nbsp;";
					echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=DESC$url_busqueda$url_idioma$url_parent'>$downimg_s</A>";
				}
			} else {
				echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg</A>&nbsp;";
				echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$campo&ord=DESC$url_busqueda$url_idioma$url_parent'>$downimg</A>";
			}
			echo "</TH>\n";
		}else{
			echo "<TH ALIGN='LEFT' BGCOLOR='$default->table_bgheadercolor' WIDTH='' VALIGN='MIDDLE' STYLE='color: $default->table_headercolor'>";
			echo convert_str($v,$default->encode);															  
			if ($k==$config['orden']) {
				if ($config['ord'] == "ASC" ) {
					echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg_s</A>&nbsp;";
					echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=DESC$url_idioma$url_busqueda$url_parent'>$downimg</A>";
				} else {
					echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg</A>&nbsp;";
					echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=DESC$url_busqueda$url_idioma$url_parent'>$downimg_s</A>";
				}
			} else {
				echo "&nbsp;<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=ASC$url_busqueda$url_idioma$url_parent'>$upimg</A>&nbsp;";
				echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=l&pag=$config[pag]&orden=$k&ord=DESC$url_busqueda$url_idioma$url_parent'>$downimg</A>";
			}
			echo "</TH>\n";
		}
	}
}

while( !$result->EOF ){
	echo "<TR BGCOLOR='$default->row_bgcolor' onmouseout=\"style.backgroundColor='$default->row_bgcolor'\" onmouseover=\"style.backgroundColor='$default->row_bgcolor_over'\" >\n";
	echo "<TD ALIGN='center' WIDTH='10%' VALIGN='MIDDLE' >";
	$id=$result->fields[$config['id']];
	if (is_array($config['plugin'])){
	   for($p=0;$p<count($config['plugin']);$p++){
			include($config['plugin'][$p]);
			echo "<IMG SRC='img/blank.gif' BORDER='0' HEIGHT='20' WIDTH='5'>";
		}
	}
	if($config['delete']=="yes"){
		echo "<A  TITLE='Eliminar' HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=e&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='./img/delete.gif' ALT='Eliminar' BORDER='0' TITLE='Eliminar'></A>";
	}
	if($config['edit']=="yes"){
		echo "<A  TITLE='Modificar' HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=m&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='./img/edit.gif' ALT='Modificar' BORDER='0' TITLE='Modificar'></A>";
	}
	if($config['export']!=""){
		echo "<A HREF=\"javascript:{$config['export']}($id)\"><IMG SRC='./img/excel.gif' ALT='Exportar' BORDER='0' TITLE='Exportar'></A>";
	}
	if($config['enviar']!=""){
		echo "<A HREF=\"javascript:{$config['enviar']}($id)\"><IMG SRC='./img/enviarnews.gif' ALT='Enviar' BORDER='0' TITLE='Enviar'></A>";
	}
	if($config['pass']=="yes"){
		echo "<A HREF='{$_SERVER['PHP_SELF']}?accion={$config[accion]}&op=p&id=$id&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]$url_idioma$url_busqueda$url_parent'><IMG SRC='./img/clave.gif' ALT='Cambiar Clave' BORDER='0' TITLE='Password'></A>";
	}
	echo "</TD>\n";
	$nr_row = 0;
	$nr_col = $default->first_column_table;
	foreach($config['campos'][0] as $k => $v){
		if($v!=""){
			echo "<TD WIDTH='' VALIGN='MIDDLE' ALIGN='LEFT' >";
			if (preg_match("/binario/i",$k)){
				if($result->fields[$config['campos'][0][$k]['campo']]){
					echo "Si";
				}else{
					echo "No";
				}
			} elseif (preg_match("/state/i",$k)){
				if($result->fields[$config['campos'][0][$k]['campo']]== '0'){
					echo "Activo";
				}elseif($result->fields[$config['campos'][0][$k]['campo']]== '1'){
					echo "Borrador";
				}elseif($result->fields[$config['campos'][0][$k]['campo']]== '2'){
					echo "Enviado";
				}elseif($result->fields[$config['campos'][0][$k]['campo']]== '10'){
					echo "Anulado";
				}
				
				
			}else{
				$fd = $result->FetchField($nr_col);
				$fd_tipo = $result->MetaType($fd->type);
				if ( $fd_tipo =='D'){
					echo timesql2std($result->fields[$nr_col]);
				} elseif ($fd_tipo=='T'){
					echo timest2dt($result->fields[$nr_col]);
				} else {
		   		if (strlen($result->fields[$nr_col]) > 200 ) {
		   			echo nl2br(wordwrap($result->fields[$nr_col],100));
					} else {
						echo $result->fields[$nr_col];
					}
				}
			}
			echo "&nbsp;</TD>\n";
		}
		$nr_row+=1;
		$nr_col++;
	}
	echo "</TR>\n";
	$result->MoveNext();
}
if ($nr_row==0){
	echo "<TR BGCOLOR='$default->row_bgcolor' HEIGHT='30'><TD ALIGN='center' VALIGN='MIDDLE' COLSPAN='$column' ><STRONG STYLE='color:red'>No se encontraron registros</STRONG></TD></TR>\n";
}
echo "</TD></TABLE>\n";
?>
</TD></TR></TABLE><BR>

