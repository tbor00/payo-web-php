<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Pedidos";
$config['tabla']="e_cotiza";
$config['join']="left join e_webusers on e_webusers.user_id=e_cotiza.user_id";
$config['campos'][0] = array(
	'id_cotiza'=>"N&uacute;mero",
	'fecha'=>"Fecha",
	'e_cotiza.user_id AS usuario_id'=>"",
	'razonsocial'=>"Raz&oacute;n Social",
		'state'=>array(
			nombre=>"Enviado",
			campo=>"estado"),
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_cotiza";
$config['default_order']="id_cotiza";
$config['default_ord'] = "desc";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="no";
$config['delete']="no";
$config['form_edit']="forms/pedidos.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_cotiza='$id'";
$config['eliminar_control']=array();
$config['modificar_control']=array();
$config['alta_control']=array();


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
		reenviar_cotiza($id);
		listar($config);
		break;
	}
}//Fin del switch
?>
