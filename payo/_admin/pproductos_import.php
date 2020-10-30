<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
require_once('include/functions.inc.php');
require_once('file.lib.php');
set_time_limit(0);
$upload = new upload("archivo");
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
function ImportarProductos($filename="sprecios"){
	global $default, $importerror;
	$filepath = dirname(__FILE__)."/tmpdir/";
	$infile = $filepath.$filename.".gz";
	$oufile = $filepath.$filename.".csv";

	if (uncompress($infile, $oufile)){
		if (!file_exists($oufile)){
	 	 	$importerror = "ERROR: no se generó el archivo \\n $outfile...";	
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
				$db->Execute("DELETE FROM e_xproductos WHERE precio=0 OR precio2=0");

				$db->Execute("DELETE FROM e_pproductos");
				
				//$db->Execute("DELETE FROM e_pprod_marcas WHERE marca IN (SELECT marca from e_xproductos)");
				//$db->Execute("DELETE FROM e_pprod_tipos WHERE tipo IN (SELECT tipo from e_xproductos)");
				//$db->Execute("DELETE FROM e_pprod_proveedores WHERE proveedor IN (SELECT proveedor from e_xproductos)");
				//$db->Execute("DELETE FROM e_pproductos WHERE proveedor IN (SELECT proveedor from e_xproductos)");
				//$db->Execute("DELETE FROM e_pproductos WHERE codigo IN (SELECT codigo from e_xproductos)");

				$db->Execute("INSERT INTO e_pproductos SELECT codigo,descripcion,unidad,precio,precio2,proveedor,codfab,modelo,origen,tipo,imagen,alicuota,moneda,marca,marca_a,marca_b,marca_c,observ,unven,stock FROM e_xproductos");
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
?>
<HTML>
<HEAD>
<TITLE>Importación de Productos</TITLE>
<LINK REL="stylesheet" TYPE="text/css" HREF="styles/style.css">
<SCRIPT LANGUAGE="javascript" SRC="jscripts/xp_progress.js"></SCRIPT>
<SCRIPT LANGUAGE="javascript">
var refreshW = 0;
var uperror = '';
var cierra = 0;
var regs = 0;
var sbar;
function Do_close() {
   if (refreshW == 1) {
		window.alert('Se ha importado el archivo correctamente.');
		opener.document.location="index.php?op=l&accion=pproductos";
		window.close();
	} else {
		sbar.hideBar();
		if (uperror!=''){
			window.alert(uperror);
		}
	}
}
function Do_refresh() {
		if (uperror!=''){
			window.alert(uperror);
		}
		window.document.location="<?php echo $_SERVER[PHP_SELF] ?>";
}
//--------------------------------------------------------------
function Change_text(texto){
	document.getElementById('bartext').innerHTML = texto; 
}
//--------------------------------------------------------------
function Importar(){
	FM1.style.visibility="hidden";
	FM2.style.visibility="visible";
	sbar.showBar();
	document.forms[0].submit();
}
//--------------------------------------------------------------
</SCRIPT>
</HEAD>
<BODY BGCOLOR="<?php echo $default->body_bgcolor ?>" ONLOAD="Do_close();" BACKGROUND="<?php echo $default->body_bg ?>">
<TABLE ALIGN="CENTER" WIDTH="100%" HEIGHT="100%">
<?php
echo "<TR><TD ALIGN=\"CENTER\">\n";
echo "<DIV ID='FM2' ALIGN='CENTER' STYLE='visibility:hidden;'>\n";
echo "<SCRIPT TYPE=\"text/javascript\">\n";
echo "var sbar=createBar(300,15,'white',1,'green','".$default->table_bgheadercolor."',85,7);\n";
echo "</SCRIPT>\n";
echo "<P ID=\"bartext\" STYLE=\"color:white\">Importando datos</P></DIV>\n";
echo "</TD></TR>\n";
if ($doimport=="import"){
	if (ImportarProductos()){
		echo "<SCRIPT>\n";
		echo "refreshW=1;\n";
		echo "sbar.hideBar();\n";
		echo "Change_text('');\n";
		echo "</SCRIPT>\n";
	} else {
		echo "<SCRIPT>\n";
		echo "refreshW=0;\n";
		echo "uperror='".$importerror."'\n";
		echo "Change_text('');\n";
		echo "sbar.hideBar();\n";
		echo "cierra = 1;\n";
		echo "</SCRIPT>\n";
	}
} elseif($doimport=="upload"){
	$destination_folder = "tmpdir/";
   $result = $upload->security_check();
	if ($upload->file['name']=="sprecios.gz"){
		if ($result == true){
			$result = $upload->move($destination_folder, true);
		}
		if ($result==true){
			$importerror="";
		} else {
			$importerror="No se pudo subir el archivo";
		}
	} else {
		$importerror="Nombre de archivo incorrecto, debe ser sprecios.gz";
	}
	echo "<SCRIPT>\n";
	echo "uperror='".$importerror."'\n";
	echo "Do_refresh()\n";
	echo "</SCRIPT>\n";
} else {
	echo "<TR><TD ALIGN=\"CENTER\">\n";
	echo "<DIV ID='FM1' ALIGN='CENTER' STYLE='visibility:visible;'>\n";
	$db=connect();
	$rs_li = $db->Execute("SELECT * FROM e_pprod_import ORDER BY id_import DESC LIMIT 0,1 ");
	if ($rs_li && !$rs_li->EOF){
		echo "<P><STRONG>Ultima Importación:</STRONG> ".timest2dt($rs_li->fields[fecha])."<BR>";
		echo "<STRONG>Cantidad:</STRONG> ".$rs_li->fields[cant]."</P>";
	}

	if (file_exists("tmpdir/sprecios.gz")){
		echo "<FORM NAME=\"import_form\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"doimport\" VALUE=\"import\">\n";
		echo "<INPUT CLASS=\"button\" NAME=\"importar\" VALUE=\"Importar\" TYPE=\"button\" ONCLICK=\"javascript:Importar();\">\n";
		echo "</FORM>";
	} else {



		echo "<P STYLE=\"color:white\">No existe el archivo de Precios a importar</P>\n";
		echo "<FORM NAME=\"import_form\" ENCTYPE=\"multipart/form-data\" action=\"{$_SERVER['PHP_SELF']}\" METHOD=\"POST\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"doimport\" VALUE=\"upload\">\n";
		echo "<STRONG>Archivo: </STRONG><INPUT CLASS=\"boxes\" SIZE=\"40\" NAME=\"archivo\" TYPE=\"file\"><BR><BR>\n";
		echo "<INPUT CLASS=\"button\" NAME=\"subir\" VALUE=\"Subir\" TYPE=\"submit\">\n";
		echo "<INPUT CLASS=\"button\" NAME=\"cancelar\" VALUE=\"Cancelar\" TYPE=\"button\" ONCLICK=\"javascript:window.close();\">\n";
		echo "</FORM>";
	}
	echo "</DIV>\n";
	echo "</TD></TR></TABLE>\n";
}
?>
</BODY></HTML>
