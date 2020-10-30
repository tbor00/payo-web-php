<?php
$db_m = connect();
$db_m->debug = SDEBUG;

echo "<TABLE WIDTH=\"100%\" BGCOLOR=\"$default->border_color\" BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\">\n";
echo "<TR><TD>\n";
echo "<TABLE BGCOLOR=\"$default->row_bgcolor\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
echo "<TR><TD>\n";
echo "<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"1\">\n";
echo "<TR><TD WIDTH=\"\" VALIGN=\"MIDDLE\" ALIGN=\"LEFT\">\n";

$query_h = "SELECT id_menu,titulo,activo from e_menues WHERE tipo=0 ORDER BY posicion";
$result_h = $db_m->Execute($query_h);
if ($result_h && !$result_h->EOF){
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\" WIDTH=\"100%\">\n";
	echo "<TR VALIGN=\"MIDDLE\" BGCOLOR=\"$default->row_bgcolor\">\n";
	echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" WIDTH=\"1%\">";
	echo "<A HREF=\"$PHP_SELF?accion={$config[accion]}&op=m&id={$result_h->fields[id_menu]}\"><IMG SRC=\"./img/edit.gif\" ALT=\"Modificar\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></A>";
	echo "</TD>\n";
	echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\" WIDTH=\"99%\"><SPAN STYLE=\"font-weight:bold;\">".$result_h->fields[titulo]."</SPAN></TD>\n";
	echo "</TR>\n";
	echo "</TABLE>";
}

$array_menues=array($config[menu_tipo]);
for ($a_me=0;$a_me<sizeof($array_menues);$a_me++){
	$t_menues = $array_menues[$a_me];
	$query_m = "SELECT id_menu,titulo,activo from e_menues WHERE tipo=$t_menues AND $config[condicion] ORDER BY posicion";
	$result_m = $db_m->Execute($query_m);
	if ($result_m && !$result_m->EOF){
		while(!$result_m->EOF){
			$query_sm = "Select id_menu,titulo,activo from e_menues where tipo=$t_menues+1 and menu_id={$result_m->fields[id_menu]} AND $config[condicion] ORDER BY posicion";
			$result_sm = $db_m->Execute($query_sm);
			if ($result_sm && !$result_sm->EOF){
				$noborrar = true;
			} else {
				$noborrar = false;
			}
			if ($result_m->fields[activo]){
				$estilo_m="font-weight:bold; color:#000000";
			} else {
				$estilo_m="font-weight:bold; color:#C0C0C0";
			}
			echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\" WIDTH=\"100%\">\n";
			echo "<TR VALIGN=\"MIDDLE\" BGCOLOR=\"$default->row_bgcolor\" >\n";
			echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" WIDTH=\"1%\"><IMG SRC=\"./img/blank.gif\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></TD>";
			
			echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" WIDTH=\"5%\">";
			if ($noborrar == false){
				echo "<A HREF=\"$PHP_SELF?accion={$config[accion]}&op=e&id={$result_m->fields[id_menu]}\"><IMG SRC=\"./img/delete.gif\" ALT=\"Eliminar\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></A>";
			} else {
				echo "<IMG SRC=\"./img/blank.gif\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\">";
			}
			echo "<A HREF=\"$PHP_SELF?accion={$config[accion]}&op=m&id={$result_m->fields[id_menu]}\"><IMG SRC=\"./img/edit.gif\" ALT=\"Modificar\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></A>";
			echo "</TD>\n";
			echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\" WIDTH=\"95%\"><SPAN STYLE=\"$estilo_m\">".$result_m->fields[titulo]."</SPAN></TD>\n";
			echo "</TR>\n";
			if ($result_sm && !$result_sm->EOF){
				if ($result_sm->fields[activo]){
					$estilo_sm="font-weight:bold; color:#000000";
				} else {
					$estilo_sm="font-weight:bold; color:#C0C0C0";
				}
				echo "<TR>";
				echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" WIDTH=\"1%\"><IMG SRC=\"./img/blank.gif\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></TD>";
				echo "<TD WIDTH=\"\" VALIGN=\"MIDDLE\" ALIGN=\"LEFT\"></TD>\n";
				echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\" WIDTH=\"99%\">\n";
				while (!$result_sm->EOF){
					echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\" WIDTH=\"100%\">\n";
					echo "<TR VALIGN=\"MIDDLE\" BGCOLOR=\"$default->row_bgcolor\">\n";
					echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" WIDTH=\"5%\">";
					echo "<A HREF=\"$PHP_SELF?accion={$config[accion]}&op=e&id={$result_sm->fields[id_menu]}\"><IMG SRC=\"./img/delete.gif\" ALT=\"Eliminar\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></A>";
					echo "<A HREF=\"$PHP_SELF?accion={$config[accion]}&op=m&id={$result_sm->fields[id_menu]}\"><IMG SRC=\"./img/edit.gif\" ALT=\"Modificar\" BORDER=\"0\" WIDTH=\"20\" HEIGHT=\"20\"></A>";
					echo "</TD>\n";
					echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\" WIDTH=\"95%\"><SPAN STYLE=\"$estilo_sm\">".$result_sm->fields[titulo]."</SPAN></TD>\n";
					echo "</TR>\n";
					echo "</TABLE>\n";
					$result_sm->Movenext();
				}
			   echo "</TD></TR>\n";
			}
			echo "</TABLE>\n";
			$result_m->MoveNext();
		}
	}
}
echo "</TD></TR>\n";
echo "</TABLE>\n";
echo "</TD></TR>\n";
echo "</TABLE>\n";
echo "</TD></TR>\n";
echo "</TABLE>\n";
?>

