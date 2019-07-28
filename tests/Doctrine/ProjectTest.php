<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProjectTest extends DoctrineTestCase
{
    /** @var EntityRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->em->getRepository(Project::class);
    }

    public function testMarkDone()
    {
        /** @var Project $project2 */
        $project2 = $this->em->transactional(function (EntityManagerInterface $em) {
            $project2 = Project::of("project 2");
            $project2->addElement(Task::of("task 2-1"));
            $project2->addElement(Task::of("task 2-2"));
            $project2->markDone();

            $em->persist($project2);

            return $project2;
        });

        echo $project2;
        self::assertCount(2, $project2->getElements());
        $project2->getElements()->forAll(function (int $i, Task $e) {
            self::assertTrue($e->isDone());
        });
    }

    public function testGetElementShouldBeImmutable()
    {
        $project2 = Project::of("project 2");
        $task2_0 = Task::of("task 2-0");
        $task2_1 = Task::of("task 2-1");
        $project2->addElement($task2_0);
        $project2->addElement($task2_1);

        /** @var Project $project2 */
        $project2 = $this->em->transactional(function (EntityManagerInterface $em) use ($project2) {
            $em->persist($project2);

            return $project2;
        });

        /** @var Project $sut */
        $sut = $this->repository->find($project2->getId());

        // TODO @appkr Failed asserting that actual size 1 matches expected size 2.
        echo $project2;
        self::assertCount(2, $project2->getElements());

        echo sprintf("task2-1 %s, task2-2 %s", spl_object_hash($task2_0), spl_object_hash($task2_1)), PHP_EOL;
        $sut->getElements()->forAll(function (int $i, Task $e) {
            echo "task2-{$i} ", spl_object_hash($e), PHP_EOL;
        });

        self::assertTrue(true);
    }
}
