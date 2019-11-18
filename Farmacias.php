<?php  

class FarmaciasTurno
{

	private  $UrlComunasRegion = 'https://midastest.minsal.cl/farmacias/maps/index.php/utilidades/maps_obtener_comunas_por_regiones';

	private $UrlFarmaciaTurno = 'https://farmanet.minsal.cl/maps/index.php/ws/getLocalesRegion';

	//devulve todas las comunas de la region metropolitana 
	public function getRegionComuna($reg_id)
	{
		$data  = $this->LlamarApi($this->UrlComunasRegion, 'POST', $reg_id); 

		echo $data;
	}

	//devuelve el local segun la comuna
	public function getLocal($reg_id, $comuna_id)
	{
		if(isset($reg_id) && isset($comuna_id))
		{
			$data  = $this->LlamarApi($this->UrlFarmaciaTurno."?id_region=".$reg_id,'GET', null); 

			//array de objeto
			$dataArray = json_decode($data);

			$arrayResultados = array();

			//recorro arreglo
			foreach ($dataArray as $value) {
				//filtra arreglo por comuna
				if($value->fk_comuna == $comuna_id) 
				{
					//creo arreglo solo con valores solicitados
					$nombre_local = $value->local_nombre;
					$arrayResultados[] = array(
						"local_id" => $value->local_nombre,
						"nombre_local" => $nombre_local
					);
				}
			}

			//$this->debug(array_unique($arrayResultados, SORT_REGULAR));
			
			//elimino duplicados del arreglo 
			$arrayFinal = array_unique($arrayResultados, SORT_REGULAR);

			//header para consumir desde cualquier lugar 
			header("Access-Control-Allow-Origin: *");
			header('Content-type:application/json;charset=utf-8');
			//retorna json con los valores solicitados

			//status  
			http_response_code(200);
			echo json_encode($arrayFinal);
		}else{
			http_response_code(404);
		}

	}

	public function getFarmaciasTurno($reg_id, $comuna_id, $nombre_local)
	{	
		//verifica si las variables estan definidas 
		if(isset($reg_id) && isset($comuna_id) && isset($nombre_local))
		{
			$data  = $this->LlamarApi($this->UrlFarmaciaTurno."?id_region=".$reg_id,'GET', null); 

			//array de objeto
			$dataArray = json_decode($data);

			//$this->debug($dataArray);

			$arrayResultados = array();

			//recorro arreglo
			foreach ($dataArray as $value) {
				//filtra arreglo por comuna
				if($value->fk_comuna == $comuna_id && $value->local_nombre == $nombre_local) 
				{
					//creo arreglo solo con valores solicitados
					$arrayResultados[] = array(
						"nombre_local" => $value->local_nombre,
						"direccion" => $value->local_direccion,
						"telefono" => $value->local_telefono,
						"latitud" => $value->local_lat,
						"longitud" => $value->local_lng
					);
				}
			}

			//header para consumir desde cualquier lugar 
			header("Access-Control-Allow-Origin: *");
			header('Content-type:application/json;charset=utf-8');
			//retorna json con los valores solicitados

			//status  
			http_response_code(200);
			echo json_encode($arrayResultados);
		}else{
			http_response_code(404);
		}
		
	}

	public static function LlamarApi($url, $metodo, $reg_id) 
	{
		//crea un nuevo recurso curl para hacer la peticion post 
		$ch = curl_init($url);

		if($metodo == 'POST')
		{
			//envia parametro POST al recurso
			curl_setopt($ch, CURLOPT_POSTFIELDS, "reg_id=".$reg_id);
		}

		//creo un array para las cabeceras 
		$headers = array(
				   		'Body' => 'multipart/form-data',
						'Content-Type' => 'application/x-www-form-urlencoded');

		//seteo headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		//devuelve respuesta
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//ejecuta request
		$result = curl_exec($ch);

		$data  = $result;

		return $data;

	}

	//metodo para agrupar arreglo 
	public static function group_by($key, $data) {
	    $result = array();

	    foreach($array as $val) {
	        if(array_key_exists($key, $val)){
	            $result[$val[$key]][] = $val;
	        }else{
	            $result[""][] = $val;
	        }
	    }

	    return $result;
	}

	//este metodo lo use para hacer debug
	public static function debug($valor)
	{
		echo "<pre>";
		print_r($valor);
		echo "</pre>";
		//exit;
	}

}


