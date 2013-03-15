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
     * @param $websiteManager
     */
    public function __construct($websiteManager)
    {
        $this->websiteManager  = $websiteManager;
    }

    /**
     * Load current website on front
     *
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function onKernelRequest(Event $event)
    {
        $request = $event->getRequest();

        if (strpos($request->getPathInfo(), '/_wdt') === 0
            || strpos($request->getPathInfo(), '/robots.txt') === 0 || strpos($request->getPathInfo(), '/css') === 0
            || strpos($request->getPathInfo(), '/js') === 0) {
            //For Front only
            return;
        }

        if (strpos($request->getPathInfo(), '/admin') === 0) {
            //Administration
            //Page edition id = menuNode id
            $id = $request->get('id', null);
            $websiteId = $request->get('website', null);
            $locale = $request->get('locale', null);

            $this->websiteManager->loadWebsiteById($websiteId, $locale);
        } else {
            //Front case
            //Load current website
            $website = $this->websiteManager->loadWebsiteByHost($request->getHost());
            $request->setLocale($website->getLocale());
        }
    }
}
