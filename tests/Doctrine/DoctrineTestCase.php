<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class DoctrineTestCase extends TestCase
{
    /** @var EntityManagerInterface */
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = $this->createEntityManager();
    }

    private function createEntityManager()
    {
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src"], $isDevMode);
        $conn = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../database.sqlite',
        ];

        return EntityManager::create($conn, $config);
    }
}
