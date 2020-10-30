<?php
//-----------------------------------------------
//
//
//
//-----------------------------------------------

include('../include/config_admin.inc.php');
require_once("../../include/config_database.inc.php");
require_once("../../include/adodb/adodb.inc.php");
//---------------------------------------------------------------------------
class CDefaults {

	//---------------------------------------------------------------------------
	function CDefaults () {
		global $site_config;
		$this->dbtype      			=  $site_config->DBTYPE;
		$this->dbhost      			=  $site_config->DBHOST;
		$this->dbport      			=  $site_config->DBPORT;
		$this->database    			=  $site_config->DATABASE;
		$this->dbuser      			=  $site_config->DBUSER;
		$this->dbuser_pass 			=  $site_config->DBUSER_PASS;

		$this->web_title       		=  $site_config->web_title;
      $this->session_name    		=  $site_config->session_name;
      $this->session_timeout 		=  $site_config->session_timeout;
      $this->list_row        		=  $site_config->list_row;
      $this->lang            		=  $site_config->lang;
      $this->set_idioma      		=  $site_config->set_idioma;
      $this->flog            		=  $site_config->flog;
		$this->log_table           =  $site_config->log_table;
		$this->sessions_table      =  $site_config->sessions_table;

      $this->body_bgcolor        =  $site_config->body_bgcolor;
      $this->body_bg             =  $site_config->body_bg;
      $this->title_bgcolor       =  $site_config->title_bgcolor;
      $this->title_color         =  $site_config->title_color;
      $this->border_color        =  $site_config->border_color;
      $this->table_bgcolor       =  $site_config->table_bgcolor;
      $this->table_headercolor   =  $site_config->table_headercolor;
      $this->table_bgheadercolor =  $site_config->table_bgheadercolor;
      $this->row_bgcolor         =  $site_config->row_bgcolor;
      $this->row_bgcolor_over    =  $site_config->row_bgcolor_over;
      $this->dialog_bgcolor      =  $site_config->dialog_bgcolor;
												                          
      $this->menu_bgcolor        =  $site_config->menu_bgcolor;
      $this->menu_fontcolor      =  $site_config->menu_fontcolor;
      $this->menu_foncolor_over  =  $site_config->menu_foncolor_over;
      $this->menu_bgcolor_over   =  $site_config->menu_bgcolor_over;
      $this->menu_bordercolor    =  $site_config->menu_bordercolor;
      $this->menu_separatorcolor =  $site_config->menu_separatorcolor;
      $this->fieldrequired_color =  $site_config->fieldrequired_color;
      $this->error_color         =  $site_config->error_color;

	 	if ($this->dbtype == 'oci8'){
			$this->first_column_table = 1;
		} else {
			$this->first_column_table = 0;
		}

	}
}
$default = new CDefaults();
//---------------------------------------------------------------------------
$ADODB_SESSION_DRIVER= $default->dbtype;
$ADODB_SESSION_CONNECT= $default->dbhost;
$ADODB_SESSION_USER = $default->dbuser;
$ADODB_SESSION_PWD = $default->dbuser_pass;
$ADODB_SESSION_DB = $default->database;
$ADODB_SESSION_TBL = $default->sessions_table;
include("../../include/adodb/session/adodb-session2.php");


if (ini_get('register_globals') == false ){
	//extract($HTTP_GET_VARS,EXTR_OVERWRITE,"");
	//extract($HTTP_POST_VARS,EXTR_OVERWRITE,"");
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
}

$url_base = "../../";

$img_mini_dir = $url_base."imagenes/miniaturas";
$img_mini_quality = "70";
$img_mini_res = "200x200";
//------------------------------------------------
$img_dir = $url_base."imagenes";
$img_max_size = 600*1024;
$img_overwrite = false;
//------------------------------------------------
$mm_dir = $url_base."multimedia";
$mm_max_size = 1000*1024;
$mm_overwrite = false;
//------------------------------------------------
$file_dir = $url_base."archivos";
$file_max_size = 900*1024;
$file_overwrite = false;
//------------------------------------------------
if ($type!=""){
	if ($type=="image"){
		$accepted_files = array('images');
		$overwrite = $img_overwrite;
		$dirname = $img_dir;
		$max_size = $img_max_size;
	}
	if ($type=="media"){
		$accepted_files = array('adobe_flash','windows_media','real_media','mp3_media');
		$overwrite = $mm_overwrite;
		$dirname = $mm_dir;
		$max_size = $mm_max_size;
	}
	if ($type=="file"){
		$accepted_files = array('images','html','ms_excel','ms-word','ms_powerpoint','adobe_pdf','text');
		$overwrite = $file_overwrite;
		$dirname = $file_dir;
		$max_size = $file_max_size;
	}
}

?>
