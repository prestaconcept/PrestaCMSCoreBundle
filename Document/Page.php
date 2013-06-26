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
    protected $urlRelative;

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $pathComplete;

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $urlComplete;

    /**
     * @var boolean $isUrlCompleteMode
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $isUrlCompleteMode;

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

    /**
     * @PHPCRODM\Date()
     */
    protected $lastCacheModifiedDate;

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
     * @param string $urlRelative
     */
    public function setUrlRelative($urlRelative)
    {
        if (strpos($urlRelative, '/') !== 0) {
            $urlRelative = '/' . $urlRelative;
        }
        $this->urlRelative = $urlRelative;
    }

    /**
     * @return string
     */
    public function getPathComplete()
    {
        return (isset($this->pathComplete)) ? $this->pathComplete : null;
    }

    /**
     * @param string $pathComplete
     */
    public function setPathComplete($pathComplete)
    {
        $this->pathComplete = $pathComplete;
    }

    /**
     * @return string
     */
    public function getUrlRelative()
    {
        return (isset($this->urlRelative)) ? $this->urlRelative : null;
    }

    /**
     * @param string $urlComplete
     */
    public function setUrlComplete($urlComplete)
    {
        if (strpos($urlComplete, '/') !== 0) {
            $urlComplete = '/' . $urlComplete;
        }
        $this->urlComplete = $urlComplete;
    }

    /**
     * @return string
     */
    public function getUrlComplete()
    {
        return (isset($this->urlComplete)) ? $this->urlComplete : null;
    }

    /**
     * @param boolean $isUrlCompleteMode
     */
    public function setIsUrlCompleteMode($isUrlCompleteMode)
    {
        $this->isUrlCompleteMode = $isUrlCompleteMode;
    }

    /**
     * @return boolean
     */
    public function isUrlCompleteMode()
    {
        return (bool)$this->isUrlCompleteMode;
    }

    /**
     * @param Date $lastCacheModifiedDate
     */
    public function setLastCacheModifiedDate($lastCacheModifiedDate)
    {
        $this->lastCacheModifiedDate = $lastCacheModifiedDate;
    }

    /**
     * @return Date
     */
    public function getLastCacheModifiedDate()
    {
        return $this->lastCacheModifiedDate;
    }
}
