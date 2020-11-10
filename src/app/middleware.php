<?php
declare(strict_types=1);

use App\Exceptions\HttpExceptionCustomUnauthorized;
use App\Http\Middleware\AddUserMiddleware;
use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {

    $app->addBodyParsingMiddleware();

    $app->add(AddUserMiddleware::class);

    $app->add(new JwtAuthentication([
        "attribute" => "token",
        "secure" => false,
        "path" => ["/users", "/messages"],
        "secret" => $_ENV["JWT_SECRET"],
        "algorithm" => ["RS256"],
        "error" => function ($response, $arguments) {
            throw new HttpExceptionCustomUnauthorized($arguments["message"]);
        }
    ]));

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
};
