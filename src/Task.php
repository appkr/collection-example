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
        $done = $this->isDone ? "true" : "false";

        return <<<EOT
Task {
    name: {$this->title},
    isDone: {$done}
}
EOT;
    }

    public function toHtml()
    {
        return "<li><a href='{$this->hashCode()}'>{$this->title}</a></li>";
    }
}