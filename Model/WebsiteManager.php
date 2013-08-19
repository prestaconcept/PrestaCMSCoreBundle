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
use Presta\CMSCoreBundle\Model\Website;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RouteProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Website Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManager
{
    const WEBSITE_CLASS = 'Presta\CMSCoreBundle\Doctrine\Phpcr\Website';

    /**
     * @var \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RouteProvider
     */
    protected $routeProvider;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingBundle\Listener\IdPrefix
     */
    protected $routeListener;

    /**
     * @var \Knp\Menu\Provider\MenuProviderInterface
     */
    protected $menuProvider;

    /**
     * @var array
     */
    protected $websites;

    /**
     * @var string
     */
    protected $defaultWebsiteId;

    /**
     * @var string
     */
    protected $defaultLocale;

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
     * @param \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RouteProvider $routeProvider
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
     * @param \Knp\Menu\Provider\MenuProviderInterface $menuProvider
     */
    public function setMenuProvider($menuProvider)
    {
        $this->menuProvider = $menuProvider;
    }

    /**
     * @return \Knp\Menu\Provider\MenuProviderInterface
     */
    public function getMenuProvider()
    {
        return $this->menuProvider;
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
     * @return \Presta\CMSCoreBundle\Doctrine\Phpcr\Website
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
    protected function setCurrentWebsite($website)
    {
        if (!$website instanceof Website) {
            return false;
        }
        $this->currentWebsite = $website;

        //Inject route prefix in Route Repository and listener
        $this->routeProvider->setPrefix($website->getRoutePrefix());
        $this->routeListener->setPrefix($website->getRoutePrefix());
        $this->menuProvider->setMenuRoot($website->getMenuRoot());
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
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param string $defaultWebsiteId
     */
    public function setDefaultWebsiteId($defaultWebsiteId)
    {
        $this->defaultWebsiteId = $defaultWebsiteId;
    }

    /**
     * @return string
     */
    public function getDefaultWebsiteId()
    {
        return $this->defaultWebsiteId;
    }

    /**
     * Get website
     *
     * @param  array   $hostConfiguration
     * @return Website
     */
    protected function loadWebsite($hostConfiguration)
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
            return $this->loadWebsite($this->hosts[$host]);
        }

        return false;
    }

    /**
     * Return front host for a website : used to open front url from the backend
     *
     * @param  Website $website
     * @param  string  $locale
     * @param  string  $env
     * @return string
     */
    public function getHostForWebsite($website, $locale, $env)
    {
        foreach ($this->hosts as $host) {
            if ($host['path'] == $website->getId() && $host['locale'] == $locale && $host['env'] == $env) {
                return $host['host'];
            }
        }

        return false;
    }

    /**
     * Load current website based on its id and locale
     *
     * @param  int      $websiteId
     * @param  string   $locale
     * @param  string   $environment
     * @return null|Website
     */
    public function loadWebsiteById($websiteId, $locale, $environment)
    {
        $params = array(
            'path'      => $websiteId,
            'locale'    => $locale,
            'env'       => $environment
        );

        return $this->loadWebsite($params);
    }

    /**
     * Return current website base url for the locale parameter based on current environment
     *
     * @param $locale
     *
     * @return string
     */
    public function getBaseUrlForLocale($locale)
    {
        if (is_null($this->getCurrentWebsite()) || is_null($this->getCurrentEnvironment())) {
            return false;
        }

        $configuration = $this->websites[$this->getCurrentWebsite()->getPath()]['hosts'][$this->getCurrentEnvironment()];

        if (!isset($configuration[$locale]) || !isset($configuration[$locale]['host'])) {
            return false;
        }

        return 'http://' . $configuration[$locale]['host'];
    }
}
