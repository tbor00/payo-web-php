<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
require_once('include/functions.inc.php');
set_time_limit(0);
//-------------------------------------------------------------------------------------
function EliminarProductos($proveedor=""){
	global $default, $importerror;
	if ($proveedor==''){
		$importerror = "Error: Debe seleccionar un Proveedor...";
		return false;
	}
	$db = connect();
	$db->debug = 0;
	if ($proveedor='ALL'){
		$sql = "DELETE FROM e_pproductos";
	} else {
		$sql = "DELETE FROM e_pproductos WHERE proveedor='{$proveedor}'";
	}
	if ($db->Execute($sql)){
		if ($proveedor='ALL'){
			//$db->Execute("DELETE FROM e_pprod_proveedores");
			//$db->Execute("DELETE FROM e_pprod_marcas");
			//$db->Execute("DELETE FROM e_pprod_tipos");
		} else {
			//$db->Execute("DELETE FROM e_pprod_proveedores WHERE proveedor='{$proveedor}'");
			//$db->Execute("DELETE FROM e_pprod_marcas WHERE marca NOT IN (SELECT marca from e_pproductos)");
			//$db->Execute("DELETE FROM e_pprod_tipos WHERE tipo NOT IN (SELECT tipo from e_pproductos)");
		}
		$retornar=true;
	} else {
		$importerror = "Error al eliminar archivos...";
		$retornar=false;
	}
	return $retornar;
}
//------------------------------------------------------------------------------------
?>
<HTML>
<HEAD>
<TITLE>Eliminación de Productos</TITLE>
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
		window.alert('Se han eliminado los productos correctamente.');
		opener.document.location="index.php?op=l&accion=pproductos";
		window.close();
	} else {
		sbar.hideBar();
		if (uperror!=''){
			window.alert(uperror);
		}
	}
}
//--------------------------------------------------------------
function Change_text(texto){
	document.getElementById('bartext').innerHTML = texto; 
}
//--------------------------------------------------------------
function Eliminar(){
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
echo "<P ID=\"bartext\" STYLE=\"color:white\">Eliminando datos</P></DIV>\n";
echo "</TD></TR>\n";
if ($_POST['doeliminar']=="yes" && $_POST['proveedor']!=""){
	if (EliminarProductos($_POST['proveedor'])){
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
} else {
	echo "<TR><TD ALIGN=\"CENTER\">\n";
	echo "<DIV ID='FM1' ALIGN='CENTER' STYLE='visibility:visible;'>\n";
	$db=connect();
	echo "<FORM NAME=\"delete_form\" METHOD=\"POST\">\n";
	echo "Proveedor: <SELECT NAME=\"proveedor\" HEIGHT=\"0\">\n";
	echo "<OPTION VALUE=\"\">-----------------------------</OPTION>\n";
	echo "<OPTION VALUE=\"ALL\">Todos los Productos</OPTION>\n";
	$rs_li = $db->Execute("SELECT DISTINCT proveedor FROM e_pprod_proveedores WHERE proveedor<>'' ORDER BY proveedor");
	if ($rs_li && !$rs_li->EOF){
		while (!$rs_li->EOF){
			echo "<OPTION VALUE=\"".htmlspecialchars($rs_li->fields[proveedor])."\">".htmlspecialchars($rs_li->fields[proveedor])."</OPTION>\n";
			$rs_li->MoveNext();
		}
	}
	echo "</SELECT>\n";
	echo "<INPUT TYPE=\"HIDDEN\" NAME=\"doeliminar\" VALUE=\"yes\">\n";
	echo "<INPUT CLASS=\"button\" NAME=\"importar\" VALUE=\"Eliminar\" TYPE=\"button\" ONCLICK=\"javascript:Eliminar();\">\n";
	echo "</FORM>";
	echo "</DIV>\n";
	echo "</TD></TR></TABLE>\n";
}
?>
</BODY></HTML>
