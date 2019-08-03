<?php
/**
 * Referenced from Doctrine official doc
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/configuration.html#obtaining-an-entitymanager
 *
 * To handle the following issue
 * ```
 * $ vendor/bin/doctrine orm:schema-tool:create
 * # [OK] No Metadata Classes to process.
 * ```
 * Doctrine2 - No Metadata Classes to process
 * @see https://stackoverflow.com/a/19129147
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

$entityPath = [__DIR__ . "/src/Doctrine"];
$devMode    = true;
$doctrineConfig = Setup::createAnnotationMetadataConfiguration($entityPath, $devMode);

$dbConfig   = [
    "driver" => "pdo_sqlite",
    "database" => __DIR__ . "/database.sqlite",
];
$em = EntityManager::create($dbConfig, $doctrineConfig);
