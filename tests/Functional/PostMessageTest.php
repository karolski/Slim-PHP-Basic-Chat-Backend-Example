<?php


namespace Tests\Functional;


use App\Constants\StatusCode;
use App\Entities\Message;
use App\Entities\User;
use App\Serializers\MessageSerializer;
use DateTime;
use Tests\ApiTestCase;
use Tests\Fixtures\UserFixture;
use Tests\Utils\HeaderHelper;

class PostMessageTest extends ApiTestCase
{

    /**
     * @var User
     */
    private $author;
    /**
     * @var User
     */
    private $toUser;

    /**
     * @var string
     */
    private $messageContent = "Hey, my new message";

    public function setUp(): void
    {
        parent::setUp();
        $users = UserFixture::create($this->entityManager);
        $this->author = $users[0];
        $this->toUser = $users[1];
    }

    public function testCreateMessage()
    {
        $response = $this->makeAuthenticatedPostRequest($this->getValidPayload());

        $this->assertSame(StatusCode::CREATED, $response->getStatusCode());

        /** @var Message $newMessage */
        $newMessage = $this->entityManager
            ->getRepository(Message::class)
            ->findBy(["content" => $this->messageContent])[0];

        $this->assertTrue($newMessage instanceof Message);
        $this->assertEquals($newMessage->getTo(), $this->toUser);
        $this->assertEquals($newMessage->getFrom(), $this->author);
        $this->assertEquals(
            $newMessage->getCreatedAt()->format('D-H-M'),
            (new DateTime())->format('D-H-M')
        );

        $expectedJson = MessageSerializer::serialize($newMessage);

        $this->assertJsonData($response, $expectedJson);
    }

    public function testCannotCreateMessageWithoutPayload()
    {
        $response = $this->makeAuthenticatedPostRequest(null);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testCannotCreateMessageWithoutAuthentication()
    {
        $response = $this->makePostRequest($this->getValidPayload());
        $this->assertSame(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    public function testRequireContent()
    {
        $payload = $this->getValidPayload();
        unset($payload["content"]);
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testRequireToBeString()
    {
        $payload = $this->getValidPayload();
        $payload["content"] = 123;
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testRequireRecipient()
    {
        $payload = $this->getValidPayload();
        unset($payload["to"]);
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testRequireRecipientToBeValidUuid()
    {
        $payload = $this->getValidPayload();
        $payload["to"] = "invalid uuid";
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testCannotSendTooLongMessages()
    {
        $payload = $this->getValidPayload();
        $payload["content"] = str_repeat("very long message", 10000 );
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::BAD_REQUEST, $response->getStatusCode());
    }

    public function testCannotSendAMessageToInexistentUser()
    {
        $payload = $this->getValidPayload();
        $madeUpUuid = "4A1E06B7-DBF0-4662-AD03-DB68C1476909";
        $payload["to"] = $madeUpUuid;
        $response = $this->makeAuthenticatedPostRequest($payload);
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode());
    }


    private function makeAuthenticatedPostRequest($payload)
    {
        $header = HeaderHelper::getAuthHeader($this->author);
        return $this->makePostRequest($payload, $header);
    }

    private function makePostRequest($payload, $header = null)
    {
        return $this->makeJsonRequest(
            'POST',
            '/messages',
            $payload,
            $header
        );
    }

    private function getValidPayload()
    {
        return [
            "to" => $this->toUser->getId(),
            "content" => $this->messageContent
        ];
    }
}