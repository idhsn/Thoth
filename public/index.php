<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/core/Router.php';

$router = new Router();

$router->get('/', ['HomeController', 'index']);
$router->get('/register', ['StudentController', 'showRegister']);
$router->post('/register', ['StudentController', 'register']);
$router->get('/login', ['StudentController', 'showLogin']);
$router->post('/login', ['StudentController', 'login']);
$router->post('/logout', ['StudentController', 'logout']);

$router->get('/student/dashboard', ['StudentController', 'dashboard']);
$router->get('/student/course/{id}', ['StudentController', 'course']);
$router->post('/student/course/{id}/enroll', ['StudentController', 'enroll']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
