<?php

$config[accion] = $accion;

if(!isset($op) || $op==""){
	$op="l";
}

if(isset($ord)){
	$config['ord']=$ord;
}else{
	if (isset($config['default_ord'])){ 
		$config['ord']=$config['default_ord'];
	} else {
		$config['ord']="ASC";
	}
}


if(isset($orden)){
	$config['orden']=$orden;
}else{
	$config['orden']=$config['default_order'];
}

if($config['set_idioma']=="yes"){
	if(isset($idioma) && $idioma != ""){
		$config['idioma']=$idioma;
	}else{
		$config['idioma']="1";
	}
} else {
	$config['idioma']="1";
}


if(strlen($busca)>0 && strlen($buscarx)>0){
	$config['buscar']=$busca;
	$config['buscarx']=$buscarx;
}

if (isset($gotopag)){
	$pag = $gotopag;
}

if ($pag=='' || $pag==0){
 	$pag = 1;
}
$config['pag'] = $pag;

$config['cancel_option']="op=l&pag=$config[pag]&orden=$config[orden]&ord=$config[ord]&idioma=$idioma&busca=$config[buscar]&buscarx=$config[buscarx]&accion=$accion&parent=$config[parent]";

$config['submit_option']=array(
	"id"=>$id,
	"pag"=>$config['pag'],
	"orden"=>$config['orden'],
	"ord"=>$config['ord'],
	"idioma"=>$config['idioma'],
	"busca"=>$config['buscar'],
	"buscarx"=>$config['buscarx'],
	"accion"=>$config['accion'],
);
if (!isset($config['listar_condicion']) || $config['listar_condicion']==""){
	if (preg_match("/oci8/i",$default->dbtype)){
		$config['listar_condicion']="1=1";			
	} elseif (preg_match("/postgres/i",$default->dbtype)){
		$config['listar_condicion']="true";
	} else {
		$config['listar_condicion']="1";
	}
}
if($config['set_idioma']=="yes"){
	$config['listar_condicion'].=" AND {$config[tabla]}.lenguaje_id=$config[idioma]";
}
?>