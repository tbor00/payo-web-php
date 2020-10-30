<?php
require('../include/site.lib.php');
session_name($default->session_name);
session_start();
require_once('../include/translate.inc.php');
require_once('../include/users.lib.php');
require_once("../include/lib//nusoap.php");

$server = new soap_server;
$ns =  make_absoluteURI($_SERVER['PHP_SELF']."?wsdl");

$server->configureWSDL("GetProductsAsArray",$ns);

$server->wsdl->schematargetnamespace=$ns;
 
 
$server->wsdl->addComplexType('productos','complexType','struct','all','',
               array(
					'codigo'		 => array('name' => 'codigo', 'type' => 'xsd:string'),
					'imagen'		 => array('name' => 'imagen', 'type' => 'xsd:string'),
					'descripcion'	 => array('name' => 'descripcion', 'type' => 'xsd:string' ),
					'marca'		 	 => array('name' => 'marca', 'type' => 'xsd:string' ),
					'modelo'		 => array('name' => 'modelo', 'type' => 'xsd:string' ),
					'tipo'			 => array('name' => 'tipo', 'type' => 'xsd:string' ),
					'codfab'		 => array('name' => 'codfab', 'type' => 'xsd:string' ),
					'unven'			 => array('name' => 'unven', 'type' => 'xsd:string' ),
					'observ'		 => array('name' => 'observ', 'type' => 'xsd:string' ),
					'stock'			 => array('name' => 'stock', 'type' => 'xsd:string' ),
					'moneda'		 => array('name' => 'moneda', 'type' => 'xsd:string' ),
					'precio'		 => array('name' => 'precio', 'type' => 'xsd:string' ),
				));
						
						
$server->wsdl->addComplexType('ArrayOfProductos','complexType','array','','SOAP-ENC:Array',
					array(),
					array(        
						array('ref' => 'SOAP-ENC:arrayType',
							'wsdl:arrayType' => 'tns:productos[]'                              
						)                                       
					),
					'tns:productos'); 						

function GetProductsAsArray(){
	global $default;
	$db=connect();
	$n=0;
	$subquery="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND stock > 0";
	$query = "SELECT e_pproductos.* FROM e_pproductos".$subquery." ORDER by codigo";
	if ($producto_r=$db->Execute($query)){
		while (!$producto_r->EOF){
			$prd[$n]['codigo'] = utf8_encode($producto_r->fields['codigo']);
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("../products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
				$partes_ruta = pathinfo($p_image);
				$prd[$n]['imagen'] = "https://".$_SERVER['SERVER_NAME']."/products/imagenes/".$partes_ruta['basename'];
			} else {
				$prd[$n]['imagen'] = "";
			}
			$prd[$n]['descripcion'] = utf8_encode($producto_r->fields['descripcion']);
			$prd[$n]['marca'] = utf8_encode($producto_r->fields['marca']);
			$prd[$n]['modelo'] = utf8_encode($producto_r->fields['modelo']);
			$prd[$n]['tipo'] = utf8_encode($producto_r->fields['tipo']);
			$prd[$n]['codfab'] = utf8_encode($producto_r->fields['codfab']);
			$prd[$n]['unven'] = utf8_encode($producto_r->fields['unven']);
			$prd[$n]['observ'] = utf8_encode($producto_r->fields['observ']);
			$prd[$n]['stock'] = sprintf('%01.0f',$producto_r->fields['stock']);
			if ($producto_r->fields['moneda'] == '$'){
				$moneda = "ARS";
			} else {
				$moneda = $producto_r->fields['moneda'];
			}
			$prd[$n]['moneda'] = $moneda;
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION['iva']>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			$prd[$n]['precio'] = decimales($precio);
			$producto_r->MoveNext();
			$n++;
		}
	}
	return $prd;
}


$server->register('GetProductsAsArray',
				array(),
				array('return'=>'tns:ArrayOfProductos'),
				$ns, 
				'urn:'.'wsproductos',
				'urn:'.'wsproductos'.'#GetProductsAsArray',
				'rpc',
				'encoded',
				'Este método devuelve un array de productos.'
				);    

               
// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);   

?>