<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Usuarios";
$config['tabla']="e_webusers";
$config['join']="left JOIN e_vendedores on vendedor_id=id_vendedor";
$config['campos'][0] = array(
		'e_webusers.username'=>"Usuario",
		'nombres'=>"Nombre",
		'apellidos'=>"Apellido",
		'razonsocial'=>"Empresa",
		'eb_cod'=>"Cod EBASE",
		'nivel'=>"",
		'e_webusers.email'=>"",
		'user_id'=>"",
		'direccion'=>"",
		'cp'=>"",
		'ciudad'=>"",
		'provincia_id'=>"",
		'telefonos'=>"",
		'web'=>"",
		'cuit'=>"",
		'iva'=>"",
		'rubro_id'=>"",
		'vendedor_id'=>"",
		'vendedor'=>"Vendedor",
		'coef'=>"",
		'pago'=>"",
		'binario'=>array(
			nombre=>"Activo",
			campo=>"activo_u"),
	);
$config['include_form']="templates/listar.inc.php";
$config['id']="user_id";
$config['default_order']="username";
$config['default_ord'] = "";
$config['set_idioma']="no";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['pass']="yes";
$config['form_edit']="forms/webusers.inc.php";
$config['listar_condicion']="1";
$config['em_condicion']="user_id=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('e_webusers'=>"username='$username[1]' AND user_id!='$id'");
$config['alta_control']=array('e_webusers'=>"(e_webusers.username='$username[1]')" );

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
		$dbm = connect();
		$dbm->debug = SDEBUG;
		$dbm->StartTrans();
		if ($ok = eliminar($config, $dbm)){
			$dbm->Execute("DELETE FROM e_user_gestiones WHERE user_id=$id");
		} else {
			$dbm->FailTrans();
		}
		$ok = $dbm->CompleteTrans();
		if ($ok){
			$config['condicion']=$config['listar_condicion'];
			listar_menues($config);
		} else {
			Echo_Error(ERROR_DB_FAILTRANS);
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
		if ($activo_u[1]==""){$activo_u[1]=0;}
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"username"=>$username[1],
		 		"nombres"=>$nombres[1],
		 		"apellidos"=>$apellidos[1],
		 		"email"=>$email[1],
		 		"nivel"=>$nivel[1],
		 		"activo_u"=>$activo_u[1],
		 		"razonsocial"=>$razonsocial[1],
				"direccion"=>$direccion[1],
				"cp"=>$cp[1],
				"ciudad"=>$ciudad[1],
				"provincia_id"=>$provincia_id[1],
				"telefonos"=>$telefonos[1],
				"web"=>$web[1],
				"rubro_id"=>$rubro_id[1],
				"eb_cod"=>$eb_cod[1],
				"cuit"=>$cuit[1],
				"iva"=>$iva[1],
				"coef"=>$coef[1],
				"pago"=>$pago[1],
				"vendedor_id"=>$vendedor_id[1],
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
			$dbm = connect();
			$dbm->debug = SDEBUG;
			$dbm->StartTrans();
			if ($ok = modificar($config, $dbm)){
				$query="DELETE FROM e_user_gestiones WHERE user_id=$id";
				$dbm->Execute($query);
				$gess = split("~",$gess_lst);
				if (is_array($gess)){
					foreach($gess as $k){
						if ($k!="") {
							if (!$ok=$dbm->Execute("INSERT INTO e_user_gestiones (user_id,gestion_id) VALUES ('$id','$k')")){
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
				if ($notificar[1]){
					notificar_activacion($id);
				}
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
		if ($activo_u[1]==""){$activo_u[1]=0;}

		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"username"=>$username[1],
		 		"nombres"=>$nombres[1],
		 		"apellidos"=>$apellidos[1],
		 		"email"=>$email[1],
		 		"nivel"=>$nivel[1],
		 		//"socio"=>$socio[1],
		 		"activo_u"=>$activo_u[1],
		 		"razonsocial"=>$razonsocial[1],
				"direccion"=>$direccion[1],
				"cp"=>$cp[1],
				"ciudad"=>$ciudad[1],
				"provincia_id"=>$provincia_id[1],
				"telefonos"=>$telefonos[1],
				"web"=>$web[1],
				"rubro_id"=>$rubro_id[1],
				"eb_cod"=>$eb_cod[1],
				"cuit"=>$cuit[1],
				"iva"=>$iva[1],
				"coef"=>$coef[1],
				"vendedor_id"=>$vendedor_id[1],
				"pago"=>$pago[1],
			);
			$config['campos'][$id_l]["password"]=md5($password);
			$config['campos'][$id_l]["user_id"]="auto_increment";

		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['alta_control'];
		$glo_form_tit = $config['menu'] . " - Altas";
		$config['op']="ca";	
		$config['include_form_error']=$config['form_edit'];
		if(controlar($config)){
			$dbm = connect();
			$dbm->debug = SDEBUG;
			$dbm->StartTrans();
			if ($id = alta($config, $dbm)){
				$gess = split("~",$gess_lst);
				$i=0;
				if (is_array($gess)){
					foreach($gess as $k){
						if ($k!="") {
							if (!$ok=$dbm->Execute("INSERT INTO e_user_gestiones (user_id,gestion_id) VALUES ('$id','$k')")){
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
