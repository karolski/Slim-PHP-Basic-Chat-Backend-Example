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

abstract class Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws HttpException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    /**
     * @return Response
     * @throws HttpException
     */
    abstract protected function action(): Response;

    /**
     * @return array
     * @throws HttpBadRequestException
     */
    protected function getJsonData(): array
    {
        $data = $this->request->getParsedBody();

        if ($data == null) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return (array)$data;

    }

    /**
     * @param string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $payload
     * @param int $statusCode
     * @return Response
     */
    protected function respondWithPayload($payload = null, int $statusCode = StatusCode::OK)
    {
        return ResponseHelper::createJsonResponse($this->response, $payload, $statusCode);
    }
}
