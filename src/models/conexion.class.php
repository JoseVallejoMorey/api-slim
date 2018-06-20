<?php

require_once(__DIR__.'/../config/config.php');
require_once('MysqliDb.php');

class conexion{

	private $con;

	public function __construct(){

		$this->con = new MysqliDb (HOST, USER, PASS, TABLE);

	}


	public function getConexion(){
		return $this->con;
	}

}