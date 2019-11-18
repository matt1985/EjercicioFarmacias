<?php 

require 'Farmacias.php';

$resultados = new FarmaciasTurno();


$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

//busca valores en url segun posicion  
$metodo = explode('/', $url)[6]; 
$comuna_id  = explode('/', $url)[8];
$local = explode('/', $url)[10];


if($metodo == 'getRegionComuna')
{
	$resultados->getRegionComuna(7);
}

if($metodo == 'getLocal')
{
	$resultados->getLocal(7, $comuna_id);
}

if($metodo == 'getFarmaciasTurno')
{
 	$resultados->getFarmaciasTurno(7, $comuna_id, $local);
}



?>