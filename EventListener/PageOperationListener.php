<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\EventListener;

use Presta\CMSCoreBundle\Event\PageCreationEvent;
use Presta\CMSCoreBundle\Event\PageDeletionEvent;
use Presta\CMSCoreBundle\Event\PageUpdateEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Handle page operations
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageOperationListener
{
    /**
     * @param PageCreationEvent $event
     */
    public function onPageCreation(PageCreationEvent $event)
    {

    }

    /**
     * @param PageUpdateEvent $event
     */
    public function onPageUpdate(PageUpdateEvent $event)
    {
        //Check route change and create corresponding redirects github #10
    }

    /**
     * @param PageDeletionEvent $event
     */
    public function onPageDeletion(PageDeletionEvent $event)
    {
        //Remove corresponding MenuNodes

        //Remove corresponding routes
        var_dump($event);die;
    }
}
