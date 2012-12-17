<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteRepository;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Listener\IdPrefix;

/**
 * Handle website selection based on request
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteListener
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var RouteRepository
     */
    protected $routeRepository;

    /**
     * @var IdPrefix
     */
    protected $routeListener;

    /**
     * @param $websiteManager
     * @param $routeRepository
     */
    public function __construct($websiteManager, $routeRepository, $routeListener)
    {
        $this->websiteManager  = $websiteManager;
        $this->routeRepository = $routeRepository;
        $this->routeListener   = $routeListener;
    }

    /**
     * Load current website on front
     *
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function onKernelRequest(Event $event)
    {
        $request = $event->getRequest();

        if (strpos($request->getPathInfo(), '/admin') === 0 || strpos($request->getPathInfo(), '/_wdt') === 0
            || strpos($request->getPathInfo(), '/robots.txt') === 0 || strpos($request->getPathInfo(), '/css') === 0
            || strpos($request->getPathInfo(), '/js') === 0) {
            //For Front only
            return;
        }

        //Find website
        $website = $this->websiteManager->loadWebsiteByHost($request->getHost());

        //Inject route prefix in Route Repository adn listener
        $this->routeRepository->setPrefix($website->getRoutePrefix());
        $this->routeListener->setPrefix($website->getRoutePrefix());
    }
}