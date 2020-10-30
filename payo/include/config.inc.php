<?php
//---------------------------------------------
// DEFINICIONES VARIAS
//---------------------------------------------
define (SDEBUG,0);																	// 1=Imprime info de debug / 0=No
define (MD5_SIZE,32);																// Largo de MD5 para contraseñas y demas

//---------------------------------------------
// ERRORES 
//---------------------------------------------
define (ERROR_FORM_NOT_FOUND,"Formulario NO encontrado");				            // Texto del error
define (ERROR_DB_CONNECT,"Error al conectarse a la base de datos");	                // Texto del error
define (ERROR_DB_WRITE,"Error al intentar guardar datos en la base de datos");

//---------------------------------------------------------------------------

//---------------------------------------------
//  VARIABLES DE CABECERA DEL SITIO
//---------------------------------------------
$site_config = new stdClass();

$site_config->web_title				= "PAYO - Materiales el&eacute;ctricos";					                        // Titulo del sitio (defecto)
$site_config->keywords				= "materiales electricos, electricidad, gremio, meteriales, ";		// Keywords (defecto)
$site_config->copyright				= "Copyright 2020 - PAYO";									// Copyright
$site_config->site_description		= "PAYO";							                        // Descripción
$site_config->author				= "Argxentia (http://www.argxentia.com.ar)";						// Autor del sitio

//---------------------------------------------
// VARIAS
//---------------------------------------------
$site_config->mailfrom 				= "info@payo.com.ar";				// Remitente de Mails (defecto)
$site_config->mailfromname			= "PAYO";							// Remitente de Mails (defecto)
$site_config->mailto   				= "info@payo.com.ar";				// Destinatario de Mails (defecto)
$site_config->mailer   				= "mail";									// metodo para enviar mails
$site_config->session_name			= "SESSPAYO";							// Nombre de la SESSION
$site_config->lang     				= "es";										// Idioma (defecto)
$site_config->words					= 40;
$site_config->paginarpor			= 10;
$site_config->error_color			= red;
$site_config->encode				= "ISO-8859-1";
$site_config->decimales				= 2;
$site_config->enable_gestion        = True;
$site_config->show_stock			= True;
$site_config->products_dir			= "products";
$site_config->o_venta				= True;
$site_config->mp_app				= "";
$site_config->mp_id                 = "974537609345873";
$site_config->mp_secret             = "mlR9ooiWOiQT7fyTISoVmj5V0j0pkywI";
$site_config->mp_rate               = 0.06;

$site_config->captcha_site_key		= "6LcepVIUAAAAAH6STyxv00u3QFX0AJk-IQYm_Ryx";
$site_config->captcha_secret		= "6LcepVIUAAAAAKOGaWqD49KvuJhcq_Wqp4qe5npq";


//$site_config->mp_id                 = "1515101516162308";
//$site_config->mp_secret             = "Ti0v4IisBwNki40lpW7CNL9nosVqoWeL";


//---------------------------------------------------------------------------
?>