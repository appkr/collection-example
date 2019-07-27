<?php

namespace App;

class Task extends Todo
{
    public function markDone()
    {
        $this->isDone = true;
        return $this;
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

    public function toHtml()
    {
        return "<li><a href='{$this->hashCode()}'>{$this->title}</a></li>";
    }
}