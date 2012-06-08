<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;

use Symfony\Component\HttpFoundation\Request;

use Application\PrestaCMS\CoreBundle\Entity\Website;

/**
 * Website Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
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
     * @var PrestaCMS\CoreBundle\Repository\WebsiteRepository
     */
    protected $_repository;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_websites = null;
        $this->_repository = null;
    }
        
    /**
     * Return website repository
     * 
     * @return PrestaCMS\CoreBundle\Repository\WebsiteRepository 
     */
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository =$this->_container->get('doctrine')->getEntityManager()
                ->getRepository('Application\PrestaCMS\CoreBundle\Entity\Website');
        }
        return $this->_repository;
    }
    
    /**
     * Return default website
     * 
     * @param  string $locale
     * @return \Application\PrestaCMS\CoreBundle\Entity\Website
     * @throws \Exception 
     */
    public function getDefaultWebsite($locale)
    {
        $website = $this->_getRepository()->getDefaultWebsite($locale);
        if (($website instanceof Website) == false) {
            throw new \Exception('There is no default website defined!');
        }
        return $website;
    }
    
    /**
     * Get website
     * 
     * @param  integer $websiteId
     * @param  string $locale
     * @return \Application\PrestaCMS\CoreBundle\Entity\Website 
     */
    public function getWebsite($websiteId, $locale)
    {
        $website = $this->_getRepository()->find($websiteId);
        if ($website instanceof Website) {
            $website->setLocale($locale);
        }
        //locale ?
        
        
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
     * @param   Symfony\Component\HttpFoundation\Request $resquest
     *
     * @author  Alain Flaus <aflaus@prestaconcept.net>
     *
     * @return  \Application\PrestaCMS\CoreBundle\Entity\Website  
     */
    public function getWebsiteForRequest(Request $request)
    {
        // je n'ai pas réussi a récupérer la "current" locale du website a partir du host
        $website = $this->_getRepository()->findAvailableByHost($request->getHost());

        preg_match('/^([a-zA-Z]*)\./', $request->getHost(), $locale);
        if (isset($locale[1]) && in_array($locale[1], $website->getAvailableLocales())) {
            $website->setLocale($locale[1]);
        } else {
            //voir si on affiche la default culture ou une 404
            $website->setLocale($website->getDefaultLocale());
        }

        return $website;
    }
}
