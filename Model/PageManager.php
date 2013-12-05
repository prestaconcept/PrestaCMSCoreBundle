<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Presta\CMSCoreBundle\Event\PageCreationEvent;
use Presta\CMSCoreBundle\Event\PageDeletionEvent;
use Presta\CMSCoreBundle\Exception\Page\PageTypeNotFoundException;
use Presta\CMSCoreBundle\Event\PageUpdateEvent;
use Presta\CMSCoreBundle\Model\Page\PageTypeInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Page
     */
    protected $currentPage;

    /**
     * @var ArrayCollection
     */
    protected $pageTypes;

    public function __construct($container)
    {
        $this->container  = $container;
        $this->pageTypes  = new ArrayCollection();
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->container->get('doctrine_phpcr')->getManager();
    }

    /**
     * Load a page corresponding to a menu node
     *
     * @param  string $menuNodeId
     * @param  string $locale
     * @return Page
     */
    public function getPageForMenu($menuNodeId, $locale)
    {
        $menuNode = $this->getDocumentManager()->findTranslation(null, $menuNodeId, $locale);
        $page     = $menuNode->getContent();

        //Translation is not propagated to the children
        //Should be corrected by https://github.com/doctrine/phpcr-odm/pull/237
        //but not working now : 14/03/2013 in administration as locale is not necessary the request one
        foreach ($page->getZones() as $zone) {
            foreach ($zone->getBlocks() as $block) {
                $this->getDocumentManager()->bindTranslation($block, $locale);
            }
        }

        $this->setCurrentPage($page);

        return $page;
    }

    /**
     * Create a page
     *
     * @param Page $page
     */
    public function create($page)
    {
        $this->getDocumentManager()->persist($page);
        $this->getDocumentManager()->flush();

        $this->container->get('event_dispatcher')->dispatch(
            PageCreationEvent::EVENT_NAME,
            new PageCreationEvent($page)
        );
    }

    /**
     * Update a page
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
     * @param  string      $pageTypeId
     * @return PageManager
     */
    public function addPageType($pageTypeId)
    {
        $this->pageTypes->add($pageTypeId);

        return $this;
    }

    /**
     * Return corresponding page type service
     *
     * @param  string            $idType
     * @return PageTypeInterface
     */
    public function getPageType($pageTypeId)
    {
        if (!$this->pageTypes->contains($pageTypeId)) {
            throw new PageTypeNotFoundException($pageTypeId);
        }

        return $this->container->get($pageTypeId);
    }

    /**
     * @param Page $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return Page
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Get a Page by id
     *
     * @param  $id
     * @return Page
     */
    public function getPageById($id)
    {
        return $this->getDocumentManager()->find(null, $id);
    }

    /**
     * Return token : use it to validate risky action
     *
     * @param Page $page
     */
    public function getToken(Page $page)
    {
        return md5($page->getId() . $page->getType() . $this->container->getParameter('secret'));
    }

    /**
     * Check if token is valid
     *
     * @param  Page    $page
     * @param  string  $token
     * @return boolean
     */
    public function isValidToken($page, $token)
    {
        return ($token != null && $token == $this->getToken($page));
    }

    /**
     * @param  Website $website
     * @return array
     */
    public function getPagesForWebsite(Website $website)
    {
        $qb = $this->getDocumentManager()->createQueryBuilder();
        $qb->from()->document('Presta\CMSCoreBundle\Doctrine\Phpcr\Page', 'p');
        //Waiting on QueryBuilderV2 to use this
        //$qb->where()->like()->field('p.id')->literal($website->getPageRoot() . '%');
        $qb->orderBy()->asc()->field('p.id');

        $results = $qb->getQuery()->execute();

        $pages = array();
        foreach ($results as $page) {
            if (strpos($page->getId(), $website->getPageRoot()) === 0) {
                $pages[] = $page;
            }
        }

        return $pages;
    }

    /**
     * @param Page $page
     */
    public function clearCache(Page $page)
    {
        $page->clearCache();
        $this->update($page);
    }
}
