<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

/**
 * This class centralize basic model code
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractModel
{
    /**
     * Primary identifier, details depend on storage layer.
     */
    protected $id;

    /**
     * @var AbstractModel
     */
    protected $parent;

    /**
     * @var boolean $enabled
     */
    protected $enabled = true;

    /**
     * @var string
     */
    protected $name;

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
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

    /**
     * @param AbstractModel $parent
     */
    public function setParent(AbstractModel $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return AbstractModel
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
