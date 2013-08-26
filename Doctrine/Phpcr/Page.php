<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Doctrine\Phpcr;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ODM\PHPCR\ChildrenCollection;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Knp\Menu\NodeInterface;
use Presta\CMSCoreBundle\Model\Page as PageModel;

/**
 * Page Document
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @todo refactor children and zone storing so everything is not loaded each time: use filter on children annotation ?
 *
 */
class Page extends PageModel implements
    MenuNodeReferrersInterface,
    RouteReferrersInterface,
    TranslatableInterface
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';

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
     * @var string
     */
    protected $title;

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
     * @var boolean $urlCompleteMode
     */
    protected $urlCompleteMode;

    /**
     * @var string $metaKeywords
     */
    protected $metaKeywords;

    /**
     * @var string $metaDescription
     */
    protected $metaDescription;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $status
     */
    protected $status = self::STATUS_PUBLISHED;

    /**
     *  @var string
     */
    protected $locale;

    /**
     * @var boolean $isActive
     */
    protected $isActive;

    /**
     * @var string $template
     */
    protected $template;

    /**
     * @var ChildrenCollection
     */
    protected $children;

    /**
     * @var RouteObjectInterface[]
     */
    protected $routes;

    /**
     * MenuNode[]
     */
    protected $menuNodes;

    /**
     * @var Date
     */
    protected $lastCacheModifiedDate;

    public function __construct()
    {
        $this->isActive = true;
        $this->children = new ArrayCollection();

        $this->routes = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
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
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
        if (count($this->children) == 0) {
            return $this->children;
        }

        return $this->children->filter(
            function ($e) {
                return $e instanceof Page;
            }
        );
    }

    /**
     * Sets the children
     *
     * @param $children ChildrenCollection
     */
    public function setChildren(ChildrenCollection $children)
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
        if (count($this->children) == 0) {
            return $this->children;
        }

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
     * @param boolean $urlCompleteMode
     */
    public function setUrlCompleteMode($isUrlCompleteMode)
    {
        $this->urlCompleteMode = $isUrlCompleteMode;
    }

    /**
     * @return boolean
     */
    public function isUrlCompleteMode()
    {
        return (bool) $this->urlCompleteMode;
    }

    /**
     * Check is page has routing data, used when update the routes in EventListener
     *
     * @return boolean
     */
    public function hasRoutingData()
    {
        return (isset($this->urlComplete) || isset($this->urlRelative));
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

    /**
     * @param Route $route
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
    }

    /**
     * @param Route $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * @return \Symfony\Component\Routing\Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param MenuNode $menu
     */
    public function addMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->add($menu);
    }

    /**
     * @param MenuNode $menu
     */
    public function removeMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->removeElement($menu);
    }

    /**
     * @return ArrayCollection of MenuNode that point to this content
     */
    public function getMenuNodes()
    {
        return $this->menuNodes;
    }
}
