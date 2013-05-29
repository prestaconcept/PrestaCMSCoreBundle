<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

use Presta\CMSCoreBundle\Document\Website;

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
     * @var RouteProviderInterface
     */
    protected $routeProvider;

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
}
