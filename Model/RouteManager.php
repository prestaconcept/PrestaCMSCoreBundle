<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author David Epely <depely@prestaconcept.net>
 * @author Alain Flaus <aflaus@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Cmf\Component\Routing\RedirectRouteInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RedirectRoute;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Page;

/**
 * Description of RouteManager
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author David Epely <depely@prestaconcept.net>
 */
class RouteManager
{
    /**
     * @var ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var RouteProviderInterface
     */
    protected $routeProvider;

    /**
     * Setter
     * 
     * @param ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * Setter
     * 
     * @param RouteProviderInterface $routeProvider
     */
    public function setRouteProvider(RouteProviderInterface $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * Getter
     * 
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->modelManager->getDocumentManager();
    }

    /**
     * @param  \Presta\CMSCoreBundle\Document\Website $website
     * @return RouteCollection
     */
    public function findRoutesByWebsite(Website $website)
    {
        //Locale is in host only; then we list children for current locale
        $baseRoute = $this->routeProvider->getRouteByName($website->getRoutePrefix());

        if (!$baseRoute) {
            throw new \RuntimeException('Website must has a route');
        }

        return $this->getRouteCollectionForHierarchy($baseRoute);
    }

    /**
     * get routes recursively
     *
     * @param  Route           $route
     * @return RouteCollection
     */
    public function getRouteCollectionForHierarchy(Route $route)
    {
        $routeCollection = new RouteCollection();

        // SYMFONY 2.1 COMPATIBILITY: tweak route name
        $routeName = trim(preg_replace('/[^a-z0-9A-Z_.]/', '_', $route->getRouteKey()), '_');
        $routeCollection->add($routeName, $route);

        foreach ($route->getRouteChildren() as $child) {
            //route cannot be other than RouteObjectInterface
            if ($child instanceof Route) {
                $routeCollection->addCollection($this->getRouteCollectionForHierarchy($child));
            }
        }

        return $routeCollection;
    }

    /**
     * Update page routing
     * 
     * @param  Page   $page
     */
    public function updatePageRouting(Page $page)
    {
        $mainRoute = $this->getRouteForPage($page);
        
        // if page url has change
        if ($page->getUrl() != $mainRoute->getName()) {

            // search previous redirect route with same name exist, remove it
            $previousRedirectRoute = $this->getMatchingRedirectRouteForPage($page);
            if (!is_null($previousRedirectRoute)) {
                $this->getDocumentManager()->remove($previousRedirectRoute);

                // move page
                $this->getDocumentManager()->move($mainRoute, self::generateNewPath($mainRoute, $page->getUrl()));

            } else {
                // list redirect route to create them with current url after move
                $redirectRoutes = array();
                $this->indexRedirectRouteToCreate($redirectRoutes, $mainRoute);

                // move page
                $this->getDocumentManager()->move($mainRoute, self::generateNewPath($mainRoute, $page->getUrl()));

                // persist redirect
                foreach ($redirectRoutes as $redirectRoute) {
                    $this->getDocumentManager()->persist($redirectRoute);
                }
            }

            $this->getDocumentManager()->flush();
        }
    }

    /**
     * Update current route with new page url
     * 
     * @param  RouteObjectInterface $mainRoute
     * @param  string $newUrl
     */
    static public function generateNewPath(RouteObjectInterface $mainRoute, $newUrl)
    {
        return str_replace($mainRoute->getName(), $newUrl, $mainRoute->getId());
    }

    /**
     * Generate redirect route for a route and all its children
     *
     * @param  array                    $redirectRoutes
     * @param  RouteObjectInterface     $route
     */
    public function indexRedirectRouteToCreate(array &$redirectRoutes, RouteObjectInterface $route)
    {
        // create new redirect route for old url
        $redirectRoute = new RedirectRoute();
        $redirectRoute->setRouteName($route->getName());
        $redirectRoute->setRouteTarget($route);

        foreach ($route->getRouteChildren() as $routeChild) {
            $this->indexRedirectRouteToCreate($redirectRoutes, $routeChild);
        }

        $redirectRoutes[] = $redirectRoute;
    }

    /**
     * Return page route
     * 
     * @param  Page     $page
     * @param  string   $locale
     * @return RouteObjectInterface|null
     */
    public function getRouteForPage(Page $page, $locale = null)
    {
        $route = null;

        if (is_null($locale)) {
            $locale = $page->getLocale();
        }

        foreach ($page->getRoutes() as $pageRoute) {
            if ($pageRoute->getDefault('_locale') == $locale) {
                $route = $pageRoute;
            }
        }

        return $route;
    }

    /**
     * Return all redirect routes for a page
     * 
     * @param  Page $page
     * @return RouteCollection $redirectRoutes
     */
    public function getRedirectRouteForPage(Page $page)
    {
        $redirectRoutes = new RouteCollection();

        // get all RedirectRoute for current page
        foreach ($page->getRoutes() as $route) {
            if ($route instanceof RedirectRouteInterface) {
                $redirectRoutes->add($route->getRouteName(), $route);
            }
        }

        return $redirectRoutes;
    }

    /**
     * Return matching redirectRoute with current page url
     * 
     * @param  Page   $page
     * @return RouteObjectInterface|null
     */
    public function getMatchingRedirectRouteForPage(Page $page)
    {
        $redirectRoutes = $this->getRedirectRouteForPage($page);

        return $redirectRoutes->get($page->getUrl());
    }
}
