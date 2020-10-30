<?php
require_once('core.lib.php');
//-------------------------------------------------------------------------------------
function uncompress($srcName, $dstName) {
	if (!file_exists ($srcName) || !is_readable ($srcName)) {
		echo "El archivo $srcName no existe.";
		return false;
	}
	if ((!file_exists ($dstName) && !is_writable (dirname ($dstName)) || (file_exists($dstName) && !is_writable($dstName)) )) {
		echo "No se puede acceder al archivo $dstName.";
		return false;
	}
	if ($zp = gzopen($srcName, "r")){
		if ($fp = fopen($dstName, "w")){
  			while(!gzeof($zp)) {
				$buffer = gzread($zp, 4096);
				fwrite($fp, $buffer, strlen($buffer));
			}
			fclose($fp);
		} else {
			gzclose($zp);
			echo "No se pudo descomprimir el archivo.";
			return false;
		}
		gzclose($zp);
	} else {
		echo "No se pudo descomprimir el archivo.";
		return false;
	}
	return true;
}
//-------------------------------------------------------------------------------------
function ImportarCtas($filepath="./"){
	global $default, $importerror;
	$filename = "sclientes";
	$infile = $filepath.$filename.".gz";
	$oufile = $filepath.$filename.".csv";
	$log_file='/var/www/html/_admin/logs/i_cl_log.txt';

	if (uncompress($infile, $oufile)){
		if (!file_exists($oufile)){
			file_put_contents($log_file,date("F j, Y, g:i a")." - "."ERROR: no se genero el archivor $outfile\n" , FILE_APPEND);
	 	 	$importerror = "ERROR: no se generó el archivo \\n $outfile...";	
			return false;
		}
		$db = connect();
		$db->debug = 0;
		$sql = "DELETE FROM e_xclientes";
		if ($db->Execute($sql)) {
			$sql = "LOAD DATA LOCAL INFILE '".$oufile."' REPLACE INTO TABLE e_xclientes ";
			//$sql = "LOAD DATA INFILE '".$oufile."' REPLACE INTO TABLE e_xctasctes ";
			$sql .= "FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\\\\' ";
			$sql .= "LINES TERMINATED BY '\\r\\n'"; 
			if ($db->Execute($sql)) {
				//$db->Execute("DELETE FROM e_ctasctes");
				//$db->Execute("INSERT INTO e_ctasctes SELECT id,cliente_eb,comprobante,numero,fecha,vto,referencia,credito,debito,saldo,estado,gestion FROM e_xctasctes");
				$db->Execute("UPDATE e_webusers,e_xclientes SET e_webusers.vendedor_id=e_xclientes.vendedor_id, e_webusers.razonsocial=e_xclientes.razonsocial 
				where e_webusers.eb_cod=e_xclientes.eb_cod AND e_xclientes.razonsocial<>''");
				$res_c = $db->Execute("SELECT COUNT(eb_cod) AS cuenta from e_xclientes");
				if ($res_c && !$res_c->EOF){
					$cuenta = $res_c->fields[cuenta];
					file_put_contents($log_file,date("F j, Y, g:i a")." - "."Registros Importados $cuenta\n" , FILE_APPEND);
				} else {
					$cuenta = 0;
				}
				$db->Execute("INSERT INTO e_ctasctes_import (cant) values($cuenta)");
				$db->Execute("DELETE FROM e_xclientes");
				$retornar=true;

			} else {
				file_put_contents($log_file,date("F j, Y, g:i a")." - "."ERROR al importar los datos $oufile\n" , FILE_APPEND);
			 	$importerror = "ERROR al importar los datos desde \\n $oufile...";
				$retornar=false;
			}
 		} else {
			file_put_contents($log_file,date("F j, Y, g:i a")." - "."ERROR al importar los datos...\n" , FILE_APPEND);
			$importerror = "ERROR al importar los datos...";
			$retornar=false;
		}
		if (file_exists($oufile)){
			unlink($oufile);
		}
		if (file_exists($infile)){
			unlink($infile);
		}
	} else {
		file_put_contents($log_file,date("F j, Y, g:i a")." - "."Error al descomprimir archivo\n" , FILE_APPEND);
		$importerror = "Error al descomprimir archivo...";
		$retornar=false;
	}

	return $retornar;
}
//------------------------------------------------------------------------------------
$pathf=$argv[1];
if (ImportarCtas($pathf)){
    echo "Se importaron los Clientes exitosamente\n";
}
?>
