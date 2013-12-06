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

use Doctrine\Common\Collections\Collection;
use Knp\Menu\NodeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Presta\CMSCoreBundle\Model\Zone;
use Symfony\Component\Routing\Route;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Page extends AbstractParentModel
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';

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
     * This is not store in database, it's used to pass data form the form to the menu
     * @var string
     */
    protected $menuLabel;

    /**
     * This is not store in database, it's used to pass data form the form to the menu
     * @var string
     */
    protected $menuId;

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
     * @var string $template
     */
    protected $template;

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

    /**
     * @var bool
     */
    protected $cachePrivate = false;

    /**
     * @var int
     */
    protected $cacheMaxAge = 0;

    /**
     * @var int
     */
    protected $cacheSharedMaxAge = 0;

    /**
     * @var bool
     */
    protected $cacheMustRevalidate = false;

    public function __construct()
    {
        parent::__construct();

        $this->lastCacheModifiedDate = new \DateTime();

        $this->routes = new ArrayCollection();
        $this->menus  = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
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
     * As page can have different types of children, we filter on page
     *
     * This is used in forms
     *
     * @return Collection
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
     * Add a zone and initialize its id
     *
     * @param Zone $zone
     */
    public function addZone(Zone $zone)
    {
        $this->children->set($zone->getName(), $zone);
    }

    /**
     * @return Collection
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
        $this->urlRelative = rtrim($urlRelative, '/');
    }

    /**
     * @return string
     */
    public function getPathComplete()
    {
        return (string)$this->pathComplete;
    }

    /**
     * @param string $pathComplete
     */
    public function setPathComplete($pathComplete)
    {
        if (strpos($pathComplete, '/') !== 0) {
            $pathComplete = '/' . $pathComplete;
        }
        $this->pathComplete = $pathComplete;
    }

    /**
     * @return string
     */
    public function getUrlRelative()
    {
        return (string)$this->urlRelative;
    }

    /**
     * @param string $urlComplete
     */
    public function setUrlComplete($urlComplete)
    {
        if (strpos($urlComplete, '/') !== 0) {
            $urlComplete = '/' . $urlComplete;
        }
        $this->urlComplete = rtrim($urlComplete, '/');
    }

    /**
     * @return string
     */
    public function getUrlComplete()
    {
        return (string)$this->urlComplete;
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
     * @return string
     */
    public function getMenuLabel()
    {
        return $this->menuLabel;
    }

    /**
     * @param string $menuLabel
     */
    public function setMenuLabel($menuLabel)
    {
        $this->menuLabel = $menuLabel;
    }

    /**
     * @return string
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * @param string $menuId
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;
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
     * @return Route[] Route instances that point to this content
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

    /**
     * @param boolean $cachePrivate
     */
    public function setCachePrivate($cachePrivate)
    {
        $this->cachePrivate = $cachePrivate;
    }

    /**
     * @return boolean
     */
    public function getCachePrivate()
    {
        return $this->cachePrivate;
    }

    /**
     * @param int $cacheMaxAge
     */
    public function setCacheMaxAge($cacheMaxAge)
    {
        $this->cacheMaxAge = $cacheMaxAge;
    }

    /**
     * @return int
     */
    public function getCacheMaxAge()
    {
        return $this->cacheMaxAge;
    }

    /**
     * @param int $cacheSharedMaxAge
     */
    public function setCacheSharedMaxAge($cacheSharedMaxAge)
    {
        $this->cacheSharedMaxAge = $cacheSharedMaxAge;
    }

    /**
     * @return int
     */
    public function getCacheSharedMaxAge()
    {
        if ($this->getCachePrivate()) {
            //Share max age is only for public response
            return 0;
        }

        return $this->cacheSharedMaxAge;
    }

    /**
     * @param boolean $cacheMustRevalidate
     */
    public function setCacheMustRevalidate($cacheMustRevalidate)
    {
        $this->cacheMustRevalidate = $cacheMustRevalidate;
    }

    /**
     * @return boolean
     */
    public function getCacheMustRevalidate()
    {
        return $this->cacheMustRevalidate;
    }

    /**
     * To clear the front cache, we just need to update the LastCacheModifiedDate of the page
     * Front cache validation noticed that cache should be recomputed
     */
    public function clearCache()
    {
        $this->lastCacheModifiedDate = new \DateTime();
    }
}
