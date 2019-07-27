<?php

namespace App;

class Task
{
    private $title;
    private $isDone;

    public function __construct($title)
    {
        $this->title = $title;
        $this->isDone = false;
    }

    public function markDone()
    {
        $this->isDone = true;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function isDone()
    {
        return $this->isDone;
    }

    public function hashCode()
    {
        return spl_object_hash($this);
    }

    public function __toString()
    {
        $isDone = $this->isDone ? "true" : "false";

        $string = static::class . " {\n";
        $string .= "    title: {$this->title}\n";
        $string .= "    isDone: {$isDone}\n";
        $string .= "}";

        return $string;
    }
}