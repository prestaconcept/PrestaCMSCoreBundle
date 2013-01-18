<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Cmf\Bundle\ContentBundle\Document\MultilangStaticContent;
use Symfony\Cmf\Component\Routing\RouteAwareInterface;

/**
 * Page Document
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true, translator="attribute", repositoryClass="Presta\CMSCoreBundle\Document\Page\Repository")
 */
class Page extends MultilangStaticContent implements RouteAwareInterface
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';

    /**
     * @var string $metaKeywords
     * @PHPCRODM\String(translated=true)
     */
    protected $metaKeywords;

    /**
     * @var string $metaDescription
     * @PHPCRODM\String(translated=true)
     */
    protected $metaDescription;

    /**
     * @var string $type
     * @PHPCRODM\String(translated=true)
     */
    protected $type;

    /**
     * @var string $type
     * @PHPCRODM\String(translated=true)
     */
    protected $status;

    /**
     * @var boolean $isActive
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $isActive;

    /**
     * @var string $template
     * @PHPCRODM\String(translated=true)
     */
    protected $template;

    /**
     * @PHPCRODM\Children(fetchDepth=3)
     */
    protected  $children;

    /**
     * The children documents of this document
     *
     * If there is information on the document type, the documents are of the
     * specified type, otherwise they will be Generic documents
     *
     * @return object documents
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the children
     *
     * @param $children ArrayCollection
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }

    /**
     * Add a child to this document
     *
     * @param $child
     */
    public function addChild($child)
    {
        if (null === $this->children) {
            $this->children = new ArrayCollection();
        }

        $this->children->add($child);
    }

    /**
     * Add a zone and initialize its id
     *
     * @return object
     */
    public function addZone($zone)
    {
        if (null === $this->children) {
            $this->children = new ArrayCollection();
        }

        return $this->children->set($zone->getName(), $zone);
    }

    /**
     * Alias de getChildren
     *
     * @return object
     */
    public function getZones()
    {
        return $this->getChildren();
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Return Children page description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Page
     */
    public function getRouteContent()
    {
        return $this;
    }

    public function getUrl()
    {
        //todo prendre en compte la locale
        return $this->getRoutes()->first()->getName();
    }
}