<?php

use App\Http\Handlers\HttpErrorHandler;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$container = require __DIR__ . '/container.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register middleware
$middleware = require __DIR__ . '/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/routes.php';
$routes($app);

//// Add Routing Middleware
$app->addRoutingMiddleware();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$callableResolver = $app->getCallableResolver();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Add Error Middleware
/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];
/** @var bool logErrorDetails */
$logErrorDetails = $container->get('settings')['logErrorDetails'];

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrorDetails, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

return $app;