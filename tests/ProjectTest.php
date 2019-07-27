<?php

namespace App;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testGetElementShouldReturnImmutableElement()
    {
        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $elements = new Collection([$task1, $task2]);
        $sut = new Project($elements);

        echo $sut;
        self::assertNotEquals(spl_object_hash($elements), spl_object_hash($sut->getElements()));
    }

    public function testShouldNotAcceptMoreElementsThanAllowedTo()
    {
        $this->expectException(\InvalidArgumentException::class);

        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $task3 = new Task("task 3");
        $task4 = new Task("task 4");
        $elements = new Collection([$task1, $task2, $task3, $task4]);

        new Project($elements);
    }

    public function testShouldNotAcceptMoreElementsThanAllowedTo2()
    {
        $this->expectException(\InvalidArgumentException::class);

        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $task3 = new Task("task 3");
        $task4 = new Task("task 4");
        $elements = new Collection([$task1, $task2, $task3]);
        $sut = new Project($elements);

        $sut->add($task4);
    }

    public function testCanRemoveElement()
    {
        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $elements = new Collection([$task1, $task2]);
        $sut = new Project($elements);

        $sut->remove($task1);

        echo $sut;
        self::assertCount(1, $sut->getElements());
    }

    public function testCanChangeDoneStateOfAllElements()
    {
        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $elements = new Collection([$task1, $task2]);
        $sut = new Project($elements);

        $sut->markDone();

        echo $sut;
        $sut->getElements()->each(function (Task $e) {
            self::assertTrue($e->isDone());
        });
    }
}
