<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\PrestaCMS\CoreBundle\Entity\Page;
use Application\PrestaCMS\CoreBundle\Entity\PageRevision;

/**
 * Event listener for page creation
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Alain Flaus <aflaus@prestaconcept.net>
 */
class CreatePageListener
{
    /**
     * Create page draft revision for a new page 
     *
     * @param   LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Page && $entity->getType() == 'cms_page') {

            $page_draft_revision = new PageRevision();
            $page_draft_revision->setLocale('fr');
            $page_draft_revision
                ->setPage($entity)
                ->setTemplate('default')
                ->setStatus(PageRevision::STATUS_DRAFT)
            ;

            $entityManager->persist($page_draft_revision);
            $entityManager->flush();

            $page_draft_revision->setLocale('en');
            $page_draft_revision
                ->setTemplate('default')
                ->setStatus(PageRevision::STATUS_DRAFT)
            ;

            $entityManager->persist($page_draft_revision);
            $entityManager->flush();
        }
    }
}