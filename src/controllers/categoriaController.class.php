<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once(__DIR__.'/../models/conexion.class.php');


class CategoriaController {


	public function sugerirCategoria($request, $response){

		$coincidencias = array();
		$categorias = array(
			'reformas baños' => array('bañera','ducha'),
			'reformas cocina' => array('cocina','encimera'),
			'calefaccion' => array('calefaccion', 'termo', 'caldera', 'calefactor','radiador' ),
			'aire acondicionado' => array('aire acondicionado'),
			'construccion casas' => array('construir','terreno','casa','terraza')
			);


		$data = $request->getParsedBody();
		$palabras = explode(' ',$data['descripcion']);

		foreach ($palabras as $palabra) {
			foreach ($categorias as $key => $value) {
				if(in_array($palabra, $value)){
					array_push($coincidencias, $key);
				}
			}
		}

		return json_encode($coincidencias);

	}

}