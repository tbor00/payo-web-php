<?php
$loc_title = $default->web_title . " - ".translate("Productos");
$db = connect();
$db->debug = SDEBUG;
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="es" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="es" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="es">
<!--<![endif]-->
<HEAD> 
<TITLE><?php echo $loc_title ?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1" />
<META NAME="Keywords" CONTENT="<?php echo mk_keywords() ?>" />
<META NAME="Copyright" CONTENT="<?php echo $default->copyright ?>" />
<META NAME="Author" CONTENT="<?php echo $default->author ?>" />
<META NAME="Description" CONTENT="<?php echo $default->site_description ?>">
<LINK HREF="favicon.ico" REL="Shortcut Icon" TYPE="image/x-icon" />
<LINK HREF="favicon.ico" REL="icon" TYPE="image/x-icon" />
<link href="theme/stylesheet/print.css" rel="stylesheet">
<script type="text/javascript" src="jscripts/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
$(window).load(function() {
  var ua = navigator.userAgent.toLowerCase();
  var isEPAPP = ua.indexOf("electropuertoapp") > -1; //&& ua.indexOf("mobile");

    if (isEPAPP) {
      // https://developers.google.com/cloud-print/docs/gadget
      //var gadget = new cloudprint.Gadget();
      //gadget.setPrintDocument("url", $('title').html(), window.location.href, "iso-8859-1");
      //gadget.openPrintDialog();
	  //EPAPP.Print(document.documentElement.outerHTML);
	  window.print();
    } else {
      window.print();
    }
    return false;
});
</script>
</HEAD>
<BODY style="font-size: 9pt;"> 
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#eeeeee">
<tr>
<td width="70%" class=\"text-left\">
<img src="image/logo.gif" title="<?php echo $default->web_title ?>" alt="<?php echo $default->web_title ?>" class="img-responsive" />
</td>
<td width="30%" class=\"text-left\">
<?php
$query = "SELECT * from e_parametros where id_param=1";
$result = $db->Execute($query);
if ($result && !$result->EOF){
	echo "<ul style=\"padding-left: 5px;list-style: none;\">\n";
	echo "<li>".$result->fields['pie_1']."</li>\n";
	echo "<li>".$result->fields['pie_2']."</li>\n";
	echo "<li>".$result->fields['pie_3']."</li>\n";
	echo "<li>".$result->fields['email']."</li>\n";
	echo "</ul>\n";
}
?>
</td>
</tr>
</table>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" BGCOLOR="#FFFFFF">
<TR>
<TD ALIGN="CENTER" VALIGN="TOP"></TD>
</TR>
<?php
if ($_SESSION[logged]){

	if ($_POST['buscar']!=''){
		$buscar = $_POST['buscar'];			  
	} elseif ($_GET['buscar']!=''){
		$buscar = $_GET['buscar'];
	}
	if ($_POST['tipo']!=''){
		$tipo = $_POST['tipo'];
	} elseif ($_GET['tipo']!=''){
		$tipo = $_GET['tipo'];
	}
	if ($_POST['marca']!=''){
		$marca = $_POST['marca'];
	} elseif ($_GET['marca']!=''){
		$marca = $_GET['marca'];
	}
	$ord = $_GET['ord'];
	$orden = $_GET['orden'];

//	$subquery.="  WHERE (e_pprod_marcas.nivel=0 OR e_pprod_marcas.nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).")";
//	$inner =" INNER JOIN e_pprod_marcas ON e_pproductos.marca = e_pprod_marcas.marca";
	$subquery.="  WHERE e_pproductos.marca IN (SELECT marca FROM e_pprod_marcas WHERE nivel=0 OR nivel=".($_SESSION['nivel']+1).") AND e_pproductos.proveedor IN (SELECT proveedor FROM e_pprod_proveedores WHERE nivel=0 OR nivel=".($_SESSION['nive']+1).")";

	
	if ($buscar!=''){
		if ($subquery != ''){
			$subquery .= " AND (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}else{
			$subquery=" WHERE (descripcion LIKE '%$buscar%' or modelo LIKE '%$buscar%' or codfab LIKE '%$buscar%')";
		}
	}
	
	if ($tipo!=''){
		if ($subquery != ''){
			$subquery .= " AND e_pproductos.tipo='$tipo'";
		}else{
			$subquery = " WHERE e_pproductos.tipo='$tipo'";
		}
	}
	if ($marca!=''){
		if ($subquery != ''){
			 $subquery .= " AND e_pproductos.marca='$marca'";
		}else{
			 $subquery = " WHERE e_pproductos.marca='$marca'";
		}
	}

	if ($orden == ""){
	  $orden="descripcion";
	}
	if ($ord != "ASC" && $ord != "DESC"){
	  $ord="ASC";
	}
	if (isset($orden) and strlen($orden)>0) {
		$ORDER="order by $orden $ord";
	}

	$color1 = "#FFFFFF";  
	$color2 = "#F8F8F8";

	$query = "SELECT * FROM e_pproductos".$inner.$subquery." $ORDER";


	$producto_r=$db->Execute($query);
	if ($producto_r && !$producto_r->EOF){

		echo "<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"1\" BORDER=\"0\">";
		echo "<TR style=\"background-color: #C0C0C0\">";
		echo "<TH style=\"border: 1pt solid; \">Cod&nbsp;</TH>";
		echo "<TH style=\"border: 1pt solid; \">Descripci&oacute;n&nbsp;</TH>";
		echo "<TH style=\"border: 1pt solid; \">Marca&nbsp;</TH>";
		echo "<TH style=\"border: 1pt solid; \">Modelo&nbsp;</TH>";
		echo "<TH style=\"border: 1pt solid; \">Cod Fab&nbsp;</TH>";
		echo "<TH style=\"border: 1pt solid; \">Precio&nbsp;</TH>";
		echo "</TR>";
		while(!$producto_r->EOF){
			if ($colorx==$color1){
				$colorx=$color2;
			}else{
				$colorx=$color1;
			}
			echo "<TR style=\"background-color: $colorx\">\n";
			echo "<TD style=\"text-align:left;border-bottom: 1pt solid; \">".htmlentities($producto_r->fields['codigo'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD style=\"text-align:left;border-bottom: 1pt solid; \">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD style=\"text-align:left;border-bottom: 1pt solid; \">".htmlentities($producto_r->fields['marca'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD style=\"text-align:left;border-bottom: 1pt solid; \">".htmlentities($producto_r->fields['modelo'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD style=\"text-align:left;border-bottom: 1pt solid; \">".htmlentities($producto_r->fields['codfab'],ENT_QUOTES,$default->encode)."</TD>\n";
			if ($_SESSION[nivel]==1){
				$precio = $producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio = $producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo "<TD style=\"text-align:right;border-bottom: 1pt solid; \">".$producto_r->fields['moneda']." ".decimales($precio)."</TD>\n";
			$producto_r->MoveNext();
		}
		echo "</TABLE>";
	} else {
		echo "<TR><TD>ERROR: Producto no encontrado.</TD></TR>";
	}
}
?>
<TR>
<TD ALIGN="CENTER" VALIGN="TOP"><HR COLOR="#dedede"></TD>
</TR>
<TR>
<TD CLASS="textobrowse"><?php echo date("d/m/Y H:i"); ?></TD>
</TR>
</TABLE>   
</BODY>
</HTML>
