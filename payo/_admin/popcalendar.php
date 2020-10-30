<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');

if (ini_get('register_globals') == false ){
	extract($_GET,EXTR_OVERWRITE,"");
	extract($_POST,EXTR_OVERWRITE,"");
	$PHP_SELF = $_SERVER['PHP_SELF'];
}
if (isset($_GET["campo"])){
	$campo = $_GET["campo"];
}
$glo_title = $default->web_title. " - CALENDARIO";
include("include/templates/header.inc.php");
echo "<SCRIPT>\n";
echo "function tratarFecha(dia,mes,ano){\n";
if ( $campo != "" ){
	$retorna = "window.opener.document.forms[0].elements[\"$campo\"].value = dia+\"/\"+mes+\"/\"+ano;";
	//$retorna = "window.opener.$campo.value = dia+\"/\"+mes+\"/\"+ano;";

}
echo "\t$retorna\n";
echo "self.close();\n";
echo "}\n";
echo "</SCRIPT>\n";
//----------------------------------
function ValidarDia($day,$month,$year){
	$uday = 30;
   if (($month == 1) || ($month == 3) || ($month ==5) || ($month == 7) || ($month == 8) || ($month == 10) || ($month == 12)) {
   	$uday = 31;
	}
	if ($month == 2) {
		$uday = daysInFebruary ($year);
	}	
	if ($day > $uday){
		$day = $uday;
	}
	return $day;
}
//----------------------------------
function daysInFebruary ($year){
	return (  (($year % 4 == 0) && ( (!($year % 100 == 0)) || ($year % 400 == 0) ) ) ? 29 : 28 );
}
//----------------------------------
$anoInicial = '1900';
$anoFinal = '2100';
$fecha = getdate(time());
if (isset($_GET["dia"])){
	$dia = $_GET["dia"];
} else { 
	$dia = $fecha['mday'];
}
if (isset($_GET["mes"])){
	$mes = $_GET["mes"];
} else {
	$mes = $fecha['mon'];
}
if (isset($_GET["ano"])){
	$ano = $_GET["ano"];
} else {
	$ano = $fecha['year'];
}

$hoy = date("d");
$dia = ValidarDia($dia,$mes,$ano);
$fecha = mktime(0,0,0,$mes,$dia,$ano);
$fechaInicioMes = mktime(0,0,0,$mes,1,$ano);
$fechaInicioMes = date("w",$fechaInicioMes);
$meses = Array ('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
$diasSem = Array ('D','L','M','M','J','V','S');
$ultimoDia = date('t',$fecha);
$numMes = 0;
$posicion = Array (0,1,2,3,4,5,6);
$fecha_vnc = array();
$fecha_vap = array();
echo "<FORM NAME=\"calendar_form\"><TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\"><TR><TD VALIGN=\"TOP\" ALIGN=\"CENTER\"><TABLE BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"0\" BGCOLOR=\"#D4D0C8\">\n";		 
echo "<TR><TD VALIGN=\"TOP\" ALIGN=\"LEFT\" WIDTH=\"160\"> \n";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"0\" WIDTH=\"100%\" CLASS=\"calendarm1\" BGCOLOR=\"#FFFFFF\" HEIGHT=\"100%\">\n";
echo "<TR>\n";
for ($coln = 0; $coln < 7; $coln++){
	echo '<TD WIDTH="14%" HEIGHT="19"';
	echo " BGCOLOR=\"#808080\"";
	echo " ALIGN=\"center\">\n";
	echo "<FONT COLOR=\"#D4D0C8\">$diasSem[$coln]</FONT>";
  	echo "</TD>\n";
}
echo "</TR>\n";

for ($fila = 1; $fila < 7; $fila++){
	echo "<TR>\n";
	for ($coln = 0; $coln < 7; $coln++){
		echo '<TD WIDTH="14%" HEIGHT="19"';
		if(($numMes && $numMes < $ultimoDia) || (!$numMes && $posicion[$coln] == $fechaInicioMes)){
			if ($dia == $numMes+1){
				echo " BGCOLOR=\"#0A246A\"";
			}
			echo " ALIGN=\"center\">\n";
			echo "<A HREF=\"#\" ONCLICK=\"tratarFecha('".sprintf("%02d",++$numMes)."','".sprintf("%02d",$mes)."','".$ano."')\">";
		   if($dia == $numMes){
				echo "<FONT COLOR=\"#FFFFFF\">$numMes</FONT></A>";
			} else {
		      echo ($numMes).'</A>';
			}
		} else {
			echo " ALIGN=\"center\">\n";
		}
    	echo "</TD>\n";
  }
  echo "</TR>\n";
}
echo "</TABLE></TD></TR>";
echo "<TR><TD WIDTH=\"100%\">\n";
echo "<SELECT SIZE=\"1\" NAME=\"mes\" CLASS=\"m1\" ONCHANGE=\"document.location='?dia=$dia&mes=' + document.calendar_form.mes.value + '&ano=$ano&campo=$campo';\">\n";
for($i = 1; $i <= 12; $i++){
	echo '<OPTION ';
	if ($mes == $i){
		echo 'SELECTED ';
	}
	echo 'VALUE="'.$i.'">'.$meses[$i-1]."</OPTION>\n";
}
echo "</SELECT>&nbsp;&nbsp;&nbsp;<SELECT SIZE=\"1\" NAME=\"ano\" CLASS=\"m1\" ONCHANGE=\"document.location = '?dia=$dia&mes=$mes&ano=' + document.calendar_form.ano.value + '&campo=$campo';\">\n";
for ($i = $anoInicial; $i <= $anoFinal; $i++){
	echo '<OPTION ';
	if($ano == $i){
		echo 'SELECTED ';
	}
	echo 'VALUE="'.$i.'">'.$i."</OPTION>\n";
}
echo "</SELECT></TD>";
echo "</TR></TABLE></TD>";
echo "</TR></TABLE></FORM>";
include("include/templates/footer.inc.php");
?>