<?php
$loc_title = $default->web_title . " - ".translate("Comprobantes Pendientes");
$db = connect();
$db->debug = SDEBUG;
$gestion=$_SESSION['gestion'];
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
</TR>
<?php
if ($_SESSION[logged]){

	$id_usuario=$_SESSION['uid'];
	$saldo_ctacte = 0;

	$user_query = "select * from e_webusers where user_id=$id_usuario" ;
	$user_res=$db->Execute($user_query);
	if($user_res && ! $user_res->EOF ){
		$cliente_eb=$user_res->fields['eb_cod'];
		$razon_social=$user_res->fields['razonsocial']; 
		$cuit=$user_res->fields['cuit'];
	}	
	$color1 = "#FFFFFF";  
	$color2 = "#F8F8F8";
	
	echo "<tr><TABLE CELLPADDING=\"3\" CELLSPACING=\"1\" BORDER=\"0\">";
	echo "<tr><th style=\"text-align:left;\">Comprobantes Pendientes</th></tr>";
	echo "<tr><th style=\"text-align:left;\">Cliente: $razon_social</th></tr>";
	echo "<tr><th style=\"text-align:left;\">C.U.I.T: $cuit</th></tr>";
	echo "</table></tr>";

	$query = "SELECT (credito*-1+debito) as importe,comprobante,numero, IF(credito>0,saldo*-1,saldo) as saldo ,referencia,fecha,vto FROM e_ctasctes WHERE estado<>'CAN' AND saldo<>0 AND comprobante<>'NCI' AND comprobante<>'NDI'
	          AND cliente_eb=$cliente_eb AND gestion='".$gestion."' ORDER by fecha,id ";
	$ctacte_r=$db->Execute($query);
	if ($ctacte_r && !$ctacte_r->EOF){
		echo "<TR><TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"1\" BORDER=\"0\">";
		echo "<TR style=\"background-color: #C0C0C0\">";
		echo "<TH style=\"border: 1pt solid; \">Comprobante</TH>";
		echo "<TH style=\"border: 1pt solid; \">Fecha</TH>";
		echo "<TH style=\"border: 1pt solid; \">Vto.</TH>";
		echo "<TH style=\"border: 1pt solid; \">Ref.</TH>";
		echo "<TH style=\"border: 1pt solid; \">Importe</TH>";
		echo "<TH style=\"border: 1pt solid; \">Saldo Comp.</TH>";
		echo "<TH style=\"border: 1pt solid; \">Saldo</TH>";
		echo "</TR>";
		while(!$ctacte_r->EOF){
			if ($colorx==$color1){
				$colorx=$color2;
			}else{
				$colorx=$color1;
			}
			$saldo_ctacte = $saldo_ctacte + $ctacte_r->fields['saldo'];
			echo "<TR style=\"background-color: $colorx\">\n";
			echo "<TD style=\"text-align:left;\">".$ctacte_r->fields['comprobante'].'&nbsp;'.$ctacte_r->fields[numero]."</TD>\n";
			echo "<TD style=\"text-align:right;\">".timesql2std($ctacte_r->fields['fecha'])."</TD>\n";
			echo "<TD style=\"text-align:right;\">";
			if ($ctacte_r->fields['comprobante'] != 'FAC' && $ctacte_r->fields['comprobante'] != 'NDB' && $ctacte_r->fields['comprobante'] != 'TIQ'){
				echo "";
			} else {
				echo timesql2std($ctacte_r->fields['vto']);
			}
			
			echo "</TD>\n";
			
			echo "<TD style=\"text-align:left;\">".htmlentities($ctacte_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD>\n";
			echo "<TD style=\"text-align:right;\">";
			echo sprintf('%01.2f',$ctacte_r->fields['importe']);
			echo "</TD>\n";		
			echo "<TD style=\"text-align:right;\">";
			echo sprintf('%01.2f',$ctacte_r->fields['saldo']);
			echo "</TD>\n";		
			echo "<TD style=\"text-align:right;\">".sprintf('%01.2f',$saldo_ctacte)."</TD>\n";		
			echo "</TR>\n";
			$ctacte_r->MoveNext();
		}
		echo "</TABLE></TR>";
		echo "<TR><TD ALIGN=\"CENTER\" VALIGN=\"TOP\"><HR COLOR=\"#dedede\"></TD></TR>";
		echo "<TR><TD CLASS=\"textobrowse\">";
		$queryctactei = "Select * from e_ctasctes_import ORDER by fecha DESC";
		$ctactei_r = $db->Execute($queryctactei);
		if (!$ctactei_r->EOF){
			echo "Saldo al ".timest2dt($ctactei_r->fields['fecha'])."";
		}
		echo "</TD></TR>";
	} else {
		echo "<TR><TD>ERROR: No existen movimientos.</TD></TR>";
	}
}
?>
</TABLE>   
</BODY>
</HTML>