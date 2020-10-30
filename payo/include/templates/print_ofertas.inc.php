<?php
$loc_title = $default->web_title." - ".translate("Ofertas");
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
<LINK HREF="theme/stylesheet/stylesheet.css" rel="stylesheet">
</HEAD>
<BODY style="font-size: 9pt;" ONLOAD="window.print();"> 
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

<?php
if ($_SESSION[logged]){
	$destino = $_SESSION[nivel]+1;
	$query = "SELECT e_ofertas.*,e_pproductos.* FROM e_ofertas JOIN e_pproductos ON codigo=cod_prod WHERE (destino=0 OR destino=$destino) AND fecha_baja>now() ORDER BY rand()";
	$producto_r=$db->Execute($query);
	if ($producto_r && !$producto_r->EOF){
		$col=1;
		$lineas=1;
		echo  "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"5\" BGCOLOR=\"#FFFFFF\">";
		while(!$producto_r->EOF) {

			if ($col==1){
				if ($lineas==8){
					echo "<TR CLASS=\"page-break\" VALIGN=\"TOP\">";
					$lineas=1;
				} else {
					echo "<TR CLASS=\"page-junto\" VALIGN=\"TOP\">";
				}
			}
			echo "<TD VALIGN=\"TOP\" ALIGN=\"CENTER\" WIDTH=\"33%\">";
			echo "<TABLE CLASS=\"table-ofert\" CELLPADDING=\"2\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\" WIDTH=\"340\">\n";
			echo "<TR><TD>";
			echo "<TABLE CLASS=\"table-sofert\" BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\" BGCOLOR=\"#FFFFFF\" width=\"100%\">\n";
			echo "<TR><TD VALIGN=\"TOP\">";
			if ($producto_r->fields['imagen']!=''){
				$p_image = similar_file_exists("products/imagenes/".$producto_r->fields['imagen']);
			} else {
				$p_image = '';
			}
			if ($p_image!='') {
			   $partes_ruta = pathinfo($p_image);
				if (file_exists("products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']))){
					$t_image = "products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']);
				}else{
					convert_image($p_image,"products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']),"120x120","80");
					$t_image = "products/miniaturas/".strtolower($partes_ruta['filename'])."-120x120.".strtolower($partes_ruta['extension']);
				}
				echo "<IMG SRC=\"".$t_image."\" BORDER=\"0\" title=\"".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."\" />";
			} else {
				echo "<IMG SRC=\"image/blank.gif\" WIDTH=\"120\" HEIGHT=\"62\" BORDER=\"0\">";
				
			}
			echo "</TD><TD VALIGN=\"TOP\">\n";
			echo "<TABLE CLASS=\"table-sofert\" WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\">\n";
			echo "<TR>\n";
			echo "<TD ALIGN=\"LEFT\"><SPAN CLASS=\"code-ofert\">".$producto_r->fields['codigo']."</SPAN></TD></TR><TR>\n";
			echo "<TD ALIGN=\"LEFT\"><span style=\"font-size: 15px; font-weight: bold;\">".htmlentities($producto_r->fields['descripcion'],ENT_QUOTES,$default->encode)."</span></TD></TR><TR>\n";
			echo "<TD ALIGN=\"LEFT\"><SPAN>".$producto_r->fields['oferta']."</SPAN></TD></TR><TR>\n";
			echo "<TD ALIGN=\"LEFT\">&nbsp;</TD></TR><TR>\n"; 
			echo "<TD ALIGN=\"LEFT\">&nbsp;</TD></TR><TR>\n";
			echo "<TD ALIGN=\"LEFT\"><SPAN CLASS=\"price-ofert\">";
			echo $producto_r->fields['moneda']." ";
			if ($_SESSION["nivel"]==1){
				$precio=$producto_r->fields['precio2']*$_SESSION['coef'];
			} else {
				$precio=$producto_r->fields['precio']*$_SESSION['coef'];
			}
			if ($_SESSION[iva]>0){
				$precio = $precio * (1+($producto_r->fields['alicuota']/100));	
			}
			echo decimales($precio);
			echo "</SPAN></TD></TR>";
			echo "<TR><TD ALIGN=\"LEFT\"><SPAN STYLE=\"font-size: 6pt\">Oferta v&aacute;lida hasta el ".timesql2std($producto_r->fields['fecha_baja'])."</SPAN></TD></TR>";
			echo "</TABLE></TD></TR></TABLE></TD>\n";
			echo "</TD></TR>";
			echo "</TABLE>\n";		
			echo "</TD>";
			$col++;
			if ($col==4){
				echo "</TR>\n";
				$col=1;
				$lineas++;
			}
			$producto_r->MoveNext();
		}
		echo "</TABLE>";
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