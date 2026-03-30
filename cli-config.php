<?php
require_once 'config/bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// Doctrine 2.11+ approach
if (class_exists(SingleManagerProvider::class)) {
    return ConsoleRunner::createHelperSet($entityManager);
} 
// Fallback for older Doctrine
else {
    return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
}
