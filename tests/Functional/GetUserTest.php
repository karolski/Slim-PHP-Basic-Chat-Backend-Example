<?php


namespace Tests\Functional;


use App\Constants\StatusCode;
use App\Entities\User;
use Tests\ApiTestCase;
use Tests\Fixtures\UserFixture;
use Tests\Utils\HeaderHelper;

class GetUserTest extends ApiTestCase
{

    /**
     * @var User[]
     */
    private $users;

    public function setUp(): void
    {
        parent::setUp();
        $this->users = UserFixture::create($this->entityManager);
    }

    public function testGetsAllUsersWithFixtures()
    {

        $response = $this->makeAuthenticatedGetRequest();
        $this->assertSame(StatusCode::OK, $response->getStatusCode());

        $expectedJson = array_map(function ($user) {
            return [
                "name" => $user->getName(),
                "id" => $user->getId(),
            ];
        }, $this->users);

        $this->assertJsonData($response, $expectedJson);
    }

    public function testCannotAccessWithoutAuthentication()
    {
        $response = $this->makeGetRequest();
        $this->assertSame(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    private function makeAuthenticatedGetRequest()
    {
        $header = HeaderHelper::getAuthHeader($this->users[0]);
        return $this->makeGetRequest($header);
    }

    private function makeGetRequest($header = null)
    {
        return $this->makeJsonRequest(
            'GET',
            '/users',
            null,
            $header
        );
    }
}