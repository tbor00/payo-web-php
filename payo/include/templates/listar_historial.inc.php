<?php
setlocale(LC_CTYPE , "en_US");
if ($_POST['marca']!=''){
	$marca = $_POST['marca'];
} elseif ($_GET['marca']!=''){
	$marca = $_GET['marca'];
}
$ord = $_GET['ord'];
$orden = $_GET['orden'];
$pag = $_GET['pag'];

?>
<SCRIPT LANGUAGE="Javascript1.2">
//---------------------------------------------------------
function filtramarca(form){
  var myindex2=form.marca.selectedIndex;
  location="index.php?op="+form.op.value+"&marca="+form.marca.options[myindex2].value;
}
//---------------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $_SERVER['PHP_SELF'] ?>" METHOD="POST" NAME="cons_prod">
<INPUT NAME="op" VALUE="<?php echo $op ?>" TYPE="HIDDEN">
<INPUT NAME="sop" VALUE="<?php echo $sop ?>" TYPE="HIDDEN">
<INPUT NAME="orden" VALUE="<?php echo $orden ?>" TYPE="HIDDEN">
<INPUT NAME="ord" VALUE="<?php echo $ord ?>" TYPE="HIDDEN">
<?php
echo "<div class=\"row\">\n";
echo "<div class=\"col-md-3 col-sm-offset-2\">\n";
$query = "SELECT DISTINCT marca FROM e_historial ORDER BY marca";
$marca_r=$db->Execute($query);
echo "<SELECT NAME=\"marca\" SIZE=\"1\" id=\"input-type\" class=\"form-control\" onchange=\"javascript:filtramarca(this.form)\">\n";
echo "<OPTION VALUE=\"\">Todas las Marcas</OPTION>\n";
while (!$marca_r->EOF){
	if ($marca_r->fields['marca']==stripslashes($marca)){
		echo "<OPTION VALUE=\"".$marca_r->fields['marca']."\" SELECTED>".$marca_r->fields['marca']."</OPTION>\n";
	}else{
		echo "<OPTION VALUE=\"".$marca_r->fields['marca']."\">".$marca_r->fields['marca']."</OPTION>\n";
	}
	$marca_r->MoveNext();
}
echo "</SELECT>\n";
echo "</div></div>";								 
echo "</FORM>\n";

//------------ FIN FORMULARIO DE BUSQUEDA

$qlineas = 30;


if ($marca!=''){
	if ($subquery != ''){
		 $subquery .= " AND marca='".addslashes($marca)."'";
	}else{
		 $subquery = " WHERE marca='".addslashes($marca)."'";
	}
}


$query = "SELECT COUNT(*) FROM e_historial".$subquery;
$count = $db->Execute($query); 
if ($count && !$count->EOF){
	$cant_reg = $count->fields[0];
}
echo "<BR>\n";

if ($orden == ""){
  $orden="fecha";
}
if ($ord != "ASC" && $ord != "DESC"){
  $ord="DESC";
}
if (isset($orden) and strlen($orden)>0) {
	$url_orden="&orden=$orden&ord=$ord";
	$ORDER="order by $orden $ord";
}
if ($marca != ''){
	$url_busqueda .= "&marca=$marca";
}

if (isset($gotopag)){
	if ($gotopag > ceil($cant_reg/$qlineas)) {
		$pag = ceil($cant_reg/$qlineas);
	} elseif ($gotopag < 0){
		$pag = 1;
	} else {
		$pag = $gotopag;
	}
}


if ($pag == '' || $pag == 0 ){
 	$pag = 1;
}
$from = ($pag-1) * $qlineas;


$query = "SELECT * FROM e_historial".$subquery." $ORDER limit $from, $qlineas";
if ($producto_r=$db->Execute($query)){
	echo "<div class=\"row\">";
	echo "<div class=\"col-sm-8 col-sm-offset-2\">";
	echo "<table class=\"table table-bordered table-hover\">\n";
	echo "<thead><tr>\n";
	echo Titulo_Browse("Fecha","fecha",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda",1,"10%");
	echo Titulo_Browse("Marca","marca",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda",1,"10%");
	echo Titulo_Browse("Observaciones","observaciones",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda",0,"80%");
	echo "</tr></thead>\n";
	
	if ($producto_r->EOF){
		echo "<tr>\n";
		echo "<td colspan=\"4\" class=\"text-center\">No se encontraron registros con los datos suministrados...</td>\n";
		echo "</tr>\n";
	}
	while (!$producto_r->EOF){
		echo "<tr>\n";
      echo "<td class=\"text-left\">".timesql2std($producto_r->fields['fecha'])."</td>\n";
      echo "<td class=\"text-left\">".$producto_r->fields['marca']."</td>\n";
      echo "<td class=\"text-left\">".nl2br($producto_r->fields['observaciones'])."</td>\n";
		echo "</tr>\n";
		$producto_r->MoveNext();
	}
	echo "</table>\n";
	echo "</div></div>\n";
	echo paginar($pag, $cant_reg, $qlineas, $_SERVER['PHP_SELF']."?op=$op&sop=$sop$url_orden$url_busqueda&pag=");
	
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>
<P><P>
