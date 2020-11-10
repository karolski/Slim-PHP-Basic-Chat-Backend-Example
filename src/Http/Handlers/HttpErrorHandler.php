<?php
declare(strict_types=1);

namespace App\Http\Handlers;

use App\Constants\StatusCode;
use App\Exceptions\HttpExceptionCustom;
use App\Http\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class HttpErrorHandler extends SlimErrorHandler
{

    const DEFAULT_STATUS_CODE = StatusCode::SERVER_ERROR;
    const DEFAULT_ERROR_MESSAGE = 'An internal error has occurred while processing your request.';

    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = self::DEFAULT_STATUS_CODE;
        $message = self::DEFAULT_ERROR_MESSAGE;

        if (($exception instanceof HttpException) or ($exception instanceof HttpExceptionCustom)) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();
        } else {
            $this->logger->error($exception->getMessage());
            $this->logger->error($exception->getTraceAsString());
        }

        $payload = ["error" => $message];

        if ($this->displayErrorDetails) {
            $payload["trace"] = $exception->getTrace();
        }

        $response = $this->responseFactory->createResponse($statusCode);
        return ResponseHelper::createJsonResponse($response, $payload, $statusCode);
    }
}
