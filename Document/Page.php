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

use Symfony\Cmf\Bundle\ContentBundle\Document\MultilangStaticContent;
use Symfony\Cmf\Component\Routing\RouteAwareInterface;

/**
 * Page Document
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @todo refactor children and zone storing so everything is not loaded each time: use filter on children annotation ?
 *
 * @PHPCRODM\Document(referenceable=true, translator="attribute", repositoryClass="Presta\CMSCoreBundle\Document\Page\Repository")
 */
class Page extends MultilangStaticContent implements RouteAwareInterface
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $url;

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
    protected $children;

    public function __construct()
    {
        $this->isActive = true;
        $this->children = new ArrayCollection();
    }

    /**
     * Return Page id
     * used to flatten block
     *
     * @return string
     */
    public function getId()
    {
        return $this->getPath();
    }

    /**
     * Returns if page has children pages
     *
     * @return bool
     */
    public function hasChildren()
    {
        return (count($this->getChildren()) > 0);
    }

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
        return $this->children->filter(
            function ($e) {
                return $e instanceof Page;
            }
        );
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
        $this->children->add($child);
    }

    /**
     * Add a zone and initialize its id
     *
     * @return object
     */
    public function addZone($zone)
    {
        return $this->children->set($zone->getName(), $zone);
    }

    /**
     * Alias de getChildren
     *
     * @return object
     */
    public function getZones()
    {
        return $this->children->filter(
            function ($e) {
                return $e instanceof Zone;
            }
        );
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
    public function isActive()
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

    /**
     * @return bool|\Symfony\Component\Routing\Route
     */
    public function getRoute()
    {
        foreach ($this->getRoutes() as $route) {
            if ($route->getDefault('_locale') == $this->getLocale()) {
                return $route;
            }
        }

        return false;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return bool|string
     */
    public function getUrl()
    {
        if (!$this->url) {
            $route = $this->getRoute();
            if ($route != false) {
                $this->url = $route->getName();
            } else {
                $this->url = false;
            }
        }

        return $this->url;
    }
}
