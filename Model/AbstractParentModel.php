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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * This class handle children code
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractParentModel extends AbstractModel
{
    /**
     * @var Collection
     */
    protected $children;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->children = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (count($this->getChildren()) > 0);
    }

    /**
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren(Collection $children)
    {
        return $this->children = $children;
    }

    /**
     * Add a child to this container
     *
     * @param $child
     * @param string $key the collection index name to use in the
     *                    child collection. if not set, the child will simply be appended at
     *                    the end
     *
     * @return boolean
     */
    public function addChild($child, $key = null)
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
     * @param $children
     *
     * @return boolean
     */
    public function addChildren($children)
    {
        return $this->addChild($children);
    }

    /**
     * @param $child
     */
    public function removeChild($child)
    {
        $this->children->removeElement($child);
    }
}
