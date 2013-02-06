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
            $this->repository = $this->getDocumentManager->getRepository('Presta\CMSCoreBundle\Document\Page');
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

        $page = $this->getDocumentManager()->findTranslation(null, $page->getPath(), $locale);

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
        //Update corresponding route
        $route = $page->getRoute();
        $route->setName($page->getUrl());

        //todo voir pour faire mieux :
        //création d'une redirection pour l'ancien url
        //pas de modification de la home sinon ça case le prefix des routes!

        $this->getDocumentManager()->persist($route);
        $this->getDocumentManager()->persist($page);
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
}
