<?php

namespace App;

abstract class Todo
{
    protected $title;
    protected $isDone;

    public function __construct($title)
    {
        $this->title = $title;
        $this->isDone = false;
    }

    abstract public function markDone();

    abstract public function toHtml();

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
}