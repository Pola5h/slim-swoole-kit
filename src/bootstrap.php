<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;
use Slim\Middleware\BodyParsingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add this line:
$app->addBodyParsingMiddleware();

// Eloquent setup
$capsule = new Capsule;
$dbDriver = strtolower($_ENV['DB_CONNECTION'] ?? 'sqlite');

if ($dbDriver === 'mysql') {
    // Check required MySQL env vars
    $required = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT'];
    foreach ($required as $var) {
        if (empty($_ENV[$var])) {
            throw new RuntimeException("Missing required MySQL environment variable: $var");
        }
    }
    $capsule->addConnection([
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'],
        'database' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'port' => $_ENV['DB_PORT'],
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);
} elseif ($dbDriver === 'sqlite') {
    if (empty($_ENV['SQLITE_PATH'])) {
        throw new RuntimeException("Missing SQLITE_PATH environment variable for SQLite connection.");
    }
    $capsule->addConnection([
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../' . $_ENV['SQLITE_PATH'],
        'prefix' => '',
    ]);
} else {
    throw new RuntimeException("Unsupported DB_CONNECTION value: $dbDriver. Use 'mysql' or 'sqlite'.");
}

$capsule->setAsGlobal();
$capsule->bootEloquent();

(require __DIR__ . '/Routes/api.php')($app);

return $app;
