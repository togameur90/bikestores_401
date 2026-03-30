<?php
/**
 * Application Bootstrap File.
 * Initializes the autoloader, configures Doctrine ORM parameters,
 * and sets up the database connection and entity manager.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

/**
 * @var array $paths The paths to the Doctrine Entity classes.
 */
$paths = [__DIR__ . '/../src/Entity'];

/**
 * @var bool $isDevMode Whether the application is running in development mode.
 */
$isDevMode = true;

/**
 * @var \Doctrine\ORM\Configuration $config The Doctrine ORM configuration object.
 */
$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode);

/**
 * @var array $connectionParams The database connection parameters.
 */
$connectionParams = [
    'dbname'      => 'marcher241_9',     
    'user'        => 'marcher241',       
    'password'    => 'Iephaech2ahk0euv',       
    'host'        => 'mysql-etu.unicaen.fr',
    'driver'      => 'pdo_mysql',
    'serverVersion' => '10.5.0-MariaDB',
];

/**
 * @var \Doctrine\DBAL\Connection $connection The Doctrine DBAL connection.
 */
$connection = DriverManager::getConnection($connectionParams, $config);

/**
 * @var EntityManager $entityManager The Doctrine Entity Manager instance, used globally for data operations.
 */
$entityManager = new EntityManager($connection, $config);