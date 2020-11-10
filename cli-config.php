<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';
$container = require __DIR__ . '/src/app/container.php';
return ConsoleRunner::createHelperSet($container->get("Doctrine\ORM\EntityManagerInterface"));