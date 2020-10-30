<?php
setlocale(LC_CTYPE , "en_US");
if ($_POST['buscar']!=''){
	$buscar = rawurldecode($_POST['buscar']);			  
} elseif ($_GET['buscar']!=''){
	$buscar = rawurldecode($_GET['buscar']);
}
if ($_POST['favoritos']!=''){
	$favoritos = rawurldecode($_POST['favoritos']);			  
} elseif ($_GET['favoritos']!=''){
	$favoritos = rawurldecode($_GET['favoritos']);
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
if ($_POST['mode']!=''){
	$mode = rawurldecode($_POST['mode']);
} elseif ($_GET['mode']!=''){
	$mode = rawurldecode($_GET['mode']);
}

if ($_POST['ord']!=''){
	$ord = rawurldecode($_POST['ord']);
} elseif ($_GET['ord']!=''){
	$ord = rawurldecode($_GET['ord']);
}
if ($_POST['orden']!=''){
	$orden = rawurldecode($_POST['orden']);
} elseif ($_GET['orden']!=''){
	$orden = rawurldecode($_GET['orden']);
}

$pag = rawurldecode($_GET['pag']);

if ($favoritos != 'true'){
	$favoritos='';
}	

if ($mode!='list' && $mode!='gallery'){
	$mode='list';
}
if ($orden!='descripcion' && $orden!='precio' && $orden!='codfab' && $orden!='marca'){
  $orden="descripcion";
}

if ($ord != "ASC" && $ord != "DESC"){
  $ord="ASC";
}

?>
<SCRIPT>
//---------------------------------------------------------
function filtratipo(form){
  var myindex1=form.tipo.selectedIndex;
  var myindex2=form.marca.selectedIndex;
  location="index.php?op="+form.op.value+"&tipo="+form.tipo.options[myindex1].value+"&marca="+form.marca.options[myindex2].value;
}
//---------------------------------------------------------
function filtramarca(form){
  var myindex1=form.tipo.selectedIndex;
  var myindex2=form.marca.selectedIndex;
  location="index.php?op="+form.op.value+"&tipo="+form.tipo.options[myindex1].value+"&marca="+form.marca.options[myindex2].value;
}
//---------------------------------------------------------
$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:false
		}
	});

});
//---------------------------------------------------------
$(document).ready(function() {
	$('.image').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:false
		}
	});

});
//---------------------------------------------------------
</SCRIPT>
<FORM ACTION="<?php echo $_SERVER[PHP_SELF] ?>" METHOD="POST" NAME="cons_prod">
<INPUT NAME="op" VALUE="<?php echo $op ?>" TYPE="HIDDEN">
<INPUT NAME="sop" VALUE="<?php echo $sop ?>" TYPE="HIDDEN">
<INPUT NAME="mode" VALUE="<?php echo $mode ?>" TYPE="HIDDEN">
<INPUT NAME="orden" VALUE="<?php echo $orden ?>" TYPE="HIDDEN">
<INPUT NAME="ord" VALUE="<?php echo $ord ?>" TYPE="HIDDEN">
<INPUT NAME="favoritos" VALUE="<?php echo $favoritos ?>" TYPE="HIDDEN">

