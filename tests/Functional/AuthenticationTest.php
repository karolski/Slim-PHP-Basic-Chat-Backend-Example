<?php


namespace Tests\Functional;


use App\Constants\StatusCode;
use App\Entities\User;
use http\Header;
use Tests\ApiTestCase;
use Tests\Fixtures\UserFixture;
use Tests\Utils\HeaderHelper;
use Tests\Utils\JwtGenerator;

class AuthenticationTest extends ApiTestCase
{

    /**
     * @var string
     */
    private $url = "/users";

    /**
     * @var User
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserFixture::create($this->entityManager)[0];
    }

    private function makeGetRequest($header = null)
    {
        return $this->makeJsonRequest(
            'GET',
            $this->url,
            null,
            $header
        );
    }

    private function getCustomHeader(string $token): Header
    {
        return new Header("Authorization", "Bearer " . $token);
    }

    public function testCanAccessEndpoint()
    {
        $header = HeaderHelper::getAuthHeader($this->user);
        $response = $this->makeGetRequest($header);
        $this->assertEquals(StatusCode::OK, $response->getStatusCode());
    }

    public function testCannotAccessWithInvalidToken()
    {
        $header = $this->getCustomHeader("invalidtoken");
        $response = $this->makeGetRequest($header);
        $this->assertEquals(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    public function testCannotAccessExpiredToken()
    {
        $token = JwtGenerator::getJwt($this->user, -10);
        $header = $this->getCustomHeader($token);
        $response = $this->makeGetRequest($header);
        $this->assertEquals(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    public function testCannotAccessWithoutIdInToken()
    {
        $newUser = new User();
        $newUser->setName("New unsaved user");
        $token = JwtGenerator::getJwt($newUser);
        $header = $this->getCustomHeader($token);
        $response = $this->makeGetRequest($header);
        $this->assertEquals(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    public function testCannotAccessAsUnregisteredUser()
    {
        $header = HeaderHelper::getAuthHeader($this->user);
        $this->entityManager->remove($this->user);
        $this->entityManager->flush();
        $response = $this->makeGetRequest($header);
        $this->assertEquals(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }
}