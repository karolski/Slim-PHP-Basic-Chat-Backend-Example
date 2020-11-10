<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;


$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__DIR__ . '/settings.php');

$containerBuilder->addDefinitions([
    LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $loggerSettings = $settings['logger'];
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $logger->pushHandler($handler);
        $logger->pushHandler(new ErrorLogHandler());

        return $logger;
    },
]);

$containerBuilder->addDefinitions([
    EntityManagerInterface::class => function (ContainerInterface $c): EntityManager {
        $doctrineSettings = $c->get('settings')['doctrine'];

        $config = Setup::createAnnotationMetadataConfiguration(
            $doctrineSettings['metadata_dirs'],
            $doctrineSettings['dev_mode']
        );

        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new AnnotationReader,
                $doctrineSettings['metadata_dirs']
            )
        );

        $config->setMetadataCacheImpl(
            new FilesystemCache($doctrineSettings['cache_dir'])
        );

        if (isset($_ENV["TESTING"])) {
            $connection = $doctrineSettings["connections"]["test"];
        } else {
            $connection = $doctrineSettings["connections"]["default"];
        }
        return EntityManager::create($connection, $config);
    }
]);

return $containerBuilder->build();
