<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TodoTest extends DoctrineTestCase
{
    /** @var EntityRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->em->getRepository(Todo::class);
    }

    public function testCompositeConstruction()
    {
        /** @var Todo $project */
        $project = $this->em->transactional(function (EntityManagerInterface $em) {
            $project = Project::of("root project");
                $task1 = Task::of("task 1");
                $task2 = Task::of("task 2");
                $project1 = Project::of("project 1");
                    $task1_1 = Task::of("task 1-1");
                    $project1_1 = Project::of("project 1-1");

            $project->addElement($task1);
            $project->addElement($task2);
            $project->addElement($project1);
            $project1->addElement($task1_1);
            $project1->addElement($project1_1);

            $project->markDone();

            $em->persist($project);

            return $project;
        });

        $project = $this->repository->find($project->getId());

        echo $project, PHP_EOL;
        echo $project->toHtml(), PHP_EOL;
        self::assertNotNull($project);
    }
}
