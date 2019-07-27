<?php

namespace App;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /** @var Collection */
    private $elements;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elements = new Collection();
    }

    public function testCanAddTaskObjectToTheCollection()
    {
        $task1 = new Task("task 1");
        $this->elements->add($task1);

        self::assertCount(1, $this->elements);
    }

    public function testEachApi()
    {
        // Intermediate operation
        // void function (T)
        // Mutable operation
        // BAD!! violation of "Law of Demeter"

        $task1 = new Task("task 1");
        $this->elements->add($task1);
        $cached = $this->elements;

        $this->elements->each(function (Task $e) {
                $e->markDone();
            })
            ->each(self::printCallback());

        self::assertEquals(spl_object_hash($cached), spl_object_hash($this->elements));
    }

    public function testFilterApi()
    {
        // Intermediate operation
        // bool function(T)
        // Immutable operation

        $task1 = new Task("task 1");
        $task2 = (new Task("task 2"))->markDone();
        $this->elements->add($task1);
        $this->elements->add($task2);

        $filtered = $this->elements->filter(function (Task $e) {
            return $e->isDone();
        });

        self::assertNotEquals(spl_object_hash($this->elements), spl_object_hash($filtered));
        self::assertCount(2, $this->elements);
        self::assertCount(1, $filtered);

        /** @var Task $first */
        $first = $filtered->first();
        self::assertEquals("task 2", $first->getTitle());
    }

    public function testMapApi()
    {
        // Intermediate operation
        // R function(T)
        // Immutable operation

        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $this->elements->add($task1);
        $this->elements->add($task2);

        $mapped = $this->elements->map(function (Task $e) {
            return $e->getTitle();
        });

        echo $mapped;
        self::assertNotEquals(spl_object_hash($this->elements), spl_object_hash($mapped));
    }

    public function testReduceApi()
    {
        // Terminal operation
        // R function(N, T) - binary function
        // Immutable operation

        $task1 = new Task("task 1");
        $task2 = new Task("task 2");
        $this->elements->add($task1);
        $this->elements->add($task2);

        $reduced = $this->elements->reduce(function (string $carry, Task $e) {
            if (empty($carry)) {
                return $e->getTitle();
            }
            return "{$carry}|{$e->getTitle()}";
        }, "");

        echo $reduced;
        self::assertEquals("task 1|task 2", $reduced);
    }

    public function testAllInOne()
    {
        $task1 = new Task("task 1");
        $task3 = (new Task("task 3"))->markDone();
        $task2 = (new Task("task 2"))->markDone();
        $task4 = new Task("task 4");
        $this->elements->add($task1);
        $this->elements->add($task3);
        $this->elements->add($task2);
        $this->elements->add($task4);

        $processed = $this->elements
            ->filter(function (Task $e) {
                return $e->isDone();
            })
            ->sortBy(function (Task $e) {
                return $e->getTitle();
            })
            ->map(function (Task $e) {
                return $e->getTitle();
            })
            ->values();

        echo $processed;
        self::assertEquals(["task 2", "task 3"], $processed->all());

        // In Java8, the following do the same thing
        // this.elements.stream()
        //     .filter(t -> t.dueInToday())
        //     .sorted((t1, t2) -> t1.compareTo(t2))
        //     .map(t -> t.getTitle())
        //     .collect(toList());
    }

    public static function printCallback()
    {
        return function (Task $e) {
            echo $e;
        };
    }
}
