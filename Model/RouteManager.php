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

use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Cmf\Component\Routing\RedirectRouteInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RedirectRoute;
use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * @author David Epely <depely@prestaconcept.net>
 * @author Alain Flaus <aflaus@prestaconcept.net>
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
     * @param ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
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
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->modelManager->getDocumentManager();
    }

    /**
     * Returns all routes corresponding to a website
     *
     * @param  Website $website
     * @return array
     */
    public function getRoutesForWebsite(Website $website)
    {
        $qb = $this->getDocumentManager()->createQueryBuilder();
        $qb->from()->document('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route', 'r');
        //Waiting on QueryBuilderV2 to use this
        //$qb->where()->like()->field('r.id')->literal($website->getRoutePrefix() . '%');
        $qb->orderBy()->ascending()->field('r.id');

        $results = $qb->getQuery()->execute();

        $routes = array();
        foreach ($results as $route) {
            if (strpos($route->getId(), $website->getRoutePrefix()) === 0) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

    /**
     * Initialize page routing data based on url mode
     *
     * @param Page $page
     *
     * @return Page
     */
    public function initializePageRouting(Page $page)
    {
        $correspondingRoute = $this->getRouteForPage($page, $page->getLocale());

        if ($correspondingRoute->getPrefix() == $correspondingRoute->getId()) {
            //homepage case
            $page->setUrlRelative('');
            $page->setPathComplete('');
            $page->setUrlComplete('');
        } else {
            $page->setUrlRelative($correspondingRoute->getName());

            $page->setPathComplete(
                str_replace($correspondingRoute->getPrefix(), '', $correspondingRoute->getParent()->getId() . '/')
            );

            $page->setUrlComplete(
                str_replace($correspondingRoute->getPrefix(), '', $correspondingRoute->getId())
            );
        }

        return $page;
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
     * @param Page $page
     */
    protected function updatePageRoutingUrlRelative(Page $page)
    {
        $parentRoute    = $this->getRouteForPage($page->getParent());
        $pageRoute      = $this->getRouteForPage($page);
        $newRoutePath   = $parentRoute->getId() . $page->getUrlRelative();

        if ($pageRoute->getId() == $newRoutePath) {
            //Url didn't change
            return;
        }

        return $this->moveRoute($pageRoute, $newRoutePath);
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
        $correspondingUrls = $this->getCorrespondingUrls($oldRoute, $newRouteUrl);

        //Create new route
        $this->getDocumentManager()->move($oldRoute, $newRoutePath);
        $this->getDocumentManager()->flush();
        $this->getDocumentManager()->clear();

        //Now old route is moved so we can persist the new redirects
        //        $this->generateRedirections($redirectionList);
    }

    /**
     * Update page routing
     *
     * After calling this method you will have to reload your models as ObjectManager::clear() is called
     * This is made on purpose to avoid working with inconsistent data
     *
     * @param Page $page
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
     * Return page route
     *
     * @param  Page                      $page
     * @param  string                    $locale
     * @return RouteObjectInterface|null
     */
    public function getRouteForPage(Page $page, $locale = null)
    {
        if (is_null($locale)) {
            $locale = $page->getLocale();
        }

        foreach ($page->getRoutes() as $route) {
            if (!($route instanceof RedirectRoute) && $route->getRequirement('_locale') == $locale) {
                //check redirect ?
                return $route;
            }
        }

        return null;
    }

    /**
     * Return all redirect routes for a page
     *
     * @param  Page            $page
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

    /**
     * Generate the list of redirection to create
     *
     * Returns an array where keys are old url an values the corresponding new urls
     *
     * @param  Route  $oldRoute
     * @param  string $newRouteUrl
     * @return array
     */
    protected function getCorrespondingUrls(Route $oldRoute, $newRouteUrl)
    {
        $urls = array($oldRoute->getId() => $newRouteUrl);

        foreach ($oldRoute->getRouteChildren() as $oldRouteChild) {
            $urls = array_merge(
                $urls,
                $this->getCorrespondingUrls($oldRouteChild, $newRouteUrl . '/' . $oldRouteChild->getName())
            );
        }

        return $urls;
    }

    /**
     * Generate RedirectRoutes
     *
     * @param array $urls
     */
    protected function generateRedirects(array $urls)
    {
//        //Not working : right now DoctrineDBAL Client throws an exception cause it considers that there is node duplication
//        //DocumentManager::clear does not solve the problem
//        return;

        foreach ($urls as $oldId => $newUrl) {
            $target = $this->getDocumentManager()->find(null, $newUrl);
            $parentId = substr($oldId, 0, strrpos($oldId, '/'));
            $parent = $this->getDocumentManager()->find(null, $parentId);
            if ($parent == null) {
                NodeHelper::createPath($this->getDocumentManager()->getPhpcrSession(), $parentId);
            }

            $redirectRoute = new RedirectRoute();
            $redirectRoute->setParent($parent);
            $redirectRoute->setName(substr($oldId, strrpos($oldId, '/') + 1));
            $redirectRoute->setRouteTarget($target);
            $redirectRoute->setPermanent(true);
            $redirectRoute->setDefaults($target->getDefaults());
            $redirectRoute->setRequirements($target->getRequirements());

            $this->getDocumentManager()->persist($redirectRoute);
        }

        $this->getDocumentManager()->flush();
    }

}
