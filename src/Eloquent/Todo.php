<?php
/**
 * For SingleTableInheritance
 * @see https://github.com/Nanigans/single-table-inheritance
 */

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

/**
 * Class Project
 * @package App\Eloquent
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property boolean $isDone
 * @property Collection|Todo[] $elements
 * @property Todo $parent
 */
class Todo extends Model
{
    use SingleTableInheritanceTrait;

    public $timestamps = false;

    protected $table = "todos";
    protected static $singleTableTypeField = "type";
    protected static $singleTableSubclasses = [Project::class, Task::class];

    protected $casts = [
        "isDone" => "boolean",
    ];

    public static function of(string $title)
    {
        $instance = new static();
        $instance->title = $title;
        $instance->isDone = false;
        $instance->type = static::$singleTableType;

        return $instance;
    }

    public function elements()
    {
        return $this->hasMany(Todo::class, "parentId", "id");
    }

    public function parent()
    {
        return $this->belongsTo(Todo::class, "parentId", "id");
    }

    public function getElements()
    {
        return new Collection($this->elements->all());
    }

    public function addElement(Todo $element)
    {
        $element->parent()->associate($this)->save();
    }

    public function removeElement(Todo $element)
    {
        $element->parent()->dissociate()->save();
    }

    public function getParent()
    {
        if ($this->parent == null) {
            return new static();
        }

        return clone $this->parent;
    }

    public function markDone()
    {
        if ($this->elements->isNotEmpty()) {
            $this->elements->each(function (Todo $task) {
                $task->markDone();
            });
        }

        $this->isDone = true;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function isDone()
    {
        return $this->isDone;
    }

    public function __toString()
    {
        $isDone = $this->isDone ? "true" : "false";

        $string = static::class . " {\n";
        $string .= "    id: {$this->id}\n";
        $string .= "    title: {$this->title}\n";
        $string .= "    isDone: {$isDone}\n";
        $string .= "}";

        return $string;
    }

    public function toHtml()
    {
        $html = "<li><a href=\"{$this->getKey()}\">{$this->getTitle()}</a></li>";

        if ($this->elements->isNotEmpty()) {
            $html .= "<ul>";
            $html .= $this->elements->reduce(function (string $carry, Todo $e) {
                return $carry . $e->toHtml();
            }, "");
            $html .="</ul>";
        }

        return $html;
    }
}
