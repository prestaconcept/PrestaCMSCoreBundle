<?php

namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

use Presta\CMSCoreBundle\Document\Website;

/**
 * Description of RouteManager
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class RouteManager 
{
    protected $routeProvider;
    
    public function __construct(RouteProviderInterface $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }
    
    
    /**
     * @param \Presta\CMSCoreBundle\Document\Website $website
     * @return RouteCollection
     */
    public function findRoutesByWebsite(Website $website)
    {
        //Locale is in host only; then we list children for current locale
        $baseRoute = $this->routeProvider->getRouteByName($website->getPath() . '/route/' . $website->getLocale());
        return $this->routeProvider->getRouteCollectionForHierarchy($baseRoute);
    }
    
}