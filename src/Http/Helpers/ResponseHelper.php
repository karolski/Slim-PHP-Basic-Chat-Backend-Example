<?php


namespace App\Http\Helpers;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    /**
     * @param ResponseInterface $response
     * @param array|object|null $payload
     * @param int $statusCode
     * @return ResponseInterface
     */
    public static function createJsonResponse(ResponseInterface $response, $payload, int $statusCode): ResponseInterface
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        if ($json == false) {
            $json = "";
        }
        $response->getBody()->write($json);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}