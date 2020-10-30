<?php
require('include/site.lib.php');
session_name($default->session_name);
session_start();
require_once('include/translate.inc.php');
require_once('include/users.lib.php');

setlocale(LC_CTYPE , "en_US");

if ($_POST['buscar']!=''){
	$buscar = rawurldecode($_POST['buscar']);			  
} elseif ($_GET['buscar']!=''){
	$buscar = rawurldecode($_GET['buscar']);
}
if ($_POST['tipo']!=''){
	$tipo = rawurldecode($_POST['tipo']);
} elseif ($_GET['tipo']!=''){
	$tipo = rawurldecode($_GET['tipo']);
}
if ($_POST['marca']!=''){
	$marca = rawurldecode($_POST['marca']);
} elseif ($_GET['marca']!=''){
	$marca = rawurldecode($_GET['marca']);
}

$db=connect();


$subquery="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND stock > 0";




$query = "SELECT e_pproductos.* FROM e_pproductos".$subquery." ORDER by codigo";
if ($producto_r=$db->Execute($query)){
		header('Content-type: text/xml');
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		echo "<productos>\n";
		
		while (!$producto_r->EOF){
			
			echo "<producto>\n";
			echo "<codigo>".$producto_r->fields['codigo']."</codigo>\n";
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
				echo "<imagen>https://".$_SERVER['SERVER_NAME']."/".$p_image."</imagen>\n";
			} else {
				echo "<imagen></imagen>\n";
			}
			echo "<descripcion>".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</descripcion>\n";
			echo "<marca>".htmlentities($producto_r->fields['marca'],ENT_QUOTES,$default->encode)."</marca>\n";
			echo "<modelo>".htmlentities($producto_r->fields['modelo'],ENT_QUOTES,$default->encode)."</modelo>\n";
			echo "<tipo>".htmlentities($producto_r->fields['tipo'],ENT_QUOTES,$default->encode)."</tipo>\n";
			echo "<codfab>".htmlentities($producto_r->fields['codfab'],ENT_QUOTES,$default->encode)."</codfab>\n";
			echo "<unven>".htmlentities($producto_r->fields['unven'],ENT_QUOTES,$default->encode)."</unven>\n";
			echo "<observ>".htmlentities($producto_r->fields['observ'],ENT_QUOTES,$default->encode)."</observ>\n";
			echo "<stock>".htmlentities(sprintf('%01.0f',$producto_r->fields['stock']),ENT_QUOTES,$default->encode)."</stock>\n";
			echo "<moneda>".$producto_r->fields['moneda']."</moneda>\n";
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo "<precio>".decimales($precio)."</precio>\n";
			echo "</producto>\n";
			$producto_r->MoveNext();
		}
		echo "</productos>\n";
		echo "</xml>\n";
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>