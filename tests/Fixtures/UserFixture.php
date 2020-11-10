<?php

namespace Tests\Fixtures;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;

class UserFixture implements FixtureInterface
{
    /**
     * @var array
     */
    const USER_NAMES = array("Test Name", "Test Name2", "Test Name3");


    /**
     * @param EntityManagerInterface $manager
     * @return User[]
     */
    public static function create(EntityManagerInterface $manager)
    {
        $users = array_map(function ($userName) use ($manager) {
            $user = new User();
            $user->setName($userName);
            $manager->persist($user);
            return $user;
        }, self::USER_NAMES);

        $manager->flush();

        return $users;
    }
}