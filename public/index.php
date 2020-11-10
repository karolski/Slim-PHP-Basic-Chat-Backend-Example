<?php
declare(strict_types=1);

use App\Http\Handlers\HttpErrorHandler;
use App\Http\ResponseEmitter\ResponseEmitter;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../src/app/bootstrap.php';

ob_start();
$app->run();
