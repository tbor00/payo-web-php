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
function ImportarCtas($filename="sctasctes"){
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
		$sql = "DELETE FROM e_xctasctes";
		if ($db->Execute($sql)) {
			$sql = "LOAD DATA LOCAL INFILE '".$oufile."' REPLACE INTO TABLE e_xctasctes ";
			//$sql = "LOAD DATA INFILE '".$oufile."' REPLACE INTO TABLE e_xctasctes ";
			$sql .= "FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\\\\' ";
			$sql .= "LINES TERMINATED BY '\\r\\n'"; 
			if ($db->Execute($sql)) {
				$db->Execute("DELETE FROM e_ctasctes");
				$db->Execute("INSERT INTO e_ctasctes SELECT id,cliente_eb,comprobante,numero,fecha,vto,referencia,credito,debito,saldo,estado,gestion FROM e_xctasctes");

				$res_c = $db->Execute("SELECT COUNT(id) AS cuenta from e_xctasctes");
				if ($res_c && !$res_c->EOF){
					$cuenta = $res_c->fields[cuenta];
				} else {
					$cuenta = 0;
				}


				$db->Execute("INSERT INTO e_ctasctes_import (cant) values($cuenta)");
				$db->Execute("DELETE FROM e_xctasctes");
				$retornar=true;


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
<TITLE>Importación de Ctas.Ctes.</TITLE>
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
		opener.document.location="index.php?op=l&accion=ctasctes";
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
	if (ImportarCtas()){
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
	if ($upload->file['name']=="sctasctes.gz"){
		if ($result == true){
			$result = $upload->move($destination_folder, true);
		}
		if ($result==true){
			$importerror="";
		} else {
			$importerror="No se pudo subir el archivo";
		}
	} else {
		$importerror="Nombre de archivo incorrecto, debe ser sctasctes.gz";
	}
	echo "<SCRIPT>\n";
	echo "uperror='".$importerror."'\n";
	echo "Do_refresh()\n";
	echo "</SCRIPT>\n";
} else {
	echo "<TR><TD ALIGN=\"CENTER\">\n";
	echo "<DIV ID='FM1' ALIGN='CENTER' STYLE='visibility:visible;'>\n";
	$db=connect();
	$rs_li = $db->Execute("SELECT * FROM e_ctasctes_import ORDER BY id_import DESC LIMIT 0,1 ");
	if ($rs_li && !$rs_li->EOF){
		echo "<P><STRONG>Ultima Importación:</STRONG> ".timest2dt($rs_li->fields[fecha])."<BR>";
		echo "<STRONG>Cantidad:</STRONG> ".$rs_li->fields[cant]."</P>";
	}

	if (file_exists("tmpdir/sctasctes.gz")){
		echo "<FORM NAME=\"import_form\">\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"doimport\" VALUE=\"import\">\n";
		echo "<INPUT CLASS=\"button\" NAME=\"importar\" VALUE=\"Importar\" TYPE=\"button\" ONCLICK=\"javascript:Importar();\">\n";
		echo "</FORM>";
	} else {
		echo "<P STYLE=\"color:white\">No existe el archivo de Ctas Ctes a importar</P>\n";
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
