<?php
//---------------------------------------------------------------------------
function alta($config){
   global $default, $id;
	$seq_name = 'seq_'.$config[tabla];
	$db = connect();
	$db->debug = SDEBUG;
	foreach($config['camposes'] as $k => $v){
		if($k=="binario"){
			$k=$config['camposen']['binario']['campo'];
		}
		if($v=="auto_increment"){
			$id_v = $db->GenID($seq_name);
			$v = $id_v;
		}

		if(!isset($campos)){
			$campos="($k";
			$valores="('$v'";
		}else{
			$campos.=",$k";
			$valores.=",'$v'";
		}
	}
	$campos.=")";
	$valores.=")";

	$query = "insert into $config[tabla] $campos values $valores";
	if($result = $db->Execute($query )){
		unset($campos);
		unset($valores);
		if (isset($id_v)){
			return $id_v;
		} else {
			return 1;
		}
	}else{
		echo "Error al conectarse a la base de datos" ;
		return 0;
	}
}
//---------------------------------------------------------------------------
function controlar($config){
   global $default, $glo_form_tit, $id;
	$db = connect();
	if (!isset($config['control'])){
		return 1;
	}
	if ($config[op]!="cm" && $config[op]!='ca') {
		foreach($config['control'] as $k => $v){
			list($cond,$msg)=split(":",$v);
			$query = "select count(*) from $k where $cond";
			$db->debug = SDEBUG;
			$result = $db->Execute( $query );
			$resultado = $result->fields[0];
			if ($resultado) {
				include('./include/templates/dialogmsg.inc.php');
				return 0;
			}
		}
	} else {
		foreach($config['control'] as $k => $v){
			$query = "select count(*) from $k where $v";
			$db->debug = SDEBUG;
			$result = $db->Execute( $query );
			$resultado = $result->fields[0];
			if ($resultado) {
				include('templates/form_header.inc.php');
				if(isset($config['camposes'])){
   				foreach($config['camposes'] as $k => $v){
						if($k=="binario"){
							$k=$config['camposes']['binario']['campo'];
						}
						if($v=="auto_increment"){
							$v = '';
						}
						$result->fields[$k] = $v;
					}
					$row1=$config['camposes'];
					$row=$config['camposes'];
				}
				if(isset($config['idioma'])){
					if(isset($config['camposen'])){
   					foreach($config['camposen'] as $k => $v){
							if($k=="binario"){
								$k=$config['camposen']['binario']['campo'];
							}
							if($v=="auto_increment"){
								$v = '';
							}
							$result2->fields[$k] = $v;
						}
						$row2=$config['camposen'];
					}
				}
				echo "<SCRIPT LANGUAGE=\"JavaScript1.2\" TYPE=\"text/javascript\">";
				echo "window.alert('¡Los datos que desea ingresar ya existen!');";
				echo "</SCRIPT> ";
				include($config['include_form_error']);
				include('templates/form_footer.inc.php');
				return 0;
			}
		}
	}
	return 1;
}

//------------------------------------------------------------------------------
function modificar($config){
	global $default, $id;
	$db = connect();
	if(isset($config['idioma'])){
		$config['cond']="$config[condicion] AND id_lenguaje='1'";
	}else{
		$config['cond']="$config[condicion]";
	}
	foreach($config['camposes'] as $k => $v){
		if(!isset($updatees)){
			$updatees="$k='$v'";
		} else {
			$updatees.=",$k='$v'";
		}
	}
	$query = "update $config[tabla] set $updatees where $config[cond]";
	$db->debug = SDEBUG;
	if($result = $db->Execute($query)){
		dolog($query);
		if(isset($config['idioma'])){
			/*Agregar la obtencion del ultimo id ingresado en la tabla!!!!!*/
			$config['cond']="$config[condicion] AND id_lenguaje='2'";
			foreach($config['camposen'] as $k => $v){
				if(!isset($updateen)){
					$updateen="$k='$v'";
				} else {
					$updateen.=",$k='$v'";
				}
			}
	 		$query = "update $config[tabla] set $updateen where $config[cond]";
	 		$db->debug = SDEBUG;
	  		if($result2 = $db->Execute( $query )){
				dolog($query);
				$config['condicion']="id_lenguaje='$config[idioma]'";
				return 1;
			}else{
				Error(ERROR_DB_CONNECT);
				return 0;
			}
		}else{
			unset($config['condicion']);
			return 1;
		}
	}else{
		Error(ERROR_DB_CONNECT);
		return 0;
	}
}
//---------------------------------------------------------------------------
function dolog($texto){
	global $default;
	$usuario = $_SESSION["user"];
	$rtexto = str_replace ("'","\'",$texto);
	$db = connect();
	$dquery="delete from logg where to_days(fecha) < to_days(now()) - $default->flog";
	$dres = $db->Execute($dquery);
	$squery="insert into logg (operat,usuario) values('$rtexto','$usuario')";
	$res = $db->Execute($squery);
}
//---------------------------------------------------------------------------

?>
