<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Mis Datos";
$config['tabla']="usuarios";
$config['join']="";
$config['campos'][0] = array(
    'username'=>"Usuario",
    'descripcion'=>"Descripci&oacute;n",
    'email'=>"Email",
    'id_usuario'=>"",
	 'administrador'=>"",
	 'password'=>"",
    );
$config['include_form']="templates/listar.inc.php";
$config['id']="id_usuario";
$config['default_order']="username";
$config['default_ord'] = "";
$config['set_idioma']="no";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="no";
$config['delete']="no";
$config['pass']="no";
$config['form_edit']="forms/misdatos.inc.php";
$config['listar_condicion']="id_usuario=$id";
$config['em_condicion']="id_usuario=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('usuarios'=>"username='$username[1]' AND id_usuario!='$id'");
$config['alta_control']=array();

// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------

if(!isset($op) || $op==""){
	$op="m";
}

include("include/templates/browse_header.inc.php");

switch($op){
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
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"descripcion"=>$descripcion[1],
		 		"email"=>$email[1],
			);
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$glo_form_tit = $config['menu'] . " - Modificaciones";
		$config['op']="cm";
		$config['include_form_error']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
		$config['control']=$config['modificar_control'];
		if(controlar($config)){
			if (!$ok = modificar($config, $db)){
				Echo_Error(ERROR_DB_FAILTRANS);
			}
		}
		break;
	}
	case 'p':{
		$glo_form_tit = $config['menu'] . " - Cambio de Clave";
		$config['op']="cp";	
		include("include/templates/form_header.inc.php");
		$config['include_form']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
		listar_modificaciones($config);
		include("include/templates/form_footer.inc.php");
		break;
	}
	case 'cp':{
		$db_pa = connect();
		$db_pa->debug = SDEBUG;
		$config['condicion']=$config['em_condicion'];
		$pa_qr="SELECT username from $config[tabla] WHERE $config[condicion] AND password='".md5($pass0)."'";
		$pa_rresult = $db_pa->Execute($pa_qr);
		if ($pa_rresult && !$pa_rresult->EOF){
			$pa_q = "UPDATE $config[tabla] SET password='".md5("$password")."' WHERE ".$config['condicion'];
			$pa_result = $db_pa->Execute($pa_q);
			if ($pa_result){
				dolog($pa_q);
			} else {
				dolog("ERROR: ". $pa_q);
			}
		} else {
			$glo_form_tit = $config['menu'] . " - Cambio de Clave";
			$config['op']="cp";	
			include("include/templates/form_header.inc.php");
			$config['include_form']=$config['form_edit'];
			$config['condicion']=$config['em_condicion'];
			listar_modificaciones($config);
			include("include/templates/form_footer.inc.php");
		}
		break;
	}
}//Fin del switch
?>
