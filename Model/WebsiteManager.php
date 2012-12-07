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

use Application\Presta\CMSCoreBundle\Entity\Website;
use Presta\CMSCoreBundle\Exception\Website\WebsiteNotFoundException;

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

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_websites = null;
        $this->currentWebsite = null;
        $this->_repository = null;
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
//
//    /**
//     * Return default website
//     *
//     * @param  string $locale
//     * @return \Application\Presta\CMSCoreBundle\Entity\Website
//     * @throws \Exception
//     */
//    public function getDefaultWebsite($locale)
//    {
//        $website = $this->_getRepository()->getDefaultWebsite($locale);
//        if (($website instanceof Website) == false) {
//            throw new \Exception('There is no default website defined!');
//        }
//        return $website;
//    }
//
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
     * @param  integer $websiteId
     * @param  string $locale
     * @return \Presta\CMSCoreBundle\Document\Website
     */
    public function getWebsite($websiteId, $locale)
    {
        //$website = $this->_getRepository()->find($websiteId);
        $dm = $this->_container->get('doctrine_phpcr.odm.default_document_manager');
        $website = $dm->findTranslation(null, $websiteId, $locale);
        if ($website instanceof Website) {
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
//
//
//    /**
//     * Retrieve website for current host and locale
//     *
//     * @param   Symfony\Component\HttpFoundation\Request $resquest
//     * @return  \Application\Presta\CMSCoreBundle\Entity\Website  $website
//     */
//    public function getWebsiteForRequest(Request $request)
//    {
//        $requestWebsite = null;
//
//        $websites = $this->_getRepository()->findByHost($request->getHost());
//        if (empty($websites)) {
//            throw new WebsiteNotFoundException();
//        }
//
//        // Closure telling if a given uri matches a path
//        $uriMatchesPath = function ($uri, $path) {
//            $uri = preg_replace('/^\/app_[a-z]+.php/', '', $uri);
//            return strpos($uri, $path) === 0;
//        };
//
//        foreach ($websites as $website) {
//            foreach ($website->getTranslations() as $translation) {
//
//                if (in_array($translation->getLocale(), $website->getAvailableLocales())
//                    && $translation->getField() == 'relative_path'
//                    && $uriMatchesPath($request->getRequestUri(), $translation->getContent())) {
//
//                    //if the current website match for the current locale stop search
//                    $requestWebsite = $website;
//                    $requestWebsite->setLocale($translation->getLocale());
//                    break 2;
//                }
//
//            }
//        }
//
//        if (is_null($requestWebsite))
//        {
//            $requestWebsite = $websites[0];
//            $requestWebsite->setLocale($requestWebsite->getDefaultLocale());
//        }
//
//        return $requestWebsite;
//    }
}
