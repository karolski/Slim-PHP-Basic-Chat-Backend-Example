<?php


namespace Tests\Functional;


use App\Constants\StatusCode;
use App\Entities\Message;
use App\Entities\User;
use App\Serializers\MessageSerializer;
use Tests\ApiTestCase;
use Tests\Fixtures\MessageFixture;
use Tests\Utils\HeaderHelper;

class GetMessagesTest extends ApiTestCase
{

    /**
     * @var Message[]
     */
    private $messages;


    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $contact;

    public function setUp(): void
    {
        parent::setUp();
        $this->messages = MessageFixture::create($this->entityManager);
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();
        $this->user = $users[0];
        $this->contact = $users[1];
    }

    public function testGetsMessagesWithFixtures()
    {
        $response = $this->makeAuthenticatedGetRequest($this->contact->getId());
        $this->assertSame(StatusCode::OK, $response->getStatusCode());
        $expectedMessages = array_filter($this->messages, function($message){
            return ($message->getTo() == $this->user and $message->getFrom() == $this->contact)
                or ($message->getTo() == $this->contact and $message->getFrom() == $this->user);
        });
        usort($expectedMessages, function ($m1, $m2){
            return ($m1->getCreatedAt() < $m2->getCreatedAt());
        });
        $expectedJson = MessageSerializer::serializeArray($expectedMessages);
        $this->assertJsonData($response, $expectedJson);
    }

    public function testCannotGetMessagesWithInexistentContact()
    {
        $madeUpUuid = "4A1E06B7-DBF0-4662-AD03-DB68C1476909";
        $response = $this->makeAuthenticatedGetRequest($madeUpUuid);
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode());
    }


    public function testCannotAccessWithoutAuthentication()
    {
        $response = $this->makeGetReqest($this->contact->getId());
        $this->assertSame(StatusCode::UNAUTHORIZED, $response->getStatusCode());
    }

    public function testForEmptyMessageSet()
    {
        $newUser = new User();
        $newUser->setName("New");
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();
        $response = $this->makeAuthenticatedGetRequest($newUser->getId());
        $this->assertSame(StatusCode::OK, $response->getStatusCode());
        $this->assertJsonData($response, []);
    }

    private function makeAuthenticatedGetRequest($contactId="")
    {
        $header = HeaderHelper::getAuthHeader($this->user);
        return $this->makeGetReqest($contactId, $header);
    }

    private function makeGetReqest($contactId="", $header = null)
    {
        $url = '/messages/with/' . $contactId;
        return $this->makeJsonRequest(
            'GET',
            $url,
            null,
            $header
        );
    }
}