<?php


namespace Unit\Serializers;


use App\Entities\Message;
use App\Entities\User;
use App\Serializers\MessageSerializer;
use PHPUnit\Framework\TestCase;
use Tests\ApiTestCase;
use Tests\Fixtures\MessageFixture;

class MessageSerializerTest extends ApiTestCase
{

    /**
     * @var Message[]
     */
    private $messages;
    public function setUp(): void
    {
        parent::setUp();
        $this->messages = MessageFixture::create($this->entityManager);
    }

    private function getExpectedArray(Message $message)
    {
        $from = $message->getFrom();
        $to = $message->getTo();
        return [
            "from" => [
                "name"=> $from->getName(),
                "id"=> $from->getId()
            ],
            "to" => [
                "name"=> $to->getName(),
                "id"=> $to->getId(),
            ],
            "content"=>$message->getContent(),
            "id"=>$message->getId(),
            "created_at"=>$message->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }

    function testSerializesAMessageInstance()
    {
        $message = $this->messages[0];
        $serializedMessage = MessageSerializer::serialize($message);
        $this->assertEquals($this->getExpectedArray($message), $serializedMessage);
    }

    function testSerializesMessageArray()
    {
        $serializedMessage = MessageSerializer::serializeArray($this->messages);
        $expectedArray = array_map(function ($message){
            return $this->getExpectedArray($message);
        }, $this->messages);
        $this->assertEquals($expectedArray, $serializedMessage);
    }


}