<div class="row">
	<div class="col-md-2 text-right">
		<label class="control-label" for="input-type">Tipo de Producto</label>
	</div>
	<div class="col-md-3 text-right">
		<select name="tipo" id="input-type" class="form-control" onchange="filtratipo(this.form)">
		<option value="">Todos los tipos</option>
	<?php
	if ($marca!=''){
		$query = "SELECT DISTINCT e_pprod_tipos.tipo, e_pprod_tipos.id_tipo 
		FROM (e_pproductos INNER JOIN e_pprod_marcas ON e_pproductos.marca = e_pprod_marcas.marca) 
		INNER JOIN e_pprod_tipos ON e_pproductos.tipo = e_pprod_tipos.tipo $subquery AND e_pprod_marcas.marca=".$db->qstr($marca)." order by e_pprod_tipos.tipo";
	} else {
		$query = "SELECT DISTINCT e_pprod_tipos.tipo FROM e_pprod_tipos INNER JOIN e_pproductos ON e_pproductos.tipo = e_pprod_tipos.tipo $subquery ORDER by tipo";
	}		
	$tipo_r=$db->Execute($query);
	while (!$tipo_r->EOF){
		if ($tipo_r->fields['tipo']==stripslashes($tipo)){
			echo "<OPTION VALUE=\"".rawurlencode($tipo_r->fields['tipo'])."\" SELECTED>".htmlentities($tipo_r->fields['tipo'],ENT_QUOTES,$default->encode)."</OPTION>\n";
		}else{
			echo "<OPTION VALUE=\"".rawurlencode($tipo_r->fields['tipo'])."\">".htmlentities($tipo_r->fields['tipo'],ENT_QUOTES,$default->encode)."</OPTION>\n";
		}
		$tipo_r->MoveNext();
	}
	echo "</SELECT>\n";
	?>
	</div>
	<div class="col-md-1 text-right">
		<label class="control-label" for="input-marca">Marca del Producto</label>
	</div>
	<div class="col-md-2 text-right">
		<select name="marca" id="input-marca" class="form-control" onchange="filtramarca(this.form);">
		<option value="">Todas las Marcas</option>
		<?php
		$subquery = " WHERE (e_pprod_marcas.nivel=0 OR e_pprod_marcas.nivel=".($_SESSION['nivel']+1).") ";
		if ($tipo!=''){
			$query = "SELECT DISTINCT e_pprod_marcas.marca, e_pprod_marcas.id_marca 
			FROM (e_pproductos INNER JOIN e_pprod_tipos ON e_pproductos.tipo = e_pprod_tipos.tipo) 
			INNER JOIN e_pprod_marcas ON e_pproductos.marca = e_pprod_marcas.marca $subquery AND e_pprod_tipos.tipo=".$db->qstr($tipo)."";
		}else{
			$query = "SELECT DISTINCT e_pprod_marcas.marca FROM e_pprod_marcas INNER JOIN e_pproductos ON e_pproductos.marca=e_pprod_marcas.marca $subquery ORDER BY marca";
		}
		$marca_r=$db->Execute($query);
		while (!$marca_r->EOF){
			if ($marca_r->fields['marca']==stripslashes($marca)){
				echo "<option value=\"".rawurlencode($marca_r->fields['marca'])."\" SELECTED>".htmlentities($marca_r->fields['marca'],ENT_QUOTES,$default->encode)."</option>\n";
			}else{
				echo "<option value=\"".rawurlencode($marca_r->fields['marca'])."\">".htmlentities($marca_r->fields['marca'],ENT_QUOTES,$default->encode)."</option>\n";
			}
			$marca_r->MoveNext();
		}
		?>
		</select>
	</div>
	<div class="col-md-1 text-right">
		<label class="control-label" for="input-buscar">Producto</label>
	</div>
	<div class="col-md-2 text-right">
		<div id="search" class="input-group">
		<input type="search" name="buscar" placeholder="Buscar" id="input-buscar" class="form-control" value="<?php echo html_entity_decode($buscar)?>">
		<span class="input-group-btn">
		<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		</span>	
		</div>
  	</div>


</div>
</FORM>


<?php
//------------ FIN FORMULARIO DE BUSQUEDA

$qlineas = 30;
$subquery="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).")";

