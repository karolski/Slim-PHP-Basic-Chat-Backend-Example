<?php
declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use http\Header;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

class ApiTestCase extends TestCase
{
    /** @var App */
    protected $app;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Bootstrap app.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->app = require __DIR__ . '/../src/app/bootstrap.php';
        $this->entityManager = $this->app->getContainer()->get(EntityManagerInterface::class);
        $this->recreateDbSchema();
    }

    /**
     * @param string $method
     * @param string|UriInterface $uri
     * @param array|null $data
     * @param Header|null $header
     * @return ResponseInterface
     */
    protected function makeJsonRequest(
        string $method,
        $uri,
        array $data = null,
        Header $header = null
    ): ResponseInterface
    {
        $request = $this->createJsonRequest($method, $uri, $data, $header);
        return $this->app->handle($request);
    }

    /**
     * Create a server request.
     *
     * @param string $method The HTTP method
     * @param string|UriInterface $uri The URI
     * @param array $serverParams The server parameters
     *
     * @return ServerRequestInterface
     */
    protected function createRequest(
        string $method,
        $uri,
        array $serverParams = []
    ): ServerRequestInterface
    {
        return (new ServerRequestFactory())
            ->createServerRequest($method, $uri, $serverParams);
    }

    /**
     * Create a JSON request.
     *
     * @param string $method The HTTP method
     * @param string|UriInterface $uri The URI
     * @param array|null $data The json data
     *
     * @param Header|null $header
     * @return ServerRequestInterface
     */
    protected function createJsonRequest(
        string $method,
        $uri,
        array $data = null,
        Header $header = null
    ): ServerRequestInterface
    {
        $request = $this->createRequest($method, $uri);

        if ($data !== null) {
            $request = $request->withParsedBody($data);
        }
        if ($header !== null) {
            $request = $request->withAddedHeader($header->name, $header->value);
        }

        return $request->withHeader('Content-Type', 'application/json');
    }

    /**
     * Verify that the given array is an exact match for the JSON returned.
     *
     * @param ResponseInterface $response The response
     * @param array $expected The expected array
     *
     * @return void
     */
    protected function assertJsonData(
        ResponseInterface $response,
        array $expected
    ): void
    {
        $actual = (string)$response->getBody();
        $this->assertJson($actual);
        $this->assertSame(
            $expected,
            (array)json_decode($actual, true));
    }

    private function recreateDbSchema()
    {

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}
