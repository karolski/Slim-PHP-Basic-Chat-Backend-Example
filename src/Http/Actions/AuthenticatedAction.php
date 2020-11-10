<?php
declare(strict_types=1);

namespace App\Http\Actions;

use App\Constants\StatusCode;
use App\Entities\User;
use App\Http\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpInternalServerErrorException;

abstract class AuthenticatedAction extends Action
{
    /**
     * @return User
     */
    protected function getUser(): User
    {
        return $this->request->getAttribute('user');
    }
}
