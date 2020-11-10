<?php


namespace App\Exceptions;


use App\Constants\StatusCode;
use Throwable;

class HttpExceptionCustomNotFound extends HttpExceptionCustom
{
    /**
     * HttpExceptionCustomNotFound constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Object not found", $code = StatusCode::NOT_FOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}