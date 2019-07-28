<?php
/**
 * For association
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/association-mapping.html#association-mapping
 *
 * For cascading
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/working-with-associations.html#working-with-associations
 */

namespace App\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="todos")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string", length=40)
 * @DiscriminatorMap({"PROJECT" = "Project", "TASK" = "Task"})
 */
abstract class Todo
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $title;

    /**
     * @Column(name="isDone", type="boolean")
     * @var boolean
     */
    protected $isDone;

    /**
     * @OneToMany(targetEntity="Todo", mappedBy="project", cascade={"persist", "remove"})
     * @var ArrayCollection|Todo[]
     */
    protected $elements;

    /**
     * @ManyToOne(targetEntity="Todo", inversedBy="elements", cascade={"persist", "remove"})
     * @JoinColumn(name="parentId", referencedColumnName="id")
     */
    protected $parent;

    protected function __construct(string $title)
    {
        $this->title = $title;
        $this->isDone = false;
        $this->elements = new ArrayCollection();
    }

    public static function of(string $title)
    {
        return new static($title);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function isDone()
    {
        return $this->isDone;
    }

    public function markDone()
    {
        if (! $this->elements->isEmpty()) {
            /** @var Todo $e */
            foreach ($this->elements->getIterator() as $e) {
                $e->markDone();
            }
        }

        $this->isDone = true;
    }

    public function getElements()
    {
        $cloned = new ArrayCollection();
        if ($this->elements->isEmpty()) {
            return $cloned;
        }

        /** @var Todo $e */
        foreach ($this->elements->getIterator() as $e) {
            $cloned->add(clone $e);
        }

        return $cloned;
    }

    public function addElement(Todo $element)
    {
        $element->setParent($this);
        $this->elements->add($element);
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
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
        $html = "<li><a href=\"{$this->getId()}\">{$this->getTitle()}</a></li>";
        if (! $this->elements->isEmpty()) {
            $html .= "<ul>";
            /** @var Todo $e */
            foreach ($this->elements->getIterator() as $e) {
                $html .= $e->toHtml();
            }
            $html .="</ul>";
        }

        return $html;
    }
}
