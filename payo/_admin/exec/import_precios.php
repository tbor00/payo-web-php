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
function ImportarProductos($filepath="./"){
	global $default, $importerror;
	$log_file='/var/www/html/_admin/logs/i_prod_log.txt';
	$filename = "sprecios";
	$infile = $filepath.$filename.".gz";
	$oufile = $filepath.$filename.".csv";

	if (uncompress($infile, $oufile)){
		if (!file_exists($oufile)){
			file_put_contents($log_file,"ERROR: no se genero el archivor $outfile\n" , FILE_APPEND);
	 	 	$importerror = "ERROR: no se genero el archivo \\n $outfile...";	
			return false;
		}
		$db = connect();
		$db->debug = 0;
		$sql = "DELETE FROM e_xproductos";
		if ($db->Execute($sql)) {
			$sql = "LOAD DATA LOCAL INFILE '".$oufile."' REPLACE INTO TABLE e_xproductos ";
			//$sql = "LOAD DATA INFILE '".$oufile."' REPLACE INTO TABLE e_xproductos ";
			$sql .= "FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\\\\' ";
			$sql .= "LINES TERMINATED BY '\\r\\n'"; 
			if ($db->Execute($sql)) {
				$db->Execute("DELETE FROM e_xproductos WHERE codigo=''");
				//$db->Execute("DELETE FROM e_xproductos WHERE precio=0 OR precio2=0");
				$db->Execute("DELETE FROM e_xproductos WHERE precio=0");
				
				$db->Execute("DELETE FROM e_pproductos");
				
				//$db->Execute("DELETE FROM e_pprod_marcas WHERE marca IN (SELECT marca from e_xproductos)");
				//$db->Execute("DELETE FROM e_pprod_tipos WHERE tipo IN (SELECT tipo from e_xproductos)");
				//$db->Execute("DELETE FROM e_pprod_proveedores WHERE proveedor IN (SELECT proveedor from e_xproductos)");
				//$db->Execute("DELETE FROM e_pproductos WHERE proveedor IN (SELECT proveedor from e_xproductos)");
				//$db->Execute("DELETE FROM e_pproductos WHERE codigo IN (SELECT codigo from e_xproductos)");

				$db->Execute("INSERT INTO e_pproductos SELECT codigo,descripcion,unidad,precio,precio,proveedor,codfab,modelo,origen,tipo,imagen,alicuota,moneda,marca,marca_a,marca_b,marca_c,observ,unven,stock FROM e_xproductos");
				$db->Execute("INSERT INTO e_pprod_marcas (marca) SELECT DISTINCT e_xproductos.marca from e_xproductos WHERE e_xproductos.marca<>'' AND marca NOT IN (SELECT marca from e_pprod_marcas)");
				$db->Execute("INSERT INTO e_pprod_tipos (tipo) SELECT DISTINCT e_xproductos.tipo from e_xproductos WHERE e_xproductos.tipo<>'' AND tipo NOT IN (SELECT tipo from e_pprod_tipos)");
				$db->Execute("INSERT INTO e_pprod_proveedores (proveedor) SELECT DISTINCT e_xproductos.proveedor from e_xproductos WHERE e_xproductos.proveedor<>'' AND proveedor NOT IN (SELECT proveedor from e_pprod_proveedores)");

				$res_c = $db->Execute("SELECT COUNT(codigo) AS cuenta from e_xproductos");
				if ($res_c && !$res_c->EOF){
					$cuenta = $res_c->fields[cuenta];
				} else {
					$cuenta = 0;
				}


				//$db->Execute("UPDATE oc_product,e_pproductos SET oc_product.price=e_pproductos.precio WHERE oc_product.sku=e_pproductos.codigo");

				//$db->Execute("DELETE FROM e_pprod_marcas WHERE marca NOT IN (SELECT DISTINCT marca from e_pproductos)");
				$db->Execute("DELETE FROM e_pprod_tipos WHERE tipo NOT IN (SELECT DISTINCT tipo from e_pproductos)");
				//$db->Execute("DELETE FROM e_pprod_proveedores WHERE proveedor NOT IN (SELECT DISTINCT proveedor from e_pproductos)");
				
				$db->Execute("INSERT INTO e_pprod_import (cant) values($cuenta)");
				$db->Execute("DELETE FROM e_xproductos");
				$retornar=true;

				/*
				$sql = "delete from marcas";
				$db->Execute($sql);
				$sql = "ALTER TABLE marcas AUTO_INCREMENT=1";
				$db->Execute($sql);
				$sql ="INSERT INTO marcas ( marca ) SELECT DISTINCT productos.marca FROM productos WHERE productos.marca<>'' ORDER BY productos.marca";
				if ($db->Execute($sql)) {
					$sql = "delete from tipos";
					$db->Execute($sql);
					$sql = "ALTER TABLE tipos AUTO_INCREMENT=1";
					$db->Execute($sql);
					$sql ="INSERT INTO tipos ( tipo ) SELECT DISTINCT productos.tipo FROM productos where productos.tipo<>'' ORDER BY productos.tipo";
					if ($db->Execute($sql)) {
						//
						//$sql = "UPDATE marcas INNER JOIN productos ON marcas.marca = productos.marca SET productos.marca_id = marcas.id_marca";
						//$db->Execute($sql);
						//$sql = "UPDATE tipos INNER JOIN productos ON tipos.tipo = productos.tipo SET productos.tipo_id = tipos.id_tipo";
						//$db->Execute($sql);
						//
						$retornar=true;
					}else{
				 	 	$importerror = "ERROR al importar los tipos de productos...";	
						$retornar=false;
					}
				}else{
			 	 	$importerror = "ERROR al importar las marcas...";	
					$retornar=false;
				}
				*/

			} else {
				file_put_contents($log_file,"Error al importar $outfile\n" , FILE_APPEND);
			 	$importerror = "ERROR al importar los datos desde \\n $oufile...";
				$retornar=false;
			}
 		} else {
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
		$importerror = "Error al descomprimir archivo...";
		$retornar=false;
	}

	return $retornar;
}
//------------------------------------------------------------------------------------
$pathf=$argv[1];
if (ImportarProductos($pathf)){
    echo "Se importaron los Precios Exitosamente\n";
}
?>
