<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\User;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class AddUserMiddleware implements Middleware
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     * @throws HttpUnauthorizedException
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getAttribute('token');
        if ($token) {
            $userId = $token["id"];
            if(!is_string($userId)){
                throw new HttpUnauthorizedException($request, "Invalid token payload");
            }

            /** @var User $user */
            $user = $this->userService->findById($token["id"]);

            if ($user == null) {
                throw new HttpUnauthorizedException($request, "User not registered");
            }
            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }
}
