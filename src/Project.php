<?php

namespace App;

use Illuminate\Support\Collection;

class Project
{
    const MAX_ELEMENTS = 3;

    /** @var Collection|Task[] */
    private $elements;

    public function __construct(Collection $elements = null)
    {
        if ($elements != null && $elements->count() > self::MAX_ELEMENTS) {
            throw new \InvalidArgumentException("Capacity full");
        }
        $this->elements = $elements ?: new Collection();
    }

    public function getElements()
    {
        return new Collection($this->elements->all());
    }

    public function add(Task $element)
    {
        if (! $this->canAddElement()) {
            throw new \InvalidArgumentException("Capacity full");
        }
        $this->elements->add($element);
    }

    public function remove(Task $element)
    {
        $index = $this->find($element);
        if ($index !== false) {
            $this->elements->forget($index);
            $this->elements = $this->elements->values();
        }
    }

    public function markDone()
    {
        $this->elements->each(function (Task $e) {
            $e->markDone();
        });
    }

    public function __toString()
    {
        return $this->elements->reduce(function (string $carry, Task $e) {
            return $carry . (string) $e . PHP_EOL;
        }, "");
    }

    private function find(Task $element) {
        return $this->elements->search(function (Task $task) use ($element) {
            return $task->hashCode() == $element->hashCode();
        });
    }

    private function canAddElement() {
        return $this->elements->count() < self::MAX_ELEMENTS;
    }
}