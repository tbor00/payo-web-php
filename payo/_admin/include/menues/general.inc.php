<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="General";
$config['tabla']="e_parametros";
$config['join']="";
$config['campos'][0] = array(
	'email'=>"email",
	'email_cotiza'=>'',
	'email_cobro'=>'',
	'pie_1'=>"",
	'pie_2'=>"",
	'pie_3'=>"",
	'mantenimiento'=>"",
	'data_fiscal'=>"",
	'id_param'=>"",
	'lenguaje_id'=>"",
);
if ($id=='' || !isset($id)){
	$id=1;
}
$config['id']="id_param";
$config['include_form']="templates/listar.inc.php";
$config['default_order']="email";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="";
$config['delete']="";
$config['form_edit']="forms/general.inc.php";
$config['em_condicion']="id_param=$id";
$config['listar_condicion']="";
$config['eliminar_control']=array();
$config['modificar_control']=array();
$config['alta_control']=array();


// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------
include('include/templates/browse_header.inc.php');

if ($op=='l' || !isset($op) || $op==''){
	$op='m';
}

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
		foreach ($default->lenguajes($config['set_idioma']) as $id_l){
			if ($mantenimiento[$id_l]==''){
				$mantenimiento[$id_l]=0;
			}
			$config['campos'][$id_l]=array(
				"email"=>$email[1],
				"email_cotiza"=>$email_cotiza[1],
				"email_cobro"=>$email_cobro[1],
				"pie_1"=>$pie_1[$id_l],
				"pie_2"=>$pie_2[$id_l],
				"pie_3"=>$pie_3[$id_l],
				"data_fiscal"=>$data_fiscal[$id_l],
				"mantenimiento"=>$mantenimiento[$id_l],
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
			if (modificar($config)){
				$config['condicion']=$config['listar_condicion'];
				//listar($config);
			}
		}
		break;
	}
}//Fin del switch
?>

