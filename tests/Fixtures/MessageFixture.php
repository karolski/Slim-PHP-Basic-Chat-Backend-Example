<?php

namespace Tests\Fixtures;

use App\Entities\Message;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class MessageFixture implements FixtureInterface
{

    /**
     * @param EntityManagerInterface $manager
     * @return Message[]
     */
    public static function create(EntityManagerInterface $manager)
    {
        $users = UserFixture::create($manager);
        $fixtures = [
            [
                "to" => $users[0],
                "from" => $users[1],
                "content" => "Hello",
                "createdAt" => self::getTime(1)
            ],
            [
                "to" => $users[0],
                "from" => $users[1],
                "content" => "How are you?",
                "createdAt" => self::getTime(2)

            ],
            [
                "to" => $users[0],
                "from" => $users[2],
                "content" => "How are you?",
                "createdAt" => self::getTime(2)

            ],
            [
                "to" => $users[2],
                "from" => $users[1],
                "content" => "How are you?",
                "createdAt" => self::getTime(2)

            ],
            [
                "to" => $users[0],
                "from" => $users[1],
                "content" => "Hi",
                "createdAt" => self::getTime(5)
            ],
            [
                "to" => $users[1],
                "from" => $users[0],
                "content" => "AnotherMessage",
                "createdAt" => self::getTime(10)
            ],
            [
                "to" => $users[1],
                "from" => $users[0],
                "content" => "Hello",
                "createdAt" => self::getTime(20)
            ]
        ];

        $messages = array_map(function ($fixture) use ($manager) {
            $message = new Message();
            $message->setTo($fixture["to"]);
            $message->setFrom($fixture["from"]);
            $message->setContent($fixture["content"]);
            $message->setCreatedAt($fixture["createdAt"]);

            $manager->persist($message);
            return $message;
        }, $fixtures);

        $manager->flush();

        return $messages;
    }

    /**
     * @param int $minutesFromNow
     * @return DateTime
     */
    private static function getTime(int $minutesFromNow)
    {
        $current = new DateTime();
        return $current->modify('+' . $minutesFromNow . ' minutes');
    }
}