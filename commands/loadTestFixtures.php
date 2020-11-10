<?php

use Tests\Fixtures\MessageFixture;

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../src/app/container.php';
$entityManager = $container->get("Doctrine\ORM\EntityManagerInterface");
MessageFixture::create($entityManager);
echo "Loaded fixtures";