<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    const ENTITY_PATH = __DIR__ . "/../../src/Doctrine";
    const DB_CONFIG   = [
        "driver" => "sqlite",
        "database" => __DIR__ . "/../../database.sqlite",
    ];
    const DEV_MODE    = true;

    protected function setUp(): void
    {
        parent::setUp();
        $config = Setup::createAnnotationMetadataConfiguration(static::ENTITY_PATH, static::DEV_MODE);
        $em = EntityManager::create(static::DB_CONFIG, $config);
    }

}
