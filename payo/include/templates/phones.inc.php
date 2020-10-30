<?php
echo "<div class=\"row\">";
echo "<div class=\"col-sm-12\">";
	$db = connect();
	$query = "select * from e_telefonos order by id_seccion,nombre";
	$result = $db->Execute($query);
	if ($result && !$result->EOF){
		$query = "select * from e_telefonos where id_seccion=1 order by id_seccion,nombre";
		$vresult = $db->Execute($query);
		if ($vresult && !$vresult->EOF){
			echo "<ul>";
			echo "<li style=\"font-weight: bold; list-style: none;\">Ventas</li>\n";
			while(!$vresult->EOF){
				echo "<li>".$vresult->fields['nombre']."&nbsp;(".$vresult->fields['telefono'].")"."<a title=\"Llamar\" href=\"tel:".$vresult->fields['telefono']."\"><i class=\"fa fa-phone\" style=\"color:blue;padding-left:15px;font-size:20px\"></i></a>";
				if ($vresult->fields['wapp']==1){
						echo "<a title=\"Whatsapp\" href=\"https://api.whatsapp.com/send?phone=549".$vresult->fields['telefono']."\" target=\"_new\"><i class=\"fa fa-whatsapp\" style=\"color:green;padding-left:15px;font-size:20px\"></i></a>";
				}
				echo "</li>\n";
				$vresult->MoveNext();
			}
			echo "</ul>";
		}	
		$query = "select * from e_telefonos where id_seccion=2 order by id_seccion,nombre";
		$vresult = $db->Execute($query);
		if ($vresult && !$vresult->EOF){
			echo "<ul>";
			echo "<li style=\"font-weight: bold; list-style: none;\">Administraci&oacute;n</li>\n";
			while(!$vresult->EOF){
				echo "<li>".$vresult->fields['nombre']." PUTOOOOO"."&nbsp;(".$vresult->fields['telefono'].")"."<a title=\"Llamar\" href=\"tel:".$vresult->fields['telefono']."\"><i class=\"fa fa-phone\" style=\"color:blue;padding-left:15px;font-size:20px\"></i></a>";
				if ($vresult->fields['wapp']==1){
						echo "<a title=\"Whatsapp\" href=\"https://api.whatsapp.com/send?phone=549".$vresult->fields['telefono']."\" target=\"_new\"><i class=\"fa fa-whatsapp\" style=\"color:green;padding-left:15px;font-size:20px\"></i></a>";
				}
				echo "</li>\n";
				$vresult->MoveNext();
			}
			echo "</ul>";
		}
		echo "<br>";
	}	



echo "</div></div>";
?>