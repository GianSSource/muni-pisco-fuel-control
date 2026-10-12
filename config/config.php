<?php
declare(strict_types=1);

// Para configuración local (base de datos, entorno), crea un archivo 'config.local.php'
// en esta misma carpeta y define allí las constantes. Ese archivo NO se sube a Git.
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

defined('APP_NAME') || define('APP_NAME', 'Control de Combustible.');

if (!defined('BASE_URL')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseUrl = ($scriptDir === '/' || $scriptDir === '' || $scriptDir === '.') ? '/' : rtrim($scriptDir, '/') . '/';
    define('BASE_URL', $baseUrl);
}


// 'development' muestra errores en pantalla; 'production' los oculta.
defined('APP_ENV')    || define('APP_ENV', 'production');

defined('DB_HOST')    || define('DB_HOST', 'localhost');
defined('DB_NAME')    || define('DB_NAME', 'tecnosoluciones');
defined('DB_USER')    || define('DB_USER', 'root');
defined('DB_PASS')    || define('DB_PASS', '');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');