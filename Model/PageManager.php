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

use Symfony\Cmf\Bundle\MenuBundle\Document\MenuItem;
use Presta\CMSCoreBundle\Document\Page;
use Doctrine\ODM\PHPCR\DocumentManager;

/**
 * Page Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageManager
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $_container;
    
    /**
     * @var Presta\CMSCoreBundle\Repository\PageRepository
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
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->_container->get('doctrine_phpcr.odm.default_document_manager');
    }

    /**
     * Return website repository
     * 
     * @return
     */
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository =$this->_container->get('doctrine_phpcr.odm.default_document_manager')
                ->getRepository('Presta\CMSCoreBundle\Document\Page');
        }
        return $this->_repository;
    }

    /**
     * Load a page corresponding to a menu item
     *
     * @param  MenuItem $menuItemId
     * @param  string   $locale
     * @return Page
     */
    public function getPageForMenu($menuItemId, $locale)
    {
        $menuItem = $this->getDocumentManager()->find(null, $menuItemId);
        $page = $menuItem->getContent();
        //ici le chargement de la trad ne fonctionne pas !
        $this->getDocumentManager()->bindTranslation($page, 'en');

        return $page;
    }
    
//    /**
//     * Return page by id
//     *
//     * @param  Application\Presta\CMSCoreBundle\Entity\Website $website
//     * @param  integer $id
//     * @return Application\Presta\CMSCoreBundle\Entity\Page
//     */
//    public function getPageById(Website $website, $id)
//    {
//        return $this->_getRepository()->getPageById($website, $id);
//    }
//
//    /**
//     * Return page by id
//     *
//     * @param  Application\Presta\CMSCoreBundle\Entity\Website $website
//     * @param  string $url
//     * @return Application\Presta\CMSCoreBundle\Entity\Page
//     */
//    public function getPageByUrl(Website $website, $url)
//    {
//		//todo handle first '/' ?
//		if (strpos($url, '/') === 0) {
//			$url = substr($url, 1);
//		}
//		return $this->_getRepository()->getPageByUrl($website, $url);
//    }
    
    /**
     * Update page
     *
     * @param Page $page
     */
    public function update($page)
    {
        $this->getDocumentManager()->persist($page);
        $this->getDocumentManager()->flush();
    }
    
//    /**
//     * Return root page node for a navigation
//     *
//     * @param  Website $website
//     * @param  string $navigation
//     * @return Page
//     */
//    public function getNavigationRoot(Website $website, $navigation)
//    {
//        return $this->_getRepository()->getNavigationRoot($website, $navigation);
//    }
    
//    /**
//     * Return pages from navigation
//     *
//     * @param  Application\Presta\CMSCoreBundle\Entity\Website $website
//     * @param  string $navigation
//     * @param  boolean $direct - true to take only direct children
//     * @return ArrayCollection
//     */
//    public function getNavigationPages(Website $website, $navigation, $direct = false)
//    {
//        $root = $this->getNavigationRoot($website, $navigation);
//        if ($root == null) {
//			return array();
//        }
//        return $this->_getRepository()->getChildrenPages($website, $root, $direct);
//    }
//
//    /**
//     * Return navigation tree HTML code
//     *
//     * @param  Website $website
//     * @param  string $navigation
//	 * @param  boolean $withCheckboxes
//     * @return string
//     */
//    public function getNavigationTree(Website $website, $navigation, $withCheckboxes = false)
//    {
//        $nodes = $this->getNavigationPages($website, $navigation);
//        return $this->buildTree($nodes, $website, $withCheckboxes);
//    }
//
//    /**
//     * Return single pages
//     *
//     * @param  Application\Presta\CMSCoreBundle\Entity\Website $website
//     * @return ArrayCollection
//     */
//    public function getSinglePages(Website $website)
//    {
//        return $this->_getRepository()->getSinglePages($website);
//    }
    
//    /**
//     * Return single pages tree HTML code
//     *
//     * @param  Website $website
//	 * @param  boolean $withCheckboxes
//     * @return string
//     */
//    public function getSinglePagesTree(Website $website, $withCheckboxes = false)
//    {
//        $nodes = $this->getSinglePages($website);
//        return $this->buildTree($nodes, $website, $withCheckboxes);
//    }
    
//    /**
//     * Return tree HTML code for a node list
//     *
//     * @param  array $nodes
//     * @param  Website $website
//	 * @param  boolean $withCheckboxes
//     * @return string
//     */
//    public function buildTree(array $nodes, Website $website, $withCheckboxes = false)
//    {
//        //Generate edit url foreach pages
//        foreach ($nodes as &$node) {
//            $node['edit_url'] = $this->_container->get('router')->generate('presta_cms_page_edit', array(
//                'id' => $node['id'],
//                'website_id' => $website->getId(),
//                'locale' => $website->getLocale()
//            ));
//			$node['withCheckboxes'] = $withCheckboxes;
//        }
//        $options = array(
//            'decorate' => true,
//            'rootOpen' => '<ul>',
//            'rootClose' => '</ul>',
//            'childOpen' => '<li id="page_',
//            'childClose' => '</li>',
//            'nodeDecorator' => function($node) {
//				if ($node['withCheckboxes']) {
//					return $node['id'] . '"><input type="checkbox" name="cms_page_tree" value="' . $node['id'] . '"/>' . $node['name'] . '';
//				}
//                return $node['id'] . '"><a href="' . $node['edit_url'] . '">' . $node['name'] . '</a>';
//            }
//        );
//
//        return $this->_getRepository()->buildTree($nodes, $options);
//    }
    
    /**
     * Reference a new page type service
     * 
     * @param  string $idType
     * @param  string $typeServiceId 
     * @return \Presta\CMSCoreBundle\Model\PageManager
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
     * @return false|Presta\CMSCoreBundle\Model\PagePageTypeInterface
     */
    public function getType($idType)
    {
        if (isset($this->_types[$idType])) {
            return $this->_container->get($this->_types[$idType]);
        }
        return false;  
    }
}