//$subquery.=" AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nive']+1).")";
//$inner =" INNER JOIN e_pprod_marcas ON e_pproductos.marca = e_pprod_marcas.marca";
if ($buscar!=''){
	$str_buscar = $db->qstr("%".$buscar."%");
	if ($subquery != ''){
		$subquery .= " AND (codigo LIKE ".$str_buscar." or descripcion LIKE ".$str_buscar." or modelo LIKE ".$str_buscar." or codfab LIKE ".$str_buscar.")";
	} else {
		$subquery= " WHERE (codigo LIKE ".$str_buscar." or descripcion LIKE ".$str_buscar." or modelo LIKE ".$str_buscar." or codfab LIKE ".$str_buscar.")";
	}
}
if ($tipo!=''){
	if ($subquery != ''){
		$subquery .= " AND e_pproductos.tipo=".$db->qstr($tipo)."";
	}else{
		$subquery = " WHERE e_pproductos.tipo=".$db->qstr($tipo)."";
	}
}
if ($marca!=''){
	if ($subquery != ''){
		 $subquery .= " AND e_pproductos.marca=".$db->qstr($marca)."";
	}else{
		 $subquery = " WHERE e_pproductos.marca=".$db->qstr($marca)."";
	}
}
if ($favoritos=="true"){
	$inner.=" JOIN e_prod_favoritos ON codigo=cod_prod ";
	if ($subquery != ''){
		 $subquery .= " AND id_user={$_SESSION['uid']}";
	}else{
		 $subquery = " WHERE id_user={$_SESSION['uid']}";
	}
}


$query = "SELECT COUNT(codigo) FROM e_pproductos".$inner.$subquery;
$count = $db->Execute($query); 
if ($count && !$count->EOF){
	$cant_reg = $count->fields[0];
}

