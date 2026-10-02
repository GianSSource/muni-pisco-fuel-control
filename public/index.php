<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CalendarController;
use App\Controllers\DashboardController;
use App\Controllers\FuelController;
use App\Controllers\IncomeController;
use App\Controllers\ReportController;
use App\Controllers\UserController;
use App\Core\Router;

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require dirname(__DIR__) . '/vendor/autoload.php';
}
require dirname(__DIR__) . '/config/config.php';

// Configurar zona horaria de Perú
date_default_timezone_set('America/Lima');

// Cookie de sesión endurecida: HttpOnly evita que JS lea la cookie (XSS no
// puede robar la sesión), SameSite=Lax mitiga CSRF básico, Secure solo se
// activa si la petición ya llega por HTTPS (para no romper el entorno de
// desarrollo local sin TLS).
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

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
    } else {
        error_log("Autoloader: Class file not found for '$class' at '$path'");
        throw new \Exception("Clase '$class' no encontrada. Verifique el namespace y el nombre del archivo en: $path");
    }
});
error_reporting(E_ALL);
if (defined('APP_ENV') && APP_ENV === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
}
$router = new Router();

$router->get('/', AuthController::class, 'showLogin');
$router->get('/login', AuthController::class, 'showLogin');
$router->post('/login', AuthController::class, 'login');

$router->get('/registro', AuthController::class, 'showRegister');
$router->post('/registro', AuthController::class, 'register');

$router->post('/logout', AuthController::class, 'logout');

$router->get('/dashboard', DashboardController::class, 'index');

$router->get('/calendario', CalendarController::class, 'index');
$router->get('/api/calendario/eventos', CalendarController::class, 'apiEvents');

$router->get('/despachos', FuelController::class, 'index');
$router->get('/despachos/nuevo', FuelController::class, 'create');
$router->post('/despachos/guardar', FuelController::class, 'store');
$router->post('/despachos/anular', FuelController::class, 'anular');
$router->get('/despachos/imprimir', FuelController::class, 'printReceipt');
$router->get('/api/despachos/buscar', FuelController::class, 'searchApi');
$router->get('/api/fuel/chart-stats', FuelController::class, 'statsApi');

$router->get('/ingresos', IncomeController::class, 'index');
$router->get('/ingresos/nuevo', IncomeController::class, 'create');
$router->post('/ingresos/guardar', IncomeController::class, 'store');

$router->get('/admin/catalogos', App\Controllers\AdminController::class, 'catalogos');
$router->post('/admin/catalogos/guardar', App\Controllers\AdminController::class, 'storeCatalog');
$router->post('/admin/catalogos/eliminar', App\Controllers\AdminController::class, 'deleteCatalog');
$router->post('/admin/vehiculos/guardar', App\Controllers\AdminController::class, 'storeVehicle');
$router->post('/admin/vehiculos/eliminar', App\Controllers\AdminController::class, 'deleteVehicle');

$router->get('/admin/usuarios', UserController::class, 'index');
$router->post('/admin/usuarios/guardar', UserController::class, 'store');
$router->post('/admin/usuarios/rol', UserController::class, 'updateRole');
$router->post('/admin/usuarios/eliminar', UserController::class, 'delete');
$router->post('/admin/usuarios/estado', UserController::class, 'toggleStatus');

$router->get('/reportes', ReportController::class, 'index');
$router->get('/reportes/pdf', ReportController::class, 'entregasPdf');
$router->get('/reportes/excel', ReportController::class, 'entregasExcel');
$router->get('/reportes/csv', ReportController::class, 'entregasCsv');
$router->get('/api/reportes/count', ReportController::class, 'apiCount');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($method, $uri);