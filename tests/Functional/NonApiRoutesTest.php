<?php


namespace Tests\Functional;

use App\Constants\StatusCode;
use Tests\ApiTestCase;
use Tests\Fixtures\UserFixture;

class NonApiRoutesTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        UserFixture::create($this->entityManager);
    }

    public function testHomeRoute()
    {
        $response = $this->makeJsonRequest('GET', '');
        $this->assertEquals(StatusCode::OK, $response->getStatusCode());
    }

    public function testMakeOptionsRequest()
    {
        $response = $this->makeJsonRequest('OPTIONS', '/');
        $this->assertEquals(StatusCode::OK, $response->getStatusCode());
    }

    public function testMakeOptionsRequestRandomUrl()
    {
        $response = $this->makeJsonRequest('OPTIONS', '/cdsjkcnskcds');
        $this->assertEquals(StatusCode::OK, $response->getStatusCode());
    }
}