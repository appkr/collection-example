<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TaskTest extends DoctrineTestCase
{
    /** @var EntityRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->em->getRepository(Task::class);
    }

    public function testCanSaveEntity()
    {
        $entity = $this->em->transactional(function (EntityManagerInterface $em) {
            $entity = Task::of("task 1");

            $em->persist($entity);

            return $entity;
        });

        $sut = $this->repository->find($entity->getId());

        echo $sut;
        self::assertNotNull($sut);
    }

    public function testTaskCanBeAssociatedToProject()
    {
        $task1 = $this->em->transactional(function (EntityManagerInterface $em) {
            $project1 = Project::of("project 1");
            $task1 = Task::of("task 1");
            $task1->setProject($project1);

            $this->em->persist($task1);

            return $task1;
        });

        /** @var Task $sut */
        $sut = $this->repository->find($task1->getId());

        echo $sut;
        self::assertNotNull($sut->getProject());
    }

    public function testCanMarkDone()
    {
        $entity = $this->em->transactional(function (EntityManagerInterface $em) {
            $entity = Task::of("task 1");
            $entity->markDone();

            $em->persist($entity);

            return $entity;
        });

        /** @var Task $sut */
        $sut = $this->repository->find($entity->getId());

        echo $sut;
        self::assertTrue($sut->isDone());
    }
}
