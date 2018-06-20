<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;


//Crear solicitud de presupuesto
$app->post('/solicitud', 'SolicitudController:crearSolicitud');

//Modificar solicitud de presupuesto pendiente
$app->put('/solicitud/{id}', 'SolicitudController:ModificarSolicitud');

//Publicar solicitud de presupuesto pendiente
$app->put('/publicar/{id}', 'SolicitudController:publicarSolicitud');

//Descartar solicitud de presupuesto
$app->put('/descartar/{id}', 'SolicitudController:descartarSolicitud');

//Listar solicitudes de presupuesto
$app->get('/solicitud', 'SolicitudController:listarSolicitudes');

//Listar solicitudes de un usuario
$app->get('/solicitud/{email}', 'SolicitudController:listarSolicitudes');

//Sugerir categoria
$app->post('/sugerencia', 'CategoriaController:sugerirCategoria');