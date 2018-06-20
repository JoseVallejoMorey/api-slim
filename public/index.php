<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require '../vendor/autoload.php';
require '../src/controllers/solicitudController.class.php';
require '../src/controllers/categoriaController.class.php';


$app = new \Slim\App;

//rutas
require '../src/routes/routes.php';


$app->run();