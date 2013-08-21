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

use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Cmf\Component\Routing\RedirectRouteInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RedirectRoute;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

//use Presta\CMSCoreBundle\Doctrine\Phpcr\Page;

/**
 * Description of RouteManager
 *
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
     * @var string
     */
    protected $baseUrl;

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
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
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
     * @param  Website $website
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
     * Update page routing for a complete url
     *
     * @param Page $page
     */
    protected function updatePageRoutingUrlComplete(Page $page)
    {
        $pageRoute      = $this->getRouteForPage($page);
        $newRoutePath   = $pageRoute->getPrefix() . $page->getUrlComplete();

        if ($pageRoute->getId() == $newRoutePath) {
            //Url didn't change
            return;
        }

        //Check if parent route exists
        $parentUrl  = substr($page->getUrlComplete(), 0, strrpos($page->getUrlComplete(), '/'));
        $parentPath = $pageRoute->getPrefix() . $parentUrl;

        $newRouteParent = $this->getDocumentManager()->find(null, $parentPath);
        if ($newRouteParent == null) {
            //Create new route parent
            $session = $this->getDocumentManager()->getPhpcrSession();
            NodeHelper::createPath($session, $parentPath);
        }

        return $this->moveRoute($pageRoute, $newRoutePath);
    }

    /**
     * Update page routing for a relative url
     *
     * @param  Page   $page
     */
    protected function updatePageRoutingUrlRelative(Page $page)
    {
        $pageRoute      = $this->getRouteForPage($page);
        $parentRoute    = $pageRoute->getParent();
        $newRoutePath   = $parentRoute->getId() . $page->getUrlRelative();

        if ($pageRoute->getId() == $newRoutePath) {
            //Url didn't change
            return;
        }

        return $this->moveRoute($pageRoute, $newRoutePath);
    }

    /**
     * Generate the list of redirection
     *
     * @param  Route $oldRoute
     * @param  $newRouteUrl
     * @return array
     */
    protected function generateRedirectionList(Route $oldRoute, $newRouteUrl)
    {
        $redirectionList = array();
        $redirectionList[$oldRoute->getId()] = $newRouteUrl;

        foreach ($oldRoute->getRouteChildren() as $oldRouteChild) {
            $redirectionList += $this->generateRedirectionList($oldRouteChild, $newRouteUrl . '/' . $oldRouteChild->getName());
        }

        return $redirectionList;
    }

    /**
     * Generate RedirectRoutes for the redirections
     *
     * @param array $redirectionList
     */
    protected function generateRedirections(array $redirectionList)
    {
        //Not working : right now DoctrineDBAL Client throws an exception cause it considers that there is node duplication
        //DocumentManager::clear does not solve the problem
        return;

        foreach ($redirectionList as $oldId => $newUrl) {
            $redirectRoute = new RedirectRoute();
            $redirectRoute->setId($oldId);
            $redirectRoute->setUri($newUrl);
            $this->getDocumentManager()->persist($redirectRoute);
        }

        $this->getDocumentManager()->flush();
    }

    /**
     * Move an existing route to a new path
     *
     * @param Route  $oldRoute
     * @param string $newRoutePath
     */
    protected function moveRoute(Route $oldRoute, $newRoutePath)
    {
        //Check if redirect already exists
        $oldRedirect = $this->getDocumentManager()->find('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RedirectRoute', $newRoutePath);
        if ($oldRedirect != null) {
            $this->getDocumentManager()->remove($oldRedirect);
            $this->getDocumentManager()->flush();
        }
        $newRouteUrl = $this->getBaseUrl() . str_replace($oldRoute->getPrefix(), '', $newRoutePath);
        $redirectionList = $this->generateRedirectionList($oldRoute, $newRouteUrl);

        //Create new route
        $this->getDocumentManager()->move($oldRoute, $newRoutePath);
        $this->getDocumentManager()->flush();

        //Now old route is moved so we can persist the new redirects
        $this->generateRedirections($redirectionList);
    }

    /**
     * Update page routing
     * 
     * @param  Page   $page
     */
    public function updatePageRouting(Page $page)
    {
        if (!$page->hasRoutingData()) {
            return;
        }
        if ($page->isUrlCompleteMode()) {
            return $this->updatePageRoutingUrlComplete($page);
        }

        return $this->updatePageRoutingUrlRelative($page);
    }

    /**
     * Create route for a new page
     *
     * @param  Page $page
     * @return Route
     */
    public function createPageRouting(Page $page)
    {
        $route = new Route();

        $parentPage = $page->getParent();
        if ($parentPage instanceof Page) {
            $parentRoute = $this->getRouteForPage($parentPage);
            $route->setPosition($parentRoute, $page->getName());
        } else {
            //if page is not a child, its routing is under website root node
            $id = str_replace(Website::PAGE_PREFIX, Website::ROUTE_PREFIX, $page->getId());
            $route->setId($id, $page->getName());
        }

        $route->setDefault('_locale', $page->getLocale());
        $route->setRequirement('_locale', $page->getLocale());
        $route->setRouteContent($page);

        $this->getDocumentManager()->persist($route);
        $this->getDocumentManager()->flush();

        return $route;
    }

    /**
     * Generate new route path
     * 
     * @param  RouteObjectInterface $mainRoute
     * @param  string $newUrl
     */
    static public function generateNewPath(RouteObjectInterface $mainRoute, $newUrl)
    {
        return str_replace($mainRoute->getName(), $newUrl, $mainRoute->getId());
    }

    /**
     * Create redirect route for a route and all its children
     *
     * @param  array                    $redirectRoutes
     * @param  RouteObjectInterface     $route
     */
    public function createRedirectRoute(RouteObjectInterface $route, $parent = null)
    {
        // create new redirect route for old url
        $redirectRoute = new RedirectRoute();
        $redirectRoute->setPosition($parent, $route->getName());
        $redirectRoute->setRouteTarget($route);

        $this->getDocumentManager()->persist($redirectRoute);

        foreach ($route->getRouteChildren() as $routeChild) {
            $this->createRedirectRoute($routeChild, $redirectRoute);
        }

        return $redirectRoute;
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

        $mainRoute  = $this->getRouteForPage($page);
        $referrers  = $this->getDocumentManager()->getReferrers($mainRoute);

        // get all RedirectRoute for current page
        foreach ($referrers as $route) {
            if ($route instanceof RedirectRouteInterface) {
                $redirectRoutes->add(str_replace('-', '_', $route->getName()), $route);
            }
        }

        return $redirectRoutes;
    }
}
