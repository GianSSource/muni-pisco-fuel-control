<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CalendarController;
use App\Controllers\ClientController;
use App\Controllers\DashboardController;
use App\Controllers\FuelController;
use App\Controllers\IncomeController;
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
$router->get('/despachos/nuevo', FuelController::class, 'create');
$router->post('/despachos/guardar', FuelController::class, 'store');
$router->get('/despachos/imprimir', FuelController::class, 'printReceipt');
$router->get('/api/despachos/buscar', FuelController::class, 'searchApi');
$router->get('/api/fuel/chart-stats', FuelController::class, 'statsApi');

$router->get('/ingresos', IncomeController::class, 'index');
$router->get('/ingresos/nuevo', IncomeController::class, 'create');
$router->post('/ingresos/guardar', IncomeController::class, 'store');

$router->get('/admin/catalogos', App\Controllers\AdminController::class, 'catalogos');
$router->post('/admin/catalogos/guardar', App\Controllers\AdminController::class, 'storeCatalog');
$router->get('/admin/catalogos/eliminar', App\Controllers\AdminController::class, 'deleteCatalog');

$router->get('/reportes/pdf', ReportController::class, 'entregasPdf');
$router->get('/reportes/excel', ReportController::class, 'entregasCsv');

$router->dispatch(

    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);