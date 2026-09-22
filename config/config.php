<?php
declare(strict_types=1);

const APP_NAME = 'Control de Combustible.';
const BASE_URL = '/';

// Para configuración local (base de datos, entorno), crea un archivo 'config.local.php'
// en esta misma carpeta y define allí las constantes. Ese archivo NO se sube a Git.
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

// 'development' muestra errores en pantalla; 'production' los oculta.
defined('APP_ENV')    || define('APP_ENV', 'production');

defined('DB_HOST')    || define('DB_HOST', 'localhost');
defined('DB_NAME')    || define('DB_NAME', 'tecnosoluciones');
defined('DB_USER')    || define('DB_USER', 'root');
defined('DB_PASS')    || define('DB_PASS', '');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');