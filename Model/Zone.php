<?php

namespace Presta\CMSCoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\ChildrenCollection;
use Sonata\BlockBundle\Model\BlockInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Zone
{
    /**
     * Primary identifier, details depend on storage layer.
     */
    protected $id;

    /**
     * PHPCR parent document
     *
     * @var string
     */
    protected $parent;

    /**
     * PHPCR document name
     *
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $cols;

    /**
     * @var integer
     */
    protected $rows;

    /**
     * @var boolean
     */
    protected $editable = true;

    /**
     * @var boolean
     */
    protected $sortable = false;

    /**
     * @var ArrayCollection
     */
    protected $_blocks;

    public function __construct($name = null)
    {
        $this->setName($name);
        $this->children = new ArrayCollection();
    }

    /**
     * Explicitly set the primary id, if the storage layer permits this.
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Initialise form configuration
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $configuration += array(
            'rows'          => 1,
            'cols'          => 12,
            'is_editable'   => false,
            'is_sortable'   => false
        );
        $this->setRows($configuration['rows']);
        $this->setCols($configuration['cols']);
        $this->setEditable($configuration['is_editable']);
        $this->setSortable($configuration['is_sortable']);
    }

    /**
     * Return id for HTML element
     *
     * @return string
     */
    public function getHtmlId()
    {
        return str_replace(array('.', '_', '/'), '', $this->getId());
    }

    /**
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }

    /**
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param boolean $editable
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * @param boolean $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    public function getBlocks()
    {
        return $this->getChildren();
    }

    public function addBlock($block)
    {
        $this->addChild($block);
    }

    public function setBlocks($blocks)
    {
        $this->children = $blocks;
    }

    /**
     * @var ChildrenCollection
     */
    protected $children;

    /**
     * Get children
     *
     * @return ArrayCollection|ChildrenCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param ChildrenCollection $children
     *
     * @return ChildrenCollection
     */
    public function setChildren(ChildrenCollection $children)
    {
        return $this->children = $children;
    }

    /**
     * Add a child to this container
     *
     * @param BlockInterface $child
     * @param string         $key   the collection index name to use in the
     *      child collection. if not set, the child will simply be appended at
     *      the end
     *
     * @return boolean
     */
    public function addChild(BlockInterface $child, $key = null)
    {
        if ($key != null) {

            $this->children->set($key, $child);

            return true;
        }

        return $this->children->add($child);
    }

    /**
     * Alias to addChild to make the form layer happy
     *
     * @param BlockInterface $children
     *
     * @return boolean
     */
    public function addChildren(BlockInterface $children)
    {
        return $this->addChild($children);
    }

    /**
     * Remove a child from this container
     *
     * @param  BlockInterface $child
     * @return void
     */
    public function removeChild($child)
    {
        $this->children->removeElement($child);
    }
}
