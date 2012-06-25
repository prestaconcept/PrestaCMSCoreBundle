<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

use Application\PrestaCMS\CoreBundle\Entity\Page;
use Application\PrestaCMS\CoreBundle\Entity\PageRevision;

/**
 * Page Revision Repository
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageRevisionRepository extends EntityRepository
{
    /**
     * Return draft version of a page
     * 
     * @param  Page $page
     * @return PageRevision 
     */
    public function getDraftForPage(Page $page)
    {
        $draft = $this->findOneBy(array(
            'page_id' => $page->getId(), 
            'status' => PageRevision::STATUS_DRAFT
        ));
        $draft->setLocale($page->getLocale());
        return $draft;
    }
}

