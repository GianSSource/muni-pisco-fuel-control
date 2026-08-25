<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CalendarController;
use App\Controllers\ClientController;
use App\Controllers\DashboardController;
use App\Controllers\FuelController;
use App\Controllers\ProjectController;
use App\Controllers\ReportController;
use App\Core\Router;

session_start();

require dirname(__DIR__) . '/config/config.php';

spl_autoload_register(static function (string $class): void {

    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $path = dirname(__DIR__) . '/app/' .
        str_replace('\\', '/', substr($class, strlen($prefix))) .
        '.php';

    if (is_file($path)) {
        require $path;
    }
});
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$router = new Router();

$router->get('/', AuthController::class, 'showLogin');
$router->get('/login', AuthController::class, 'showLogin');
$router->post('/login', AuthController::class, 'login');

$router->get('/registro', AuthController::class, 'showRegister');
$router->post('/registro', AuthController::class, 'register');

$router->get('/logout', AuthController::class, 'logout');

$router->get('/dashboard', DashboardController::class, 'index');

$router->get('/calendario', CalendarController::class, 'index');
$router->get('/api/calendario/eventos', CalendarController::class, 'apiEvents');

$router->get('/despachos', FuelController::class, 'index');
$router->get('/api/despachos/buscar', FuelController::class, 'searchApi');
$router->get('/api/fuel/chart-stats', FuelController::class, 'statsApi');

$router->get('/clientes', ClientController::class, 'index');
$router->get('/clientes/nuevos', ClientController::class, 'create');
$router->post('/clientes/guardar', ClientController::class, 'store');
$router->get('/clientes/editar', ClientController::class, 'edit');
$router->post('/clientes/actualizar', ClientController::class, 'update');

$router->get('/proyectos', ProjectController::class, 'index');
$router->get('/proyectos/nuevo', ProjectController::class, 'create');
$router->post('/proyectos/guardar', ProjectController::class, 'store');
$router->get('/proyectos/editar', ProjectController::class, 'edit');
$router->post('/proyectos/actualizar', ProjectController::class, 'update');

$router->get('/reportes/clientes', ReportController::class, 'clients');
$router->get('/reportes/proyectos', ReportController::class, 'projects');
$router->get('/reportes/pdf', ReportController::class, 'entregasPdf');
$router->get('/reportes/excel', ReportController::class, 'entregasCsv');

$router->dispatch(

    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);