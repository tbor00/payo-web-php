<?php
//---------------------------------------------------------------------------
function listar($config){
	global $default, $PHP_SELF;
	$db = connect();
	$db->debug = SDEBUG;
	if (isset($config['orden']) and strlen($config['orden'])>0) {
		$ORDER="ORDER BY $config[orden] $config[ord]";
	}
	if(!isset($config['condicion']) || $config['condicion']==""){
		if (preg_match("/oci8/i",$default->dbtype)){
			$config['condicion']="1=1";			
		} elseif (preg_match("/postgres/i",$default->dbtype)){
			$config['condicion']="true";
		} else {
			$config['condicion']="1";
		}
	}
	if( strlen($config['buscar'])>0 && strlen($config['buscarx'])>0){
		$config['condicion']="$config[condicion] AND $config[buscarx] LIKE '%$config[buscar]%'";
	}
	foreach($config['campos'][0] as $k => $v){
		if (preg_match("/binario/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if (preg_match("/state/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if(!isset($campos)){
			$campos=$k;
		} else {
			$campos.=",$k";
		}
	}
	$query = "SELECT count(*) FROM $config[tabla] $config[join] WHERE $config[condicion]";
	$count = $db->Execute($query); 
	if ($count){
		$cant_reg = $count->fields[0];	
	}
	if ($config['pag'] > ceil($cant_reg/$config['lineas'])) {
		$config['pag'] = ceil($cant_reg/$config['lineas']);
	} 
        if ($config['pag'] <= 0){
		$config['pag'] = 1;
	}
	$reg_from =  ($config['pag']-1) * $config['lineas'];
	$reg_to = $reg_from + $config['lineas'];
	if (preg_match("/oci8/i",$default->dbtype)){
		$query = "SELECT * FROM (SELECT ROWNUM AS LIMIT, T.* FROM (SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER) T) WHERE LIMIT BETWEEN $reg_from AND $reg_to";
	} elseif (preg_match("/postgres/i",$default->dbtype)){
		$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER LIMIT $config[lineas] OFFSET $reg_from";
	} else {
		$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER LIMIT $reg_from,$config[lineas]";
	}
	if ($result = $db->Execute($query)) {
		$n_reg = $reg_from+$config['lineas'];
		$sq_reg = $result->RecordCount() + $reg_from;
		if ( $sq_reg >= $cant_reg ) {
			$sq_reg = $cant_reg;
			$n_reg = 0;
		}
		$p_reg = $reg_from-$config['lineas'];
		include('templates/menu_abm.inc.php');
		include($config['include_form']);
	} else {
		Echo_Error(ERROR_DB_CONNECT);
	}
}
//---------------------------------------------------------------------------
function paginar($actual, $total, $por_pagina=20, $enlace) {
	$total_paginas = ceil($total/$por_pagina);
	$anterior = $actual - 1;
	$posterior = $actual + 1;

	$texto = "<TABLE CELLPADDING=\"2\" CELLSPACING=\"0\" BORDER=\"0\">\n";
	$texto .= "<TR VALIGN=\"MIDDLE\">";
	$texto .= "<TD ALIGN=\"CENTER\" CLASS=\"textobrowse\">";

	if ($actual + 4 > $total_paginas){
		$hasta = $total_paginas;
	} else {
  		$hasta = $actual + 4;
	}
	if ($hasta < 10){
		$hasta = 10;
	}
	if ($hasta > $total_paginas){
		$hasta=$total_paginas;
	}

	if ($actual - 5 > 0){
  		$desde = $actual - 5;
	} else {
  		$desde = 1;
	}

	if ($actual + 5 >= $total_paginas){
		$desde = $total_paginas - 9;
	}
	if ($desde <= 0){
		$desde = 1;
	}

	$texto .= "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\">\n";
	$texto .= "<TR VALIGN=\"MIDDLE\">";
	$texto .= "<TD VALIGN=\"MIDDLE\" ALIGN=\"RIGHT\" CLASS=\"textobrowse\">";
	if ($actual > 1){
		$texto .= "<A HREF=\"$enlace&pag=1\"><IMG SRC=\"img/first.gif\" BORDER=\"0\" ALT=\"Primera\" TITLE=\"Primera\"></A>&nbsp;";
		$texto .= "<A HREF=\"$enlace&pag=$anterior\"><IMG SRC=\"img/prev.gif\" BORDER=\"0\" ALT=\"Anterior\" TITLE=\"Anterior\"></A>&nbsp;";
	} else {
		$texto .= "<IMG SRC=\"img/first-grey.gif\" BORDER=\"0\" ALT=\"Primera\">&nbsp;";
		$texto .= "<IMG SRC=\"img/prev-grey.gif\" BORDER=\"0\" ALT=\"Anterior\">&nbsp;";
	}
	$texto .= "</TD>\n";
	$texto .= "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">";
	for ($i=$desde; $i<$actual; $i++) {
		$texto .= "<A CLASS=\"textobrowse\" HREF=\"$enlace&pag=$i\">$i</A> ";
	}
	$texto .= "<STRONG CLASS=\"textobrowse\">$actual</STRONG> ";
	for ($i=$actual+1; $i<=$hasta; $i++){
		$texto .= "<A CLASS=\"textobrowse\" HREF=\"$enlace&pag=$i\">$i</A> ";
	}
	$texto .= "</TD>\n";
	$texto .= "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\" CLASS=\"textobrowse\">";
	if ($actual<$total_paginas){
		$texto .= "&nbsp;<A HREF=\"$enlace&pag=$posterior\"><IMG SRC=\"img/next.gif\" BORDER=\"0\" ALT=\"Siguiente\" TITLE=\"Siguiente\"></A>&nbsp;";
		$texto .= "<A HREF=\"$enlace&pag=$total_paginas\"><IMG SRC=\"img/last.gif\" BORDER=\"0\" ALT=\"Ultima\" TITLE=\"Ultima\"></A>&nbsp;";

	} else {
		$texto .= "&nbsp;<IMG SRC=\"img/next-grey.gif\" BORDER=\"0\" ALT=\"Siguiente\">&nbsp;";
		$texto .= "<IMG SRC=\"img/last-grey.gif\" BORDER=\"0\" ALT=\"Ultima\">&nbsp;";
	}
	$texto .= "</TD>";
	$texto .= "</TR></TABLE>\n";
	$texto .= "</TD>\n";
	$texto .= "</TR></TABLE>\n";
	return $texto;
}
//---------------------------------------------------------------------------
function conf_eliminar($config){
	global $default, $PHP_SELF;
	$db = connect();
	$db->debug = SDEBUG;
	foreach($config['campos'][0] as $k){
		if(!isset($campos)){
			$campos=$k;
		} else {
	    $campos.=",$k";
		}
	}
	$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion]";
	if ($result = $db->Execute($query)) {
		if(strlen($config['delete_dialog']) > 0){
			include($config['delete_dialog']);
		}else{
			include('./include/templates/dialog.inc.php');
		}
	}else{
		Echo_Error(ERROR_DB_CONNECT);
	}
}
//---------------------------------------------------------------------------
function eliminar($config, $dbm=""){
	if($dbm==""){
		$db = connect();
	} else {
		$db = $dbm;
	}
	$db->debug = SDEBUG;
	$query = "DELETE FROM $config[tabla] WHERE $config[condicion]";
	$result = $db->Execute( $query );
	if ($result) {
		dolog($query);
		return 1;
	} else {
		return 0;
	}
}
//---------------------------------------------------------------------------
function listar_modificaciones($config){
	global $default, $PHP_SELF, $upload, $id, $op;
	$db = connect();
	$db->debug = SDEBUG;
	foreach($config['campos'][0] as $k => $v){
		if (preg_match("/binario/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if (preg_match("/state/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if(!isset($campos)){
			$campos=$k;
		} else {
			$campos.=",$k";
		}
   }

	foreach ($default->lenguajes($config['set_idioma'],$db) as $id_l){
		unset($campos_i);
		unset($values_i);
		if($config['set_idioma']=='yes'){
			$config['cond']="$config[condicion] AND {$config[tabla]}.lenguaje_id='$id_l'";
		} else {
			$config['cond']="$config[condicion]";
		}
		$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[cond]";
		$formdata[$id_l] = $db->Execute($query);
		if ($formdata[$id_l]) {
			if ($formdata[$id_l]->EOF && $id_l > 1){
				foreach($config['campos'][0] as $k => $v){
					if (preg_match("/binario/i",$k)){
						$k = $config['campos'][0][$k]['campo'];
					}
					if (preg_match("/state/i",$k)){
						$k = $config['campos'][0][$k]['campo'];
					}
					if ($k == "lenguaje_id"){
						$valor = $id_l;
					} else {
						$valor = $formdata[1]->fields[$k];
					}
					if (isset($campos_i)){
						$campos_i .= ",$k";
						$values_i .= ",".$db->Quote($valor);
					} else {
						$campos_i = $k;
						$values_i = $db->Quote($valor);

					}
			   }
				$llquery = "INSERT INTO $config[tabla] ($campos_i) VALUES ($values_i)";
				$db->Execute($llquery);
		  	}
		} else {
			Echo_Error(ERROR_DB_CONNECT);
		}
	}
	if (file_exists("include/".$config['include_form'])){
		include($config['include_form']);
	} else {
		Echo_Error(ERROR_FORM_NOT_FOUND);
	}
}
//---------------------------------------------------------------------------
function modificar($config, $dbm=""){
	global $default, $PHP_SELF;
	if($dbm==""){
		$db = connect();
	} else {
		$db = $dbm;
	}
	$db->debug = SDEBUG;

	foreach ($default->lenguajes($config['set_idioma'],$db) as $id_l){
		unset($updatees);
		if($config['set_idioma']=='yes'){
 			$config['cond']="$config[condicion] AND lenguaje_id='$id_l'";
		} else {
			$config['cond']="$config[condicion]";
		}
		foreach($config['campos'][$id_l] as $k => $v){
			$v = $db->qstr($v,get_magic_quotes_gpc());
			if(!isset($updatees)){
				$updatees="$k=$v";
			} else {
				$updatees.=",$k=$v";
			}
		}
		$query = "UPDATE $config[tabla] SET $updatees WHERE $config[cond]";
		if ($formdata[$id_l] = $db->Execute($query )) {
			dolog($query);
		} else {
			Echo_Error(ERROR_DB_CONNECT);
			return 0;
		}
	}
	if($config['set_idioma']=='yes'){
		$config['condicion']="lenguaje_id='$config[idioma]'";
	} else {
		unset($config['condicion']);
	}
	return 1;
}
//---------------------------------------------------------------------------
function alta($config, $dbm=""){
   global $default, $PHP_SELF;
	$seq_name = 'seq_'.$config[tabla];
	if($dbm==""){
		$db = connect();
	} else {
		$db = $dbm;
	}
	$db->debug = SDEBUG;
	foreach ($default->lenguajes($config['set_idioma'],$db) as $id_l){
		unset($campos);
		unset($valores);
		foreach($config['campos'][$id_l] as $k => $v){
			if (preg_match("/binario/i",$k)){
				$k=$config['campos'][$k]['campo'];
			}
			if (preg_match("/state/i",$k)){
				$k=$config['campos'][$k]['campo'];
			}
			if($v=="auto_increment"){
				if (!isset($id_v)){
					$id_v = $db->GenID($seq_name);
				}
				$v = $id_v;
			}
			$v = $db->qstr($v,get_magic_quotes_gpc());
			if(!isset($campos)){
				$campos="($k";
				$valores="($v";
			}else{
				$campos.=",$k";
				$valores.=",$v";
			}
		}
		$campos.=")";
		$valores.=")";

		$query = "INSERT INTO $config[tabla] $campos VALUES $valores";
		if($result = $db->Execute($query )){
			dolog($query);
		} else {
			Echo_Error(ERROR_DB_CONNECT);
			return 0;
		}
		
	}
	if (isset($id_v)){
		return $id_v;
	} else {
		return 1;
	}
}
//---------------------------------------------------------------------------
function controlar($config,$dialogo=0){
   global $default, $glo_form_tit, $PHP_SELF, $upload, $id;
	$db = connect();
	$db->debug = SDEBUG;
	if (!isset($config['control']) || $config['control']==""){
		return 1;
	}
	if ($config[op]!="cm" && $config[op]!='ca') {
		foreach($config['control'] as $k => $v){
			//$cond = $v[condicion];
			list($cond,$msg)=split(":",$v);
			$query = "SELECT count(*) FROM $k WHERE $cond";
			$resultq = $db->Execute( $query );
			$resultado = $resultq->fields[0];
			if ($resultado) {
				if ($dialogo==0){
					include('./include/templates/dialogmsg.inc.php');
				} else {
					include('./include/templates/dialogmsgn.inc.php');
				}
				return 0;
			}
		}
	} else {
		foreach($config['control'] as $k => $v){
			$query = "select count(*) from $k where $v";
			$resultq = $db->Execute( $query );
			$resultado = $resultq->fields[0];
			if ($resultado) {
				include('templates/form_header.inc.php');
				foreach ($default->lenguajes($config['set_idioma'],$db) as $id_l){
					if(isset($config['campos'][$id_l])){
	   				foreach($config['campos'][$id_l] as $k => $v){
							if (preg_match("/binario/i",$k)){
								$k=$config['campos'][$id_l][$k]['campo'];
							}
							if (preg_match("/state/i",$k)){
								$k=$config['campos'][$id_l][$k]['campo'];
							}
							if($v=="auto_increment"){
								$v = '';
							}
							$formdata[$id_l]->fields[$k] = $v;
						}
					}
				}
				echo "<SCRIPT LANGUAGE=\"JavaScript1.2\" TYPE=\"text/javascript\">";
				echo "window.alert('Los datos que desea ingresar ya existen...');";
				echo "</SCRIPT> ";
				include($config['include_form_error']);
				include('templates/form_footer.inc.php');
				return 0;
			}
		}
	}
	return 1;
}
//---------------------------------------------------------------------------
function listar_menues($config){
	global $default, $PHP_SELF;
	$db = connect();
	$db->debug = SDEBUG;
	if(!isset($config['condicion']) || $config['condicion']==""){
		if (preg_match("/oci8/i",$default->dbtype)){
			$config['condicion']="1=1";			
		} elseif (preg_match("/postgres/i",$default->dbtype)){
			$config['condicion']="true";
		} else {
			$config['condicion']="1";
		}
	}
	if( strlen($config['buscar'])>0 && strlen($config['buscarx'])>0){
		$config['condicion']="$config[condicion] AND $config[buscarx] LIKE '%$config[buscar]%'";
	}

	foreach($config['campos'][0] as $k => $v){
		if (preg_match("/binario/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if (preg_match("/state/i",$k)){
			$k=$config['campos'][0][$k]['campo'];
		}
		if(!isset($campos)){
			$campos=$k;
		} else {
			$campos.=",$k";
		}
	}

	$ORDER = "ORDER BY tipo,id_menu,menu_id,posicion";

	if (preg_match("/oci8/i",$default->dbtype)){
		$query = "SELECT * FROM (SELECT ROWNUM AS LIMIT, T.* FROM (SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER) T) WHERE LIMIT BETWEEN $reg_from AND $reg_to";
	} elseif (preg_match("/postgres/i",$default->dbtype)){
		$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER";
	} else {
		$query = "SELECT $campos FROM $config[tabla] $config[join] WHERE $config[condicion] $ORDER";
	}

	$cant_reg=1;
	if ($result = $db->Execute($query)) {
		$n_reg = $reg_from+$config['lineas'];
		$sq_reg = $result->RecordCount() + $reg_from;
		if ( $sq_reg >= $cant_reg ) {
			$sq_reg = $cant_reg;
			$n_reg = 0;
		}
		$p_reg = $reg_from-$config['lineas'];
		include('templates/menu_abm_menues.inc.php');
		include($config['include_form']);
	} else {
		Echo_Error(ERROR_DB_CONNECT);
	}

}
//---------------------------------------------------------------------------
function form_hidden_fields($config=array(),$id="",$op=""){
	if ($id != ""){
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"id\" VALUE=\"".$id."\">\n";
	}
	if ($op != ""){
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"op\" VALUE=\"".$op."\">\n";
	} else {
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"op\" VALUE=\"".$config['op']."\">\n";
	}
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"idioma\" VALUE=\"".$config['idioma']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"busca\" VALUE=\"".$config['buscar']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"buscarx\" VALUE=\"".$config['buscarx']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pag\" VALUE=\"".$config['pag']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"orden\" VALUE=\"".$config['orden']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"ord\" VALUE=\"".$config['ord']."\">\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"accion\" VALUE=\"".$config['accion']."\">\n";
}
//---------------------------------------------------------------------------
function StripUri($texto){
	$rettext = str_replace(BaseUri(),'',$texto);
	return $rettext;
}
//---------------------------------------------------------------------------
function writehelplnk($tlink,$htopic,$tipo,$hindex="") {
	if ($tipo=='req') {
		$estilo = "STYLE='color:red; font-weight: bold'";
	} else if ($tipo=='nocur') {
		$estilo = "STYLE='color:navy; font-weight: bold'";
	} else if ($tipo=='publ') {
		$estilo = "STYLE='color:green; font-weight: bold'";
	} else if ($tipo=='gen') {
		$estilo = "STYLE='color:black; font-weight: bold'";
	} else {
		$estilo = "";
	}
	$link="<A HREF='javascript:void(0)' title='Ayuda' $estilo onClick=\"javascript:helpwindow('$htopic','$tipo','$hindex');\">$tlink</A>";
	echo "$link";
}
//---------------------------------------------------------------------------
function longdate($tm,$langu) {
	$montharray=array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
   list($year,$month,$day) = split("-",$tm);
   $mdate = $day." de ".$montharray[$month-1]." de ".$year;
	return ($mdate);
}
//------------------------------------------------------------------------------
function sectionmail($sec,$id_lang,$sn='F') {
	global $default;
	$r_texto="";
	$db = connect();
	$db->debug = SDEBUG;
	$sql_query="SELECT * FROM adminmail WHERE codigo='$sec' AND lenguaje_id=$id_lang";
	$res = $db->Execute( $sql_query);
  	if($res){
     $r_texto= $res->fields[descripcion]." <".$res->fields['email'].">";
	} else {
		if ($sn == 'F') {
			$r_texto=$default->mailfrom;
		} else {
			$r_texto=$default->mailto;
		}
	}
 return($r_texto);
}
//------------------------------------------------------------------------------
function dolog($texto){
	global $default;
	$usuario = $_SESSION['user_name'];
	$db = connect();
	$rtexto = $db->qstr($texto,"");
	/*
	if (preg_match("/oci8/i",$default->dbtype)){
		$dquery="DELETE FROM $default->log_table WHERE trunc((((86400*(sysdate-fecha))/60)/60)/24) >= $default->flog";
	} elseif (preg_match("/postgres/i",$default->dbtype)){
		$dquery="DELETE FROM $default->log_table WHERE fecha < (date(now())-$default->flog)";
	} else {
		$dquery="DELETE FROM $default->log_table WHERE to_days(fecha) < to_days(now()) - $default->flog";
	}
	*/
	$dres = $db->Execute($dquery);
	$squery="insert into $default->log_table (operat,usuario) values($rtexto,'$usuario')";
	$res = $db->Execute($squery);
}
//---------------------------------------------------------------------------
function convert_image($sourcepic,$destpic,$res,$quality,$type){
	$thumb_generator = chkgd2();
	if(preg_match("/gd/i",$thumb_generator)) {
		if (preg_match("/(jpg|jpeg)$/i",$type)) {
			$type="jpg";
			$im=imagecreatefromjpeg($sourcepic);
		} elseif (preg_match("/png$/i",$type)) {
			$type="png";
			$im=imagecreatefrompng($sourcepic);
		} elseif (preg_match("/gif$/i",$type)) {
			$type="gif";
			$im=imagecreatefromgif($sourcepic);
		} 
		if ($im != "") {
			$dims=explode("x",$res);
			$newh=$dims[1];
			$neww=$newh/imagesy($im) * imagesx($im);
      	if ($neww > imagesx($im)) {
				$neww=imagesx($im);
				$newh=imagesy($im);
			}
			if ($neww > $dims[0]) {
				$neww=$dims[0];
				$newh=$neww/imagesx($im) * imagesy($im);
			}

			if ( $thumb_generator == "gd2" ) {
				if ($type=="gif") {
					$im2 = imagecreate($neww,$newh);
   	 			imagecopyresized($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
				} else {
  					$im2 = imagecreatetruecolor($neww,$newh);
  					imagecopyresampled($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
				}
			} elseif ( $thumb_generator == "gd" )	{		
    			$im2 = imagecreate($neww,$newh);
    			imagecopyresized($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
			}

			if ($type=="jpg") {
				imagejpeg($im2,$destpic,$quality);
			} elseif ($type=="png") {
				imagepng($im2,$destpic);
			} elseif ($type=="gif") {
				imagegif($im2,$destpic);
			}	
			ImageDestroy($im);
			ImageDestroy($im2);
			$ret_val = 1;
		} else {
			$ret_val = 0;
		}
	}
	return($ret_val);
}
//------------------------------------------------------------------------------
function chkgd2() { 
   static $gd_version_number = null; 
   if ($gd_version_number === null) { 
       ob_start(); 
       phpinfo(8); 
       $module_info = ob_get_contents(); 
       ob_end_clean(); 
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", 
               $module_info,$matches)) { 
           $gd_version_number = $matches[1]; 
       } else { 
           $gd_version_number = 0; 
       } 
   } 
   
	if ($gd_version_number >= 2) { 
   	return "gd2"; 
	} else {
   	return "gd";
	} 
}
//---------------------------------------------------------------------------
function isValidURL($url){
	return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
//------------------------------------------------------------------------------
function contenido_archivo($mytmpfile,$file_mime) {
	global $default;
	$contents = "";
	$mytmpdir = "./tmpdir/";
	if (!file_exists($mytmpdir.".")){
		mkdir($mytmpdir,0777);
	}
	$db = connect();
	$db->debug = SDEBUG;
	$doc_query = "SELECT funcion FROM mime_arch WHERE mime_arch.mime='$file_mime' AND indexa=1";
	$doc_result = $db->Execute($doc_query);
	if ($doc_result && !$doc_result->EOF){
		$myMime		= $doc_result->fields['mime_type'];
		$myFuncion	= $doc_result->fields['funcion'];
		$mytxtfile	= $mytmpdir.md5(uniqid(rand())).".txt";
		if (file_exists($mytxtfile)){
			unlink($mytxtfile);
		}
		$myFuncion = str_replace ("#FILE#", "'".$mytmpfile."'", $myFuncion);
		$myFuncion = str_replace ("#TMP#", "'".$mytxtfile."'", $myFuncion);
		if (file_exists($mytmpfile)){
			exec($myFuncion);
			if (file_exists($mytxtfile)){
				$fd = fopen ($mytxtfile, "rb");
				$contents = fread ($fd, filesize ($mytxtfile));
				fclose ($fd);
				unlink($mytxtfile);
			}
		}
	}

	return trim($contents);

}
//------------------------------------------------------------------------------
function genera_namefile($nameorig,$id_file,$char='0',$cant='10') {
	for ($n=1; $n<=strlen($nameorig); $n++){
		$caracter=substr($nameorig,($n*-1),1);
		if ($caracter != "."){
			$extension = $caracter.$extension;
		} else {
			break;
		}
	}
	$relleno = "%".$char.$cant."d";
	$filename = sprintf($relleno, $id_file).".".strtolower($extension);
	return $filename;
}
//-----------------------------------------------------------------------------------------------------------
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
//---------------------------------------------------------------------------
?>
