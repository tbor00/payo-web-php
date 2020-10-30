<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Usuarios Administrativos";
$config['tabla']="usuarios";
$config['join']="";
$config['campos'][0] = array(
    'username'=>Usuario,
    'descripcion'=>Descripción,
    'email'=>Email,
    'id_usuario'=>"",
	 'administrador'=>"",
    );
$config['include_form']="templates/listar.inc.php";
$config['id']="id_usuario";
$config['default_order']="username";
$config['default_ord'] = "";
$config['set_idioma']="no";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['pass']="yes";
$config['form_edit']="forms/usuarios.inc.php";
$config['listar_condicion']="1";
$config['em_condicion']="id_usuario=$id";
$config['eliminar_control']=array('usuarios'=>"$id=$_SESSION[uid]");
$config['modificar_control']=array('usuarios'=>"username='$username[1]' AND id_usuario!='$id'");
$config['alta_control']=array('usuarios'=>"(usuarios.username='$username[1]')" );

// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------

include("include/templates/browse_header.inc.php");

switch($op){
	case 'l':{
		$config['condicion']=$config['listar_condicion'];
		listar($config);
		break;
	}
	case 'e':{
		$config['control']=$config['eliminar_control'];
		$config['titulo']="Atenci&oacute;n";
		$config['mensaje']="Imposible eliminar el usuario.";
		$config['op']='l';
		if (controlar($config)){
			$config['campos'][0]=array($config['default_order']);
			$config['titulo']=$config['menu']." - Eliminar";
			$config['condicion']=$config['em_condicion'];
			$config['mensaje']=" Esta seguro que desea eliminar a";
			$config['op']= "ce";
			conf_eliminar($config);
		}	
		break;
	}
	case 'ce':{
		$config['condicion']=$config['em_condicion'];
		if (eliminar($config)){
			$config['condicion']=$config['listar_condicion'];
			listar($config);
		}
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
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"username"=>$username[1],
		 		"descripcion"=>$descripcion[1],
		 		"email"=>$email[1],
		 		"administrador"=>$administrad[1],
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
			if (modificar($config)){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			} else {
				Echo_Error(ERROR_DB_FAILTRANS);
			}
		}
		break;
	}
	case 'a':{
		$glo_form_tit = $config['menu'] . " - Altas";
		$config['op']="ca";	
		include("include/templates/form_header.inc.php");
		include("include/".$config['form_edit']);
		include("include/templates/form_footer.inc.php");
		break;
	}
	case 'ca':{
		// -------------------------------------------------------------
		// Datos Modificables
		// -------------------------------------------------------------
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"username"=>$username[1],
		 		"descripcion"=>$descripcion[1],
		 		"email"=>$email[1],
		 		"administrador"=>$administrad[1],
			);
			$config['campos'][$id_l]["password"]=md5($password);
			$config['campos'][$id_l]["id_usuario"]="auto_increment";

		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['alta_control'];
		$glo_form_tit = $config['menu'] . " - Altas";
		$config['op']="ca";	
		$config['include_form_error']=$config['form_edit'];
		if(controlar($config)){
			if ($id = alta($config)){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			} else {
				Echo_Error(ERROR_DB_FAILTRANS);
			}
		}
		break;	
	}
	case 'p':{
		$glo_form_tit = $config['menu'] . " - Cambio de Clave";
		$config['op']="cp";	
		include("include/templates/form_header.inc.php");
		$config['include_form']="forms/cambio-password.inc.php";
		$config['condicion']=$config['em_condicion'];
		listar_modificaciones($config);
		include("include/templates/form_footer.inc.php");
		break;
	}
	case 'cp':{
		$db_pa = connect();
		$db_pa->debug = SDEBUG;
		$config['condicion']=$config['em_condicion'];
		$pa_q = "UPDATE $config[tabla] SET password='".md5("$password")."' WHERE ".$config['condicion'];
		$pa_result = $db_pa->Execute($pa_q);
		if ($pa_result){
			dolog($pa_q);
		} else {
			dolog("ERROR: ". $pa_q);
		}
		$config['condicion']=$config['listar_condicion'];
		listar($config);
	}
}//Fin del switch
?>
