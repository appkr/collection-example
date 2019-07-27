<?php

namespace App;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    public function testCanPrintHtmlForComplexObject()
    {
        $task_r1 = new Task("task r1");
        $task_r2 = new Task("task r2");
        $project_r1 = new Project("project r1");
        $task_r1_1 = new Task("task r1_1");
        $task_r1_2 = new Task("task r1_2");

        $root = new Project("root");
            $root->add($task_r1);
            $root->add($task_r2);
            $root->add($project_r1);
                $project_r1->add($task_r1_1);
                $project_r1->add($task_r1_2);

        echo $root->toHtml();

        self::assertTrue(true);
    }
}
