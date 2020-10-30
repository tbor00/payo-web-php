<?php
require('../include/site.lib.php');
session_name($default->session_name);
session_start();
require_once('../include/translate.inc.php');
require_once('../include/users.lib.php');

 
function GetProductsList($obj){
	global $default;
	$db=connect();
	$prd="";
	$subquery="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND stock > 0";
	$query = "SELECT e_pproductos.* FROM e_pproductos".$subquery." ORDER by codigo";

	if ($producto_r=$db->Execute($query)){
		$productos = $obj->addChild('productos');
		while (!$producto_r->EOF){
			$producto = $productos->addChild('producto');
			$producto->addChild('codigo');
			$producto->addChild('descripcion');
			$producto->addChild('marca');
			$producto->addChild('modelo');
			$producto->addChild('tipo');
			$producto->addChild('codigofabrica');
			$producto->addChild('observaciones');
			$producto->addChild('stock');
			$producto->addChild('unidad');
			$producto->addChild('moneda');
			$producto->addChild('precio');
			$producto->addChild('imagen');
			$producto->codigo = utf8_encode($producto_r->fields['codigo']);			
			$producto->descripcion = utf8_encode($producto_r->fields['descripcion']);
			$producto->marca = utf8_encode($producto_r->fields['marca']);
			$producto->modelo = utf8_encode($producto_r->fields['modelo']);
			$producto->tipo = utf8_encode($producto_r->fields['tipo']);
			$producto->codigofabrica = utf8_encode($producto_r->fields['codfab']);
			$producto->observaciones = utf8_encode($producto_r->fields['observ']);
			$producto->stock = sprintf('%01.0f',$producto_r->fields['stock']);
			$producto->unidad = utf8_encode($producto_r->fields['unidad']);
			if ($producto_r->fields['moneda'] == '$'){
				$moneda = "ARS";
			} else {
				$moneda = $producto_r->fields['moneda'];
			}
			$obj->productos->producto->moneda = $moneda;
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION['iva']>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			$producto->precio = decimales($precio);
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("../products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
				$partes_ruta = pathinfo($p_image);
				$producto->imagen = "https://".$_SERVER['SERVER_NAME']."/products/imagenes/".$partes_ruta['basename'];
			} else {
				$producto->imagen="";
			}

			$producto_r->MoveNext();
		}
	} else {
		$obj->error->code    = 'Fatal';
		$obj->error->message = 'No Records founds';		
	}
	return $obj;
}
//----------------------------------------------------------------------------------------
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n<response>\n</response>\n";
$obj = SimpleXML_Load_String($xml);
$obj->addChild('version');
$obj->version = '1.0';
$obj->addChild('error');
$obj->error->addChild('code');
$obj->error->code = 0;
$obj->error->addChild('message');

if ($_POST['user']!=''){
	$user = rawurldecode($_POST['user']);
} elseif ($_GET['user']!=''){
	$user = rawurldecode($_GET['user']);
}
if ($_POST['passwd']!=''){
	$user = rawurldecode($_POST['passwd']);
} elseif ($_GET['passwd']!=''){
	$user = rawurldecode($_GET['passwd']);
}
if ($_POST['type']!=''){
	$type = rawurldecode($_POST['type']);
} elseif ($_GET['type']!=''){
	$type = rawurldecode($_GET['type']);
}
if (checkuser($user,$passwd)){
	$obj = GetProductsList($obj);
} else {
	session_defaults();
	$obj->error->code    = 'Fatal';
	$obj->error->message = 'Invalid authentication';		
}	
if ($type == 'JSON'){
	$out = json_encode($obj);
} else {
	$out = $obj->AsXML();
}
echo $out;
session_defaults();
exit;
?>