<?php


namespace App\Exceptions;


use App\Constants\StatusCode;
use Throwable;

class HttpExceptionCustomUnauthorized extends HttpExceptionCustom
{

    /**
     * HttpExceptionCustomUnauthorized constructor.
     * @param mixed $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Unauthorized", $code = StatusCode::UNAUTHORIZED, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}