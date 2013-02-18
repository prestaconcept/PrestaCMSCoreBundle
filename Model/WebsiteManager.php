<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Presta\CMSCoreBundle\Document\Website;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Website Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManager
{
    const WEBSITE_CLASS = 'Presta\CMSCoreBundle\Document\Website';

    /**
     * @var \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider
     */
    protected $routeProvider;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingExtraBundle\Listener\IdPrefix
     */
    protected $routeListener;

    /**
     * @var array
     */
    protected $websites;

    /**
     * @var Website
     */
    protected $currentWebsite;

    /**
     * @var string
     */
    protected $currentEnvironment;

    /**
     * @var array
     */
    protected $hosts;

    public function __construct()
    {
        $this->websites = null;
        $this->currentWebsite = null;
        $this->currentEnvironment = null;
        $this->hosts = array();
    }

    /**
     * @param \Sonata\AdminBundle\Model\ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * @return \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    public function getModelManager()
    {
        return $this->modelManager;
    }

    /**
     * @param \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider $routeProvider
     */
    public function setRouteProvider(RouteProvider $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * @param $routeListener
     */
    public function setRouteListener($routeListener)
    {
        $this->routeListener = $routeListener;
    }

    /**
     * @return boolean
     */
    public function hasMultipleWebsite()
    {
        return (count($this->websites) > 1);
    }

    /**
     * Register a new website
     *
     * @param  array          $websiteConfiguration
     * @return WebsiteManager
     */
    public function registerWebsite($websiteConfiguration)
    {
        $path = $websiteConfiguration['path'];
        $this->websites[$path] = $websiteConfiguration;

        foreach ($websiteConfiguration['hosts'] as $env => $hosts) {
            foreach ($hosts as $hostConfiguration) {
                $this->hosts[$hostConfiguration['host']] = $hostConfiguration;
                $this->hosts[$hostConfiguration['host']]['path'] = $path;
                $this->hosts[$hostConfiguration['host']]['env']  = $env;
            }
        }

        return $this;
    }

    /**
     * Check if a host is registered
     *
     * @param  string $hostCode
     * @return bool
     */
    public function hasHostRegistered($hostCode)
    {
        return isset($this->hosts[$hostCode]);
    }

    /**
     * Return current website
     *
     * @return \Application\Presta\CMSCoreBundle\Entity\Website
     */
    public function getCurrentWebsite()
    {
        return $this->currentWebsite;
    }

    /**
     * Set current website
     *
     * @param $website
     */
    public function setCurrentWebsite($website)
    {
        if (!$website instanceof Website) {
            return false;
        }
        $this->currentWebsite = $website;

        //Inject route prefix in Route Repository and listener
        $this->routeProvider->setPrefix($website->getRoutePrefix());
        $this->routeListener->setPrefix($website->getRoutePrefix());
    }

    /**
     * @return string
     */
    public function getCurrentEnvironment()
    {
        return $this->currentEnvironment;
    }

    /**
     * @param string $currentEnvironment
     */
    public function setCurrentEnvironment($currentEnvironment)
    {
        $this->currentEnvironment = $currentEnvironment;
    }

    /**
     * Get website
     *
     * @param  array  $hostConfiguration
     * @return Website
     */
    public function getWebsite($hostConfiguration)
    {
        $website = $this->getModelManager()->find(self::WEBSITE_CLASS, $hostConfiguration['path']);

        if (!$website instanceof Website) {
            return null;
        }

        $website->setLocale($hostConfiguration['locale']);

        $this->setCurrentWebsite($website);
        if (isset($hostConfiguration['env'])) {
            $this->setCurrentEnvironment($hostConfiguration['env']);
        }

        return $website;
    }

    /**
     * Return available websites
     *
     * @return ArrayCollection
     */
    public function getAvailableWebsites()
    {
        return $this->getModelManager()->findBy(self::WEBSITE_CLASS, array());
    }

    /**
     * Load a website based by host
     *
     * @param  string  $host
     * @return Website
     */
    public function loadWebsiteByHost($host)
    {
        if (isset($this->hosts[$host])) {
            $website = $this->getWebsite($this->hosts[$host]);
        } else {
            throw new NotFoundHttpException('Website not found');
        }
        $this->setCurrentWebsite($website);

        return $website;
    }
}
