<?php
declare(strict_types=1);

use App\Http\Actions\Message\CreateMessageAction;
use App\Http\Actions\Message\GetMessagesWithContactAction;
use App\Http\Actions\User\ListUsersAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('
            Welcome to chat backend! <br/>
            See
            <a href="https://documenter.getpostman.com/view/5824253/TVRka7xD" target="_blank">
                Documentation
            </a>
        ');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
    });

    $app->group('/messages', function (Group $group) {
        $group->get('/with/{contact_id}', GetMessagesWithContactAction::class);
        $group->post('', CreateMessageAction::class);
    });

    $app->group('/testing', function (Group $group) {
        $group->get('/get-jwt-tokens', function (Request $request, Response $response) {
            require __DIR__ . '/../../commands/getTestJwtTokens.php';
            return $response->withHeader('Content-type', 'text/plain ');
        });
    });
};
