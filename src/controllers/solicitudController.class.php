<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once(__DIR__.'/../models/conexion.class.php');


class SolicitudController extends conexion{

	private $container;

	public function __construct($container){

		parent::__construct();
		$this->con = $this->getConexion();
		$this->container = $container;
	}


	public function crearSolicitud($request, $response){

		$data = $request->getParsedBody();

		if( !empty($data['email']) &&
			!empty($data['telefono']) &&
			!empty($data['direccion']) &&
			!empty($data['descripcion']) ){
			
			$email = $data['email'];

			$this->con->where('email',$email);
			if($salida = $this->con->getOne('users')){
				//actualizamos usuario

				$user_id = $salida['id'];
				$campos = array('telefono'  => $data['telefono'],
								'direccion' => $data['direccion']
								);

				$this->con->where('email',$email);
				$this->con->update('users',$campos);

			}else{
				//no existe usuario, lo creamos
				$campos = array('email' => $data['email'],
								'telefono'  => $data['telefono'],
								'direccion' => $data['direccion']
								);

				$user_id = $this->con->insert('users',$campos);

			}


			//ahora creamos la solicitud
			$campos = array('descripcion' => $data['descripcion'],
							'categoria' => $data['categoria'],
							'titulo'  => $data['titulo'],
							'usuario' => $user_id
							);

			$this->con->insert('solicitudes',$campos);


		}else{
			return json_encode('{error : "text" : "Faltan datos"}');
		}

	}

	public function modificarSolicitud($request, $response){

		$id = $request->getAttribute('id');

		$this->con->where('id',$id);
		if($salida = $this->con->getOne('solicitudes')){

			if($salida['estado'] == 'pendiente'){

				$data = $request->getParsedBody();

				$campos = array('descripcion' => $data['descripcion'],
								'categoria' => $data['categoria'],
								'titulo'  => $data['titulo']
								);
				$this->con->where('id',$id);
				$this->con->update('solicitudes',$campos);


			}else{
				//no puede ser modificado
				echo '{error : "text" : "Solicitud no puede ser modificada"}';
			}

		}else{
			//no existe ese id
			echo '{error : "text" : "No existe solicitud"}';
		}

	}





	public function publicarSolicitud($request, $response){

		$id = $request->getAttribute('id');

		$this->con->where('id',$id);
		if($salida = $this->con->getOne('solicitudes')){

			if($salida['estado'] == 'pendiente' &&
				!empty($salida['titulo']) &&
				!empty($salida['categoria'])
				){

				$data = $request->getParsedBody();

				$campos = array('estado' => 'publicada');

				$this->con->where('id',$id);
				$this->con->update('solicitudes',$campos);


			}else{
				//no puede ser modificado
				echo '{error : "text" : "Solicitud no puede ser modificada"}';
			}

		}else{
			//no existe ese id
			echo '{error : "text" : "No existe solicitud"}';
		}
	}





	public function descartarSolicitud($request, $response){

		$id = $request->getAttribute('id');

		$this->con->where('id',$id);
		if($salida = $this->con->getOne('solicitudes')){

			if($salida['estado'] != 'descartada'){

				$data = $request->getParsedBody();

				$campos = array('estado' => 'descartada');
				
				$this->con->where('id',$id);
				$this->con->update('solicitudes',$campos);


			}else{
				//no puede ser modificado
				echo '{error : "text" : "La Solicitud ya ha sido descartada"}';
			}

		}else{
			//no existe ese id
			echo '{error : "text" : "No existe solicitud"}';
		}


	}
// El servicio devolverá un listado paginado de las solicitud de presupuesto. Si se le indica un email como parámetro deberá devolver solo las solicitudes de presupuesto que se correspondan con el usuario con ese email.
	public function listarSolicitudes(Request $request, Response $response, $args){

		$email  = $request->getAttribute('email');
		$params = $request->getQueryParams();

		$page = 1;
		$this->con->pageLimit = 2;

		if(isset($params['page'])){
			$page = $params['page'];
		}

		if(isset($params['results'])){
			$this->con->pageLimit = $params['results'];
		}
		

		if($email){
			$this->con->join('users u', 'u.id = s.usuario', 'LEFT');
			$this->con->where('u.email', $email);
			$salida = $this->con->paginate ('solicitudes s', $page, 's.id, s.titulo, s.descripcion, s.categoria, s.estado, s.usuario');
		}else{

			$salida = $this->con->paginate('solicitudes',$page);

		}

		return json_encode($salida);

	}



}
