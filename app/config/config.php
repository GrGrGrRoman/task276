<?php 

namespace App\config;

define('ROOT', dirname(__DIR__));
define('CONTROLLER', ROOT . DIRECTORY_SEPARATOR . 'controllers');
define('MODEL', ROOT . DIRECTORY_SEPARATOR . 'models');
define('VIEW', ROOT . DIRECTORY_SEPARATOR . 'views');
define('CORE', ROOT . DIRECTORY_SEPARATOR . 'core');
define('LIB', ROOT . DIRECTORY_SEPARATOR . 'lib');
define('DB', ROOT . DIRECTORY_SEPARATOR . 'db');
define('SQL_FILE_NAME', DB . DIRECTORY_SEPARATOR . 'sqlite.db');
define('ROUTES', ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php');
define('CONF', ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('LOG', ROOT . DIRECTORY_SEPARATOR . 'log');

// Google API configuration
define('GOOGLE_CLIENT_ID', '203003412348-5dvsukeo88jhi899k7lukaaaqs2vo615.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');

//ВНИМАНИЕ! укажите порт вашего тестового web-сервера, ex. localhost:8000
define('GOOGLE_REDIRECT_URL', 'http://localhost:8000/register_google/');
