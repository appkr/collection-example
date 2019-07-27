<?php

namespace App;

use Illuminate\Support\Collection;

class Project extends Todo
{
    const MAX_ELEMENTS = 3;

    /** @var Collection|Todo[] */
    private $elements;

    public function __construct(string $title, Collection $elements = null)
    {
        if ($elements != null && $elements->count() > self::MAX_ELEMENTS) {
            throw new \InvalidArgumentException("Capacity full");
        }
        $this->elements = $elements ?: new Collection();
        parent::__construct($title);
    }

    public function getElements()
    {
        return new Collection($this->elements->all());
    }

    public function add(Todo $element)
    {
        if (! $this->canAddElement()) {
            throw new \InvalidArgumentException("Capacity full");
        }
        $this->elements->add($element);
    }

    public function remove(Todo $element)
    {
        $index = $this->find($element);
        if ($index !== false) {
            $this->elements->forget($index);
            $this->elements = $this->elements->values();
        }
    }

    public function markDone()
    {
        $this->elements->each(function (Todo $e) {
            $e->markDone();
        });
    }

    public function __toString()
    {
        return $this->elements->reduce(function (string $carry, Todo $e) {
            return $carry . (string) $e . PHP_EOL;
        }, "");
    }

    public function toHtml()
    {
        $html = "<li><a href='{$this->hashCode()}'>{$this->title}</a></li>";
        if ($this->elements->isNotEmpty()) {
            $html .= "<ul>";
            $html .= $this->elements->reduce(function (string $carry, Todo $e) {
                return $carry . (string) $e->toHtml();
            }, "");
            $html .="</ul>";
        }

        return $html;
    }

    private function find(Todo $element) {
        return $this->elements->search(function (Todo $e) use ($element) {
            return $e->hashCode() == $element->hashCode();
        });
    }

    private function canAddElement() {
        return $this->elements->count() < self::MAX_ELEMENTS;
    }
}