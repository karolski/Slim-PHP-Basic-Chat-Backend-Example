<?php


namespace Tests\Fixtures;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;

interface FixtureInterface
{
    /**
     * @param EntityManagerInterface $manager
     * @return User[]
     */
    public static function create(EntityManagerInterface $manager);
}