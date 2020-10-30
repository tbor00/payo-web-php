<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['tipo'] = "0";
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="P&aacute;gina de Inicio";
$config['tabla']="e_menues";
$config['join']="";
$config['campos'][0] = array(
   'id_menu'=>"",
  	'titulo'=>"T&iacute;tulo",
   'imgtitulo'=>"",
	'tipo'=>"",
	'ejecutar'=>"",
	'texto_up'=>"",
	'inclusion'=>"",
	'texto_down'=>"",
	'posicion'=>"",
	'binario'=>array(
		nombre=>Activo,
		campo=>activo
	),
	'lenguaje_id'=>"",
	'hidetitle'=>"",
	'bgcolor'=>"",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_menu";
$config['default_order']="titulo";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="";
$config['delete']="";
$config['form_edit']="forms/menues.inc.php";
$config['listar_condicion']="tipo = $config[tipo]";
$config['em_condicion']="id_menu=$id";
$config['eliminar_control']="";
$config['modificar_control']=array('e_menues'=>"titulo='$titulo[1]' AND id_menu!='$id' AND tipo=$config[tipo]");
$config['alta_control']="";

// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------

include('include/templates/browse_header.inc.php');

switch($op){
	case 'l':{
		$config['condicion']=$config['listar_condicion'];
		listar($config);
		break;
	}
	case 'm':{
		$glo_form_tit = $config['menu'] . " - Modificaciones";
		$config['op']="cm";
		include('include/templates/form_header.inc.php');
		$config['include_form']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
		listar_modificaciones($config);
		include('include/templates/form_footer.inc.php');
		break;
	}
	case 'cm':{
		// -------------------------------------------------------------
		// Datos Modificables
		// -------------------------------------------------------------
		foreach ($default->lenguajes($config['set_idioma']) as $id_l){
			if ($activo[$id_l]==""){
				$activo[$id_l]="0";
			}
			if ($hidetitle[$id_l]==""){
				$hidetitle[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"imgtitulo"=>$imgtitulo[$id_l],
				"ejecutar"=>$ejecutar[$id_l],
				"texto_up"=>StripUri($texto_up[$id_l]),
				"texto_down"=>StripUri($texto_down[$id_l]),
				"activo"=>$activo[$id_l],
				"hidetitle"=>$hidetitle[$id_l],
				"bgcolor"=>$bgcolor[$id_l],
			);
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['modificar_control'];
	 	$glo_form_tit = $config['menu'] . " - Modificaciones";
    	$config['op']="cm";
    	$config['include_form_error']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
    	if(controlar($config)){
			$dbm = connect();
			$dbm->debug = SDEBUG;
			$dbm->StartTrans();
      	if ($ok = modificar($config, $dbm)){
				$query="DELETE FROM e_menu_adic WHERE menu_id=$id AND lenguaje_id=1";
				$dbm->Execute($query);
				$adics = split("~",$adics_lst);
				$i=0;
				if (is_array($adics)){
					foreach($adics as $k){
						if ($k!="") {
							$i++;
							if (!$ok=$dbm->Execute("INSERT INTO e_menu_adic (menu_id,adicional_id,lenguaje_id,posicion_adic) VALUES ('$id','$k',1,'$i')")){
								$dbm->FailTrans();
								break;
							}
						}
					}
				}
				$query="DELETE FROM e_menu_adic WHERE menu_id=$id AND lenguaje_id=2";
				$dbm->Execute($query);
				$adics2= split("~",$adics_2_lst);
				$i=0;
				if (is_array($adics2)){
					foreach($adics2 as $k){
						if ($k!="") {
							$i++;
							if (!$ok=$dbm->Execute("INSERT INTO menu_adic (menu_id,adicional_id,lenguaje_id,posicion_adic) VALUES ('$id','$k',2,'$i')")){
								$dbm->FailTrans();
								break;
							}
						}
					}
				}
				$query="DELETE FROM e_menu_form WHERE menu_id=$id AND lenguaje_id=1";
				$dbm->Execute($query);
				$forms = split("~",$forms_lst);
				$i=0;
				if (is_array($forms)){
					foreach($forms as $k){
						if ($k!="") {
							$i++;
							if (!$ok=$dbm->Execute("INSERT INTO e_menu_form (menu_id,form_id,lenguaje_id,posicion_form) VALUES ('$id','$k',1,'$i')")){
								$dbm->FailTrans();
								break;
							}
						}
					}
				}
				$query="DELETE FROM e_menu_form WHERE menu_id=$id AND lenguaje_id=2";
				$dbm->Execute($query);
				$forms2= split("~",$forms_2_lst);
				$i=0;
				if (is_array($forms2)){
					foreach($forms2 as $k){
						if ($k!="") {
							$i++;
							if (!$ok=$dbm->Execute("INSERT INTO e_menu_form (menu_id,form_id,lenguaje_id,posicion_form) VALUES ('$id','$k',2,'$i')")){
								$dbm->FailTrans();
								break;
							}
						}
					}
				}
			} else {
				$dbm->FailTrans();
			}
			$ok = $dbm->CompleteTrans();
			if ($ok){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			} else {
				Echo_Error(ERROR_DB_FAILTRANS);
			}
		}
		break;
	}
}//Fin del switch
?>

