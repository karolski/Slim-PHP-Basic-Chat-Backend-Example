<?php


namespace Unit\Serializers;


use App\Entities\User;
use App\Serializers\UserSerializer;
use Tests\ApiTestCase;
use Tests\Fixtures\UserFixture;

class UserSerializerTest extends ApiTestCase
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

    /**
     * @param User $user
     * @return array
     */
    private function getExpectedArray(User $user): array
    {
        return [
                "name"=> $user->getName(),
                "id"=> $user->getId()
        ];
    }

    function testSerializesAUserInstance()
    {
        $user = $this->users[0];
        $serializedUser = UserSerializer::serialize($user);
        $this->assertEquals($this->getExpectedArray($user), $serializedUser);
    }

    function testSerializesUserArray()
    {
        $serializedUser = UserSerializer::serializeArray($this->users);
        $expectedArray = array_map(function ($user){
            return $this->getExpectedArray($user);
        }, $this->users);
        $this->assertEquals($expectedArray, $serializedUser);
    }


}