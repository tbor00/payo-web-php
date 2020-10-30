<?php
$cotizacion = $_GET['cotizacion'];
$wishlist = $_GET['wishlist'];

if ($_SESSION[logged]){
	$id_usuario=$_SESSION['uid'];
	$db = connect();
	$db->debug = 0;
	$itemnum = $_POST['itemnum'];
	if ($_POST['cotizanum']){
		$cotizanum = $_POST['cotizanum'];
	} else {
		$cotizanum = $_GET['cotizanum'];
	}
	if ($_POST['product_id']){
		$product_id = $_POST['product_id'];
	} else {
		$product_id = $_GET['product_id'];
	}

// Remover Item al pedido
	if ($cotizacion=="remove_item") {
		if ($cotizanum && $itemnum){
			$json = array();
			$query = "DELETE FROM e_cotiza_lineas WHERE id_cot=$cotizanum AND id_lin=$itemnum";
			$delete_r = $db->Execute($query);
			if ($delete_r){
				$query = "SELECT e_cotiza_lineas.*,e_pproductos.* FROM e_cotiza_lineas LEFT JOIN e_pproductos ON codigo=codigo_prod 
				WHERE id_cot=$cotizanum";
				if ($items_r=$db->Execute($query)){
					while (!$items_r->EOF){
						if ($_SESSION[nivel]==1){
							$loc_precio=$items_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
						} else {
							$loc_precio=$items_r->fields['precio']*$_SESSION['coef']*$default->descuento;
						} 
						if ($_SESSION[iva]>0){
							$loc_precio = $loc_precio * (1+($items_r->fields['alicuota']/100));	
						}
						$loc_precio = round($loc_precio,$default->decimales);
						$total = $total + ($items_r->fields['cantidad']*$loc_precio);
						if ($_SESSION[iva]>0){
							$tiva = 0;
						} else {
							$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
						}
						$nume=$cotizanum; 
						$tart+=1;
						$items_r->MoveNext();
					}

					$json['total'] = "$tart art&iacute;culo(s) - $".sprintf("%01.2f",$total+$tiva);
			
				} else {
					$json['total'] = "0 art&iacute;culo(s) - $0.00";
				}
				
				header("Content-Type: application/json");
				echo json_encode($json);
		
			}
		}
// Agregar Item al pedido
	} elseif ($cotizacion=="add_item"){
		$idp = $_POST['product_id'];			  
		$cantidad = intval($_POST['quantity']);			  
		$json = array();
		if($idp && $cantidad>0){
			$query = "SELECT * FROM e_pproductos WHERE codigo='$idp'";
			$det_producto=$db->Execute($query);
			if ($det_producto && !$det_producto->EOF){
				$query = "SELECT * FROM e_cotiza WHERE estado=0 AND user_id=$id_usuario";
				$cotiza_r=$db->Execute($query);
				if ($cotiza_r && !$cotiza_r->EOF){
					$id_cotiza = $cotiza_r->fields["id_cotiza"];
				} else {
					$id_cotiza = $db->GenID('seq_e_cotiza');
					$query = "insert into e_cotiza (id_cotiza,user_id,estado,tiva) values ($id_cotiza,$id_usuario,0,$_SESSION[iva])";
					$cotiza_ri=$db->Execute($query);
					if (!$cotiza_ri){
						exit;
					}
				}
				$modelo = $det_producto->fields['modelo'];
				$descripcion = $det_producto->fields['descripcion'];
				$unidad = $det_producto->fields['unidad'];
				$marca = $det_producto->fields['marca'];
				$codfab = $det_producto->fields['codfab'];
				$query = "select count(codigo_prod) as existe from e_cotiza_lineas where codigo_prod='$idp' and id_cot=$id_cotiza";
				$cotiza_ex_p=$db->Execute($query);
				if ($cotiza_ex_p && $cotiza_ex_p->fields['existe']>0){
					$query = "update e_cotiza_lineas set cantidad=cantidad+$cantidad where id_cot=$id_cotiza 
					 and codigo_prod='$idp'";
				} else {
					$query = "insert into e_cotiza_lineas (id_cot, codigo_prod, cantidad, descripcion_prod, modelo_prod, unidad_prod, marca_prod, codfab) values 
							($id_cotiza,'$idp',$cantidad,'$descripcion','$modelo','$unidad','$marca','$codfab')";
				}
				$cotiza_rl=$db->Execute($query);
				if ($cotiza_rl){
					$json['cotizanum']=$id_cotiza;
					$json['success']='El producto se ha agregado con exito!';
					$db->Execute("update e_cotiza set iva=".$_SESSION[iva]." where id_cotiza=$id_cotiza");
				}
			}
		} else {
			$json['error']="Debe indicar la cantidad";
		}
		header("Content-Type: application/json");
		echo json_encode($json);
	} elseif ($cotizacion=="duplicate"){
		$idm=$_GET['idm'];
		$idsm=$_GET['idsm'];
		$cotizanum=$_GET['cotizanum'];
		$url = "index.php?op=$idm&sop=$idsm" ;
		$json = array();
		$query = "SELECT * FROM e_cotiza WHERE estado=0 AND user_id=$id_usuario";
		$cotiza_r=$db->Execute($query);
		if ($cotiza_r && !$cotiza_r->EOF){
			$id_cotiza = $cotiza_r->fields["id_cotiza"];
		} else {
			$id_cotiza = $db->GenID('seq_e_cotiza');
			$query = "insert into e_cotiza (id_cotiza,user_id,estado,iva) values ($id_cotiza,$id_usuario,0,".$_SESSION['iva'].")";
			$cotiza_ri=$db->Execute($query);
			if (!$cotiza_ri){
				echo "<script>document.location='$url';</script>";
				exit;
			}
		}
		$query = "insert into e_cotiza_lineas (id_cot, codigo_prod, cantidad, descripcion_prod, modelo_prod, unidad_prod, marca_prod, codfab)
				SELECT $id_cotiza, e_cotiza_lineas.codigo_prod, e_cotiza_lineas.cantidad, e_pproductos.descripcion
				, e_pproductos.modelo, e_pproductos.unidad, e_pproductos.marca, e_pproductos.codfab
				from e_cotiza_lineas, e_pproductos 
				where e_pproductos.codigo=e_cotiza_lineas.codigo_prod and id_cot=$cotizanum";
		$cotiza_rl=$db->Execute($query);
        if ($cotiza_rl){ 
			$json['success']='El Pedido se ha Duplicado con exito!';
		} else {
			$json['error']='Error';
		}
		echo "<script>document.location='$url';</script>";
		
		
// Obtener Informacion
	} elseif ($cotizacion=="info"){
		if ($_POST['cotizanum']){
			$cotizanum = $_POST['cotizanum'];
		} else {
			$cotizanum = $_GET['cotizanum'];
		}
		$query = "SELECT e_cotiza_lineas.*,e_pproductos.*, e_cotiza.* FROM e_cotiza, e_cotiza_lineas LEFT JOIN e_pproductos ON e_pproductos.codigo=e_cotiza_lineas.codigo_prod  
		WHERE e_cotiza_lineas.id_cot=$cotizanum and e_cotiza.estado=0 and e_cotiza_lineas.id_cot=e_cotiza.id_cotiza";
		if ($items_r=$db->Execute($query)){
			$tart = 0;
			while (!$items_r->EOF){
				if ($_SESSION[nivel]==1){
					$loc_precio=$items_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
				} else {
					$loc_precio=$items_r->fields['precio']*$_SESSION['coef']*$default->descuento;
				} 
				if ($_SESSION[iva]>0){
					$loc_precio = $loc_precio * (1+($items_r->fields['alicuota']/100));	
				}
				$loc_precio = round($loc_precio,$default->decimales);
				$total = $total + ($items_r->fields['cantidad']*$loc_precio);
				if ($_SESSION[iva]>0){
					$tiva = 0;
				} else {
					$tiva = $tiva + ($items_r->fields['cantidad']*$loc_precio*$items_r->fields['alicuota']/100);
				}
				$products[] = array(
					'quantity' => $items_r->fields['cantidad'],
					'name'     => htmlentities($items_r->fields['descripcion_prod'],ENT_QUOTES,$default->encode),
					'total'    => ($items_r->fields['cantidad']*$loc_precio),
					'code'     => $items_r->fields['codigo_prod'],
					'id_lin'   => $items_r->fields['id_lin'],
		
				);
				$nume=$cotizanum; 
				$tart+=1;
				$items_r->MoveNext();
			}
			if ($tart > 0){
			  	echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">$tart art&iacute;culo(s) - $".sprintf("%01.2f",$total+$tiva)."</span></button>\n";
				echo "<ul class=\"dropdown-menu pull-right\">\n";
				echo "<li>\n";
				echo "<table class=\"table table-striped\">\n";
				foreach ($products as $product){
					echo "<tr>\n";
					echo "<td class=\"text-left\">".$product['code']."</td>\n";
					echo "<td class=\"text-left\">".$product['name']."</td>\n";
					echo "<td class=\"text-right\">".$product['quantity']."</td>\n";
					echo "<td class=\"text-right\">$".sprintf("%01.2f",$product['total'])."</td>\n";
					echo "<td class=\"text-center\"><button type=\"button\" onclick=\"cart.remove('".$nume."','".$product['id_lin']."');\" title=\"Eliminar\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></button></td>\n";
					echo "</tr>\n";
			   }
				echo "</table>\n";
				echo "</li>\n";
				echo "<li>\n";
				echo "<div>\n";
				echo "<table class=\"table table-bordered\">\n";
				if ($_SESSION[iva]==0){
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>Subtotal</strong></td>\n";
					echo "<td class=\"text-right\">$".sprintf("%01.2f",$total)."</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "<td class=\"text-right\"><strong>Total IVA</strong></td>\n";
					echo "<td class=\"text-right\">$".sprintf("%01.2f",$tiva)."</td>\n";
					echo "</tr>\n";
				}
				echo "<tr>\n";
				echo "<td class=\"text-right\"><strong>Total</strong></td>\n";
				echo "<td class=\"text-right\">$".sprintf("%01.2f",$total+$tiva)."</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				echo "<p class=\"text-right\"><a href=\"index.php?op=pedidos&cotizacion=show&cotizanum=$nume"."\"><strong><i class=\"fa fa-shopping-cart\"></i>&nbsp;&nbsp;&nbsp;Pedido</strong></a></p>\n";
				echo "</div>\n";
				echo "</li>\n";
				echo "</ul>\n";
			} else {
				echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">0 art&iacute;culo(s) - $0.00</span></button>\n";
				echo "<ul class=\"dropdown-menu pull-right\"><li><p class=\"text-center\">Su pedido se encuentra vac&iacute;o!</p></li></ul>\n";
			}
		} else {
			echo "<i class=\"fa fa-shopping-cart\"></i> <span id=\"cart-total\">0 art&iacute;culo(s) - $0.00</span></button>\n";
			echo "<ul class=\"dropdown-menu pull-right\"><li><p class=\"text-center\">No se encuentran pedidos abiertos!</p></li></ul>\n";
		}	
	}

	if ($wishlist=='add'){
		$json = array();
		$result=$db->Execute("INSERT INTO e_prod_favoritos (id_user,cod_prod) VALUES($id_usuario,'$product_id')");
		if ($result){
			$json['success']='Success';
		} else {
			$json['error']='Error';
		}
		header("Content-Type: application/json");
		echo json_encode($json);

	} elseif ($wishlist=='remove'){
		$json = array();
		$result=$db->Execute("DELETE FROM e_prod_favoritos WHERE id_user=$id_usuario AND cod_prod='$product_id'");
		if ($result){
			$json['success']='Success';
		} else {
			$json['error']='Error';
		}
		header("Content-Type: application/json");
		echo json_encode($json);
	}

}
?>
