<?php

namespace App\Eloquent;

class TodoTest extends EloquentTestCase
{
    public function testCompositeConstruction()
    {
        $project = Project::of("root project");
            $task1 = Task::of("task 1");
            $task2 = Task::of("task 2");
            $project1 = Project::of("project 1");
                $task1_1 = Task::of("task 1-1");
                $project1_1 = Project::of("project 1-1");
        $project->save();

        $project->addElement($task1);
        $project->addElement($task2);
        $project->addElement($project1);
        $project1->addElement($task1_1);
        $project1->addElement($project1_1);
        $project->markDone();
        $project->push();

        /** @var Project $project */
        $project = Project::find($project->getKey());

        echo $project, PHP_EOL;
        echo $project->toHtml(), PHP_EOL;
        self::assertNotNull($project);
    }
}
