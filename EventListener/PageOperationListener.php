<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\EventListener;

use Doctrine\ODM\PHPCR\DocumentManager;
use Presta\CMSCoreBundle\Event\PageCreationEvent;
use Presta\CMSCoreBundle\Event\PageDeletionEvent;
use Presta\CMSCoreBundle\Event\PageUpdateEvent;
use Presta\CMSCoreBundle\Model\MenuManager;
use Presta\CMSCoreBundle\Model\RouteManager;
use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * Handle page operations
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageOperationListener
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var MenuManager
     */
    protected $menuManager;

    public function __construct(ModelManagerInterface $modelManager, RouteManager $routeManager, MenuManager $menuManager)
    {
        $this->documentManager  = $modelManager->getDocumentManager();
        $this->routeManager     = $routeManager;
        $this->menuManager      = $menuManager;
    }

    /**
     * @param PageCreationEvent $event
     */
    public function onPageCreation(PageCreationEvent $event)
    {
        $page = $event->getPage();
        $this->routeManager->createPageRouting($page);
    }

    /**
     * @param PageUpdateEvent $event
     */
    public function onPageUpdate(PageUpdateEvent $event)
    {
        $page = $event->getPage();

        // update page routing (manage redirect route)
        $this->routeManager->updatePageRouting($page);
    }

    /**
     * @param PageDeletionEvent $event
     */
    public function onPageDeletion(PageDeletionEvent $event)
    {
        $page = $event->getPage();

        //Remove corresponding MenuNodes and routes
        $referrers = $this->documentManager->getReferrers($page);

        foreach ($referrers as $referrer) {
            $this->documentManager->remove($referrer);
        }

        $this->documentManager->flush();
    }
}
