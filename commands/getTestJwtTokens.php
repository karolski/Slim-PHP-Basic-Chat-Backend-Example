<?php

use Tests\Utils\JwtGenerator;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/** @var \DI\Container $container */
$container = require __DIR__ . '/../src/app/container.php';
$entityManager = $container->get(Doctrine\ORM\EntityManagerInterface::class);

/** @var \App\Entities\User[] $users */
$users = $entityManager->getRepository(\App\Entities\User::class)->findAll();

foreach ($users as $user) {
    echo "\n id: ".$user->getId();
    echo "\n name: ".$user->getName();
    echo "\n token: ". JwtGenerator::getJwt($user);
    echo "\n ------------------------------------------ \n  ";
}