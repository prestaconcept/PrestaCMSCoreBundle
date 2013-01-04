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

use Symfony\Component\HttpFoundation\Request;

use Presta\CMSCoreBundle\Exception\Website\WebsiteNotFoundException;
use Presta\CMSCoreBundle\Document\Website;

/**
 * Website Manager
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManager
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $_container;

    /**
     * @var array
     */
    protected $_websites;

    /**
     * @var \Presta\CMSCoreBundle\Document\Website
     */
    protected $currentWebsite;

    /**
     * @var Presta\CMSCoreBundle\Document\WebsiteRepository
     */
    protected $_repository;

    /**
     * @var boolean
     */
    protected $multipleWebsite;

    /**
     * @var string
     */
    protected $defaultWebsiteCode;

    /**
     * @var array
     */
    protected $hosts;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_websites = null;
        $this->currentWebsite = null;
        $this->_repository = null;
        $this->hosts = array();
    }

    /**
     * @param string $defaultWebsiteCode
     */
    public function setDefaultWebsiteCode($defaultWebsiteCode)
    {
        $this->defaultWebsiteCode = $defaultWebsiteCode;
    }

    /**
     * @return string
     */
    public function getDefaultWebsiteCode()
    {
        return $this->defaultWebsiteCode;
    }

    /**
     * @param boolean $multipleWebsite
     */
    public function setMultipleWebsite($multipleWebsite)
    {
        $this->multipleWebsite = $multipleWebsite;
    }

    /**
     * @return boolean
     */
    public function getMultipleWebsite()
    {
        return $this->multipleWebsite;
    }

    /**
     * Register a new host
     *
     * @param  array $hostConfiguration
     * @return WebsiteManager
     */
    public function registerHost($hostConfiguration)
    {
        $this->hosts[$hostConfiguration['host']] = $hostConfiguration;

        return $this;
    }

    /**
     * Return website repository
     *
     * @return Presta\CMSCoreBundle\Repository\WebsiteRepository
     */
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository =$this->_container->get('doctrine_phpcr.odm.default_document_manager')
                ->getRepository('Presta\CMSCoreBundle\Document\Website');
        }
        return $this->_repository;
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

    public function setCurrentWebsite($website)
    {
        $this->currentWebsite = $website;
    }
//
    /**
     * Get website
     *
     * @param  string $websiteCode
     * @param  string $locale
     * @return Website
     */
    public function getWebsite($websiteCode, $locale = null)
    {$websiteCode = '/website/prestaconcept';
        //$website = $this->_getRepository()->find($websiteId);
        $dm = $this->_container->get('doctrine_phpcr.odm.default_document_manager');
        $website = $dm->find(null, $websiteCode);

        if ($website instanceof Website && $locale != null) {
            $website->setLocale($locale);
        }
        $this->currentWebsite = $website;

        return $website;
    }

    /**
     * Return available websites
     *
     * @return ArrayCollection
     */
    public function getAvailableWebsites()
    {
        return $this->_getRepository()->getAvailableWebsites();
    }

    /**
     * Load a website based by host
     *
     * @param  string $host
     * @return Website
     */
    public function loadWebsiteByHost($host)
    {
        if (isset($this->hosts[$host])) {
            $website = $this->getWebsite($this->hosts[$host]['website'], $this->hosts[$host]['locale']);
        } else {
            $website = $this->getWebsite($this->defaultWebsiteCode);
        }
        $this->setCurrentWebsite($website);

        return $website;

    }
}
