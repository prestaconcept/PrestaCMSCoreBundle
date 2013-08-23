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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Kernel;
use Presta\CMSCoreBundle\Model\WebsiteManager;

/**
 * Handle website selection based on request
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteListener
{
    const SESSION_WEBSITE_FIELD = 'presta_cms.website';
    const SESSION_LOCALE_FIELD  = 'presta_cms.locale';

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param WebsiteManager $websiteManager
     * @param Kernel         $kernel
     */
    public function __construct(WebsiteManager $websiteManager, Kernel $kernel, Session $session)
    {
        $this->websiteManager = $websiteManager;
        $this->environment    = $kernel->getEnvironment();
        $this->session        = $session;
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
            if ($this->websiteManager->getCurrentWebsite() != null) {
                //Website is already loaded : this listener is triggered by every subrequests like {% render url() %}
                return;
            }
            //Administration
            $websiteId  = $request->get('website', null);
            $locale     = $request->get('locale', null);

            if ($websiteId == null) {
                //Load website based on user last choice
                $websiteId = $this->session->get(self::SESSION_WEBSITE_FIELD);
                $locale    = $this->session->get(self::SESSION_LOCALE_FIELD);

                if ($websiteId == null) {
                    //For the first time we load the default website
                    $websiteId = $this->websiteManager->getDefaultWebsiteId();
                    $locale    = $this->websiteManager->getDefaultLocale();
                }
            }

            $website = $this->websiteManager->loadWebsiteById($websiteId, $locale, $this->environment);

            if ($website != null) {
                $this->session->set(self::SESSION_WEBSITE_FIELD, $website->getId());
                $this->session->set(self::SESSION_LOCALE_FIELD, $website->getLocale());
            }
        } else {
            //Front case
            //Load current website
            $website = $this->websiteManager->loadWebsiteByHost($request->getHost());

            if ($website != null) {
                $request->setLocale($website->getLocale());
                $request->attributes->set('_locale', $website->getLocale());
            }
        }
    }
}
