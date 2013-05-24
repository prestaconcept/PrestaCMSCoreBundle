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

use Application\Presta\CMSCoreBundle\Document\Website;

use Presta\CMSCoreBundle\Event\PageDeletionEvent;
use Presta\CMSCoreBundle\Event\PageUpdateEvent;
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
    protected $container;

    /**
     * @var Presta\CMSCoreBundle\Repository\PageRepository
     */
    protected $repository;

    /**
     * @var Page
     */
    protected $currentPage;

    /**
     * @var array
     */
    protected $types;

    public function __construct($container)
    {
        $this->container = $container;
        $this->websites = null;
        $this->repository = null;
        $this->types = array(
            //todo inject via config
            'cms_page' => 'presta_cms.page_type.cms_page',
        );
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->container->get('doctrine_phpcr')->getManager();
    }

    /**
     * Return website repository
     *
     * @return
     */
    protected function getRepository()
    {
        if ($this->repository == null) {
            $this->repository = $this->getDocumentManager()->getRepository('Presta\CMSCoreBundle\Document\Page');
        }

        return $this->repository;
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
        $menuItem = $this->getDocumentManager()->findTranslation(null, $menuItemId, $locale);
        $page = $menuItem->getContent();

        //Page is already in the menu locale
        //$this->getDocumentManager()->bindTranslation($page, $locale);

        //Translation is not propagated to the children
        //Should be corrected by https://github.com/doctrine/phpcr-odm/pull/237
        //but not working now : 14/03/2013
        foreach ($page->getZones() as $zone) {
            foreach ($zone->getBlocks() as $block) {
                $this->getDocumentManager()->bindTranslation($block, $locale);
            }
        }
        $this->setCurrentPage($page);

        return $page;
    }

    /**
     * Update page
     *
     * @param Page $page
     */
    public function update($page)
    {
        $this->getDocumentManager()->persist($page);
        $this->getDocumentManager()->flush();

        $this->container->get('event_dispatcher')->dispatch(
            PageUpdateEvent::EVENT_NAME,
            new PageUpdateEvent($page)
        );
    }

    /**
     * Delete a page
     *
     * @param Page $page
     */
    public function delete($page)
    {
        $this->container->get('event_dispatcher')->dispatch(
            PageDeletionEvent::EVENT_NAME,
            new PageDeletionEvent($page)
        );

        //We need to delete the main document after deleting every referrers
        $this->getDocumentManager()->remove($page);
        $this->getDocumentManager()->flush();
    }

    /**
     * Reference a new page type service
     *
     * @param  string                                  $idType
     * @param  string                                  $typeServiceId
     * @return \Presta\CMSCoreBundle\Model\PageManager
     */
    public function addType($idType, $typeServiceId)
    {
        $this->types[$idType] = $typeServiceId;

        return $this;
    }

    /**
     * Return corresponding page type service
     *
     * @param  string                                                 $idType
     * @return false|Presta\CMSCoreBundle\Model\PagePageTypeInterface
     */
    public function getType($idType)
    {
        if (isset($this->types[$idType])) {
            return $this->container->get($this->types[$idType]);
        }

        return false;
    }

    /**
     * @param \Presta\CMSCoreBundle\Document\Page $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return \Presta\CMSCoreBundle\Document\Page
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param $id
     * @return \Presta\CMSCoreBundle\Document\Page
     */
    public function getPageById($id)
    {
        return $this->getDocumentManager()->find(null, $id);
    }

    /**
     * Return token : use it to validate risky action
     *
     * @param $page
     */
    public function getToken($page)
    {
        return md5($page->getId() . $page->getType() . $this->container->getParameter('secret'));
    }

    /**
     * Check if token is valid
     *
     * @param  \Presta\CMSCoreBundle\Document\Page $page
     * @param  string $token
     * @return boolean
     */
    public function isValidToken($page, $token)
    {
        return ($token == $this->getToken($page));
    }
}
