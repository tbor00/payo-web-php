<?php
//---------------------------------------------------
// DEFINICIONES VARIAS
//---------------------------------------------------
define (SDEBUG,0);																	// 1=Imprime info de debug / 0=No
define (MD5_SIZE,32);																// Largo de MD5 para contraseñas y demas

//---------------------------------------------------
// ERRORES 
//---------------------------------------------------
define (ERROR_FORM_NOT_FOUND,"Formulario NO encontrado");				// Texto del error
define (ERROR_DB_CONNECT,"Error al conectarse a la base de datos");	// Texto del error
define (ERROR_DB_FAILTRANS,"Se ha producido un fallo al intentar actualizar los datos");
define (ERROR_INC_NOTFOUND,"Acci&oacute;n no permitida.<BR>Intente nuevamente mas tarde o comuniquese<BR>con el administrador.");

define (FIRST_COLUMN_TABLE, 0);													// Si es oci8 en 1 sino 0
define (ADM_FOLDER,'admin/');


//---------------------------------------------------

//---------------------------------------------------
// PARAMETROS
//---------------------------------------------------
$site_config = new stdClass();

$site_config->web_title			= "PAYO - Administraci&oacute;n";	// Titulo del sitio
$site_config->session_name		= "ADMPAYO";								// Nombre de la sesion
$site_config->session_timeout	= 10800;										// Tiempo de expiracion de la sesion
$site_config->list_row			= 20;											// Cantidad de filas en el browse
$site_config->lang				= "es";											// Idioma por defecto
$site_config->set_idioma		= "no";											// Si usa idiomas
$site_config->flog				= 30;											// Tiempo de duracion de los logs en dias
$site_config->log_table			= "logg";										// Nombre de la tabla de Logs
$site_config->sessions_table	= "e_sessions_adm";								// Nombre de la tabla de sesiones
$site_config->encode			= "iso-8859-1";      

$site_config->decimales				= 2;
$site_config->enable_gestion        = True;
$site_config->show_stock			= True;
$site_config->o_venta				= True;

//---------------------------------------------------
// COLORES
//---------------------------------------------------
$site_config->body_bgcolor  		= "#CFCFCF";  	//	  E2DECC
$site_config->body_bg 				= "";
$site_config->title_bgcolor 		= "#0082D6"; 
$site_config->title_color  			= "white";
$site_config->border_color 			= "#000000";						 
$site_config->table_bgcolor 		= "#E6E6E6";
$site_config->table_headercolor 	= "black";
$site_config->table_bgheadercolor	= "#CAEAFF";
$site_config->row_bgcolor			= "#FFFFFF";
$site_config->row_bgcolor_over 		= "#E8F5FF";
$site_config->dialog_bgcolor		= "#FFFFFF";

$site_config->menu_bgcolor 			= "#E8F5FF";
$site_config->menu_fontcolor 		= "black";
$site_config->menu_foncolor_over 	= "white";
$site_config->menu_bgcolor_over  	= "#0082D6";		 
$site_config->menu_bordercolor  	= "#E8F5FF";
$site_config->menu_separatorcolor  	= "#E8F5FF";
$site_config->fieldrequired_color 	= "red";
$site_config->error_color 			= "red";

//---------------------------------------------------
?>