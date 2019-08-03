<?php

namespace App\Eloquent;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

class EloquentTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::bootEloquent();
    }

    protected static function bootEloquent()
    {
        $DB_PATH = __DIR__ . "/../../database.sqlite";
        $TABLE_NAME = "todos";

        // Create database file, if not exists.
        if (! file_exists($DB_PATH)) {
            file_put_contents($DB_PATH, null);
        }

        // Boot Eloquent
        $capsuleManager = new Manager();
        $capsuleManager->addConnection([
            "driver"   => "sqlite",
            "database" => $DB_PATH,
        ]);
        $capsuleManager->setAsGlobal();
        $capsuleManager->bootEloquent();

        // Create table, if not exists.
        if (! Manager::schema()->hasTable($TABLE_NAME)) {
            Manager::schema()->create($TABLE_NAME, function (Blueprint $t) {
                $t->increments("id");
                $t->string("title");
                $t->boolean("isDone")->default(false);
                $t->unsignedInteger("parentId")->nullable();
                $t->string("type", 100)->nullable();
            });
        }
    }
}
