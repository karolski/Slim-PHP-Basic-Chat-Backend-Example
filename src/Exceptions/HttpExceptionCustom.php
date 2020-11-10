<?php


namespace App\Exceptions;


use App\Constants\StatusCode;
use Throwable;

class HttpExceptionCustom extends \Exception
{
    /**
     * HttpExceptionCustom constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "An error occured", $code = StatusCode::SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}