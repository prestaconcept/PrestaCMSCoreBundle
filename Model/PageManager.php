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
 * Page Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageManager
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $_container;
    
    /**
     * @var PrestaCMS\CoreBundle\Repository\PageRepository
     */
    protected $_repository;
    
    /**
     * @var array 
     */
    protected $_types;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_websites = null;
        $this->_repository = null;
        $this->_types = array(
            //todo inject via config
            'cms_page' => 'presta_cms.page_type.cms_page',
        );
    }
        
    /**
     * Return website repository
     * 
     * @return PrestaCMS\CoreBundle\Repository\PageRepository 
     */
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository = $this->_container->get('doctrine')->getEntityManager()
                ->getRepository('Application\PrestaCMS\CoreBundle\Entity\Page');
        }
        return $this->_repository;
    }
    
    /**
     * Return page by id
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @param  integer $id 
     * @return Application\PrestaCMS\CoreBundle\Entity\Page 
     */
    public function getPageById(Website $website, $id)
    {
        return $this->_getRepository()->getPageById($website, $id);
    }
    
    /**
     * Return page by id
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @param  string $url
     * @return Application\PrestaCMS\CoreBundle\Entity\Page 
     */
    public function getPageByUrl(Website $website, $url)
    {
        //TODO        
    }
    
    /**
     * Update page 
     * 
     * @param Page $page 
     */
    public function update($page)
    {
        $this->_container->get('doctrine')->getEntityManager()->persist($page);
        $this->_container->get('doctrine')->getEntityManager()->flush();
    }
    
    /**
     * Return root page node for a navigation
     * 
     * @param  Website $website
     * @param  string $navigation
     * @return Page 
     */
    public function getNavigationRoot(Website $website, $navigation)
    {
        return $this->_getRepository()->getNavigationRoot($website, $navigation);
    }
    
    /**
     * Return pages from navigation
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @param  string $navigation 
     * @param  boolean $direct - true to take only direct children
     * @return ArrayCollection
     */
    public function getNavigationPages(Website $website, $navigation, $direct = false)
    {
        $root = $this->getNavigationRoot($website, $navigation);
        if ($root == null) {
			return array();
        }
        return $this->_getRepository()->getChildrenPages($website, $root, $direct);
    }
    
    /**
     * Return navigation tree HTML code
     * 
     * @param  Website $website
     * @param  string $navigation
     * @param  boolean $direct
     * @return string 
     */
    public function getNavigationTree(Website $website, $navigation, $direct = false)
    {
        $nodes = $this->getNavigationPages($website, $navigation, $direct);
        return $this->buildTree($nodes, $website);
    }
    
    /**
     * Return single pages
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @return ArrayCollection
     */
    public function getSinglePages(Website $website)
    {
        return $this->_getRepository()->getSinglePages($website);
    }
    
    /**
     * Return single pages tree HTML code
     * 
     * @param  Website $website
     * @return string 
     */
    public function getSinglePagesTree(Website $website)
    {
        $nodes = $this->getSinglePages($website);
        return $this->buildTree($nodes, $website);
    }
    
    /**
     * Return tree HTML code for a node list
     * 
     * @param  array $nodes
     * @param  Website $website
     * @return string 
     */
    public function buildTree(array $nodes, Website $website)
    {
        //Generate edit url foreach pages
        foreach ($nodes as &$node) {
            $node['edit_url'] = $this->_container->get('router')->generate('presta_cms_page_edit', array(
                'id' => $node['id'],
                'website_id' => $website->getId(),
                'locale' => $website->getLocale()
            ));
        }
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li id="page_',
            'childClose' => '</li>',
            'nodeDecorator' => function($node) {
                return $node['id'] . '"><a href="' . $node['edit_url'] . '">' . $node['name'] . '</a>';
            }
        );
        return $this->_getRepository()->buildTree($nodes, $options);
    }
    
    /**
     * Reference a new page type service
     * 
     * @param  string $idType
     * @param  string $typeServiceId 
     * @return \PrestaCMS\CoreBundle\Model\PageManager 
     */
    public function addType($idType, $typeServiceId)
    {
        $this->_types[$idType] = $typeServiceId;
        return $this;
    }
    
    /**
     * Return correspondign page type service
     * 
     * @param  string $idType
     * @return false|PrestaCMS\CoreBundle\Model\PagePageTypeInterface 
     */
    public function getType($idType)
    {
        if (isset($this->_types[$idType])) {
            return $this->_container->get($this->_types[$idType]);
        }
        return false;  
    }
}