if (isset($orden) and strlen($orden)>0) {
	$url_orden="&orden=$orden&ord=$ord";
	if ($_SESSION['nivel']==1 && $orden=='precio'){
		$ordenx='precio2';
	} else {
		$ordenx=$orden;
	}	
		$ORDER="order by $ordenx $ord";
}
if (isset($buscar) and strlen($buscar)>0) {
	$url_busqueda="&buscar=$buscar";
}
if ($tipo != '' ){
	$url_busqueda .= "&tipo=".rawurlencode($tipo);
}
if ($marca != ''){
	$url_busqueda .= "&marca=".rawurlencode($marca);
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


if ($pag == '' || $pag == 0 || !is_numeric($pag)){
 	$pag = 1;
}
$from = ($pag-1) * $qlineas;


echo "<div class=\"row\">";								  
echo "<span class=\"col-sm-6 text-left\">";
echo "<A HREF=\"$_SERVER[PHP_SELF]?op=$op&sop=$sop$url_orden$url_busqueda&mode=list&favoritos=$favoritos&pag=$pag\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Lista\"><i class=\"fa fa-th-list\"></i></A>";
echo "<A HREF=\"$_SERVER[PHP_SELF]?op=$op&sop=$sop$url_orden$url_busqueda&mode=gallery&favoritos=$favoritos&pag=$pag\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Grilla\"><i class=\"fa fa-th\"></i></A>";
if ($_SESSION[logged]){
	if ($favoritos=="true"){
		echo "<A HREF=\"$_SERVER[PHP_SELF]?op=$op&sop=$sop$url_orden$url_busqueda&mode=$mode&favoritos=false&pag=1\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Mostrar Todos\"><i class=\"fa fa-star\"></i></A>";
	} else {
		echo "<A HREF=\"$_SERVER[PHP_SELF]?op=$op&sop=$sop$url_orden$url_busqueda&mode=$mode&favoritos=true&pag=1\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Mostrar Favoritos\"><i class=\"fa fa-star-o\"></i></A>";
	}
}	
echo "</span>";
if ($_SESSION[logged]){
	echo "<span class=\"col-sm-6 text-right\">";
	echo "<A HREF=\"auxiliar.php?op=print$url_orden$url_busqueda\" TARGET=\"_print\"  class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Versi&oacute;n para Imprimir\"><i class=\"fa fa-print\"></i></A>";
	echo "<A HREF=\"auxiliar.php?op=xls_list$url_orden$url_busqueda\" TARGET=\"_print\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"Exportar\"><i class=\"fa fa-file-excel-o\"></i></A>";
	echo "</span>";
}
echo "</div>";
echo "<br>";

$query = "SELECT e_pproductos.* FROM e_pproductos".$inner.$subquery." $ORDER limit $from, $qlineas";
if ($producto_r=$db->Execute($query)){
// Modo Galeria	
	if ($mode=='gallery'){
		$col=0;
		while (!$producto_r->EOF){
			if($producto_r->fields['marca_a']==3){
				$bgc=""; //"bg-yellow";
			} elseif($producto_r->fields['marca_a']==1){
				$bgc=""; //"bg-green";
			} else {
				$bgc="";
			}
			if ($col==0){
				echo "<div class=\"row\">";
			}
			echo "<div class=\"product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12\">\n";
			echo "<div class=\"product-thumb $bgc\">\n";
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
			   $partes_ruta = pathinfo($p_image);
				if (file_exists("products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']))){
					$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-120x120.".strtolower($partes_ruta['extension']);
				}else{
					convert_image($p_image,"products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']),"120x120","80");
					$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-120x120.".strtolower($partes_ruta['extension']);
				}
				echo "<div class=\"image\" style=\"margin-top:5px;\"><A HREF=\"".$p_image."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"><IMG SRC=\"".$t_image."\" BORDER=\"0\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"></A></div>";
			}
			echo "<div class=\"caption\">";
			echo "<h4><A CLASS=\"agree\" HREF=\"auxiliar.php?op=productos&idp=".$producto_r->fields['codigo']."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</A></h2>";
			echo "<p><STRONG>C&oacute;digo: </STRONG><SPAN CLASS=\"textobrowse\">".$producto_r->fields['codigo']."</SPAN></p>";
			echo "<p><STRONG>Marca: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['marca'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Modelo: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['modelo'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Cod. Fab.: </STRONG><SPAN CLASS=\"textobrowse\">".htmlentities($producto_r->fields['codfab'],ENT_QUOTES,$default->encode)."</p>";
			echo "<p><STRONG>Precio: </STRONG><SPAN CLASS=\"textobrowse\">".$producto_r->fields['moneda']." ";
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo decimales($precio)."</p>\n";
			echo "</div>";
			echo "</div>";			
			echo "</div>";			
			$col++;
			if ($col == 3 || $col==0){
				echo "</div>";			
				$col=0;
			}
			
			$producto_r->MoveNext();
		}
		echo "</div>\n";		
		echo paginar($pag, $cant_reg, $qlineas, $_SERVER[PHP_SELF]."?op=$op&sop=$sop$url_orden$url_busqueda&mode=$mode&favoritos=$favoritos&pag=");
// Modo Listado 		
	} else {
		echo "<div class=\"row\">";
		echo "<div class=\"table-responsive\">";
		echo "<table class=\"table table-bordered table-hover\">\n";
		echo "<thead>\n";
		echo "<TR>\n";
		echo Titulo_Browse("C&oacute;d","codigo",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0,"center");
		echo Titulo_Browse("<i class=\"fa fa-camera\"></i>","","","","",0,0,"center");
		echo Titulo_Browse("Descripci&oacute;n","descripcion",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0);
		echo Titulo_Browse("Marca","marca",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0);
		//echo Titulo_Browse("Modelo","modelo",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0);
		echo Titulo_Browse("Cod Fab","codfab",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0);
		echo Titulo_Browse("Unidades x Caja","unven",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",0,0);
		echo Titulo_Browse("Stock","stock",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",0,0,"center");
		echo Titulo_Browse("Precio","precio",$orden,$ord,$_SERVER['PHP_SELF']."?op=$op&sop=$sop&pag=$pag$url_busqueda&favoritos=$favoritos",1,0,"center");
		echo "</TR>\n";
		echo "</thead>\n";
		
		if ($producto_r->EOF){
			echo "<TR>\n";
			echo "<TD COLSPAN=\"8\" CLASS=\"text-center\">No se encontraron productos con los datos suministrados...</TD>\n";
			echo "</TR>\n";
		}
		while (!$producto_r->EOF){
			$estrella = 0;
			if($producto_r->fields['marca_a']==3){
				$bgc=""; //"bg-yellow";
			} elseif($producto_r->fields['marca_a']==1){
				$bgc=""; //"bg-green";
			} else {
				$bgc="";
			}
			echo "<TR CLASS=\"$bgc\">\n";
			echo "<TD CLASS=\"text-center\">".$producto_r->fields['codigo']."</TD>\n";
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("products/imagenes/".$producto_r->fields['imagen']);
				
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
			   $partes_ruta = pathinfo($p_image);
				if (file_exists("products/miniaturas/".strtolower($partes_ruta['filename'])."-47x47.".strtolower($partes_ruta['extension']))){
					$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-47x47.".strtolower($partes_ruta['extension']);
				}else{
					convert_image($p_image,"products/miniaturas/".strtolower($partes_ruta['filename'])."-47x47.".strtolower($partes_ruta['extension']),"47x47","80");
					$t_image = "products/miniaturas/".rawurlencode(strtolower($partes_ruta['filename']))."-47x47.".strtolower($partes_ruta['extension']);
				}
				echo "<TD CLASS=\"text-center\"><ul class=\"thumbnails\"><li><A CLASS=\"thumbnail\" HREF=\"".$p_image."\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"><IMG SRC=\"".$t_image."\" BORDER=\"0\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\"></A></li></ul></TD>";
			} else {
				echo "<TD CLASS=\"text-center\">&nbsp;</TD>";
			}
			echo "<TD CLASS=\"text-left\"><A CLASS=\"view_product\" HREF=\"auxiliar.php?op=productos&idp=".$producto_r->fields['codigo']."\">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</A></TD>\n";
			echo "<TD CLASS=\"text-left\">".htmlentities($producto_r->fields['marca'],ENT_QUOTES,$default->encode)."</TD>\n";
			//echo "<TD CLASS=\"text-left\">".htmlentities($producto_r->fields['modelo'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD CLASS=\"text-left\">".htmlentities($producto_r->fields['codfab'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD CLASS=\"text-left\">".htmlentities($producto_r->fields['unven'],ENT_QUOTES,$default->encode)."</TD>\n";
/*
			if ($producto_r->fields['stock'] > 0){
				echo "<TD CLASS=\"text-right\">".htmlentities(sprintf('%01.0f',$producto_r->fields['stock']),ENT_QUOTES,$default->encode)."</TD>\n";
			} else {
				echo "<TD CLASS=\"text-right\">"."7 D&iacute;as"."</TD>\n";
			}
*/
			if ($producto_r->fields['stock'] > 0){
				echo "<TD style=\"vertical-align: middle;\" CLASS=\"text-center\"><i class=\"fa fa-battery-full\" style=\"color:green;\"></i></TD>\n";
			} else {
				echo "<TD style=\"vertical-align: middle;\" CLASS=\"text-center\"><i class=\"fa fa-battery-quarter\" style=\"color:red;\"></i></TD>\n";
			}
		
			echo "<TD CLASS=\"text-right\">".$producto_r->fields['moneda']." ";
			if ($_SESSION['nivel']==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef']*$default->descuento;
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef']*$default->descuento;
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo decimales($precio)."</TD>\n";
			echo "</TR>\n";
			$producto_r->MoveNext();
		}
		echo "</TABLE>\n";
		echo "</div></div>\n";		
		echo paginar($pag, $cant_reg, $qlineas, $_SERVER['PHP_SELF']."?op=$op&sop=$sop$url_orden$url_busqueda&mode=$mode&favoritos=$favoritos&pag=");

	}
} else {
	echo "<P>ERROR: Al conectarse a la base de datos</P>";
}
?>
<P><P>
