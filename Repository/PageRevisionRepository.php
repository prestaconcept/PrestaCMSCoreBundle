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
use Application\PrestaCMS\CoreBundle\Entity\PageRevisionBlock;
use PrestaCMS\CoreBundle\Model\Template;

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

	/**
	 * Return published version of a page
	 *
	 * @param  Page $page
	 * @return PageRevision
	 */
	public function getPublishedRevisionForPage(Page $page)
	{
		$revision = $this->findOneBy(array(
			'page_id' => $page->getId(),
			'status' => PageRevision::STATUS_PUBLISHED
		));
		$revision->setLocale($page->getLocale());
		return $revision;
	}


	public function createBlockRevisionForTemplate(PageRevision $draft, array $configuration)
	{
		$blockByZone = array();
		foreach ($configuration['zones'] as $zoneConfiguration) {
			$zone = $zoneConfiguration['name'];
			$blockByZone[$zone] = array();
			foreach ($zoneConfiguration['blocks'] as $blockConfiguration) {
				$block = new PageRevisionBlock();
				$block->setPageRevision($draft);
				$block->setZone($zone);
				$block->setType($blockConfiguration['block_type']);
				$block->setIsEditable($blockConfiguration['is_editable']);
				$block->setIsDeletable($blockConfiguration['is_deletable']);
				$block->setPosition($blockConfiguration['position']);
				$block->setIsActive(true);
				$block->setSettings(array());
				$this->_em->persist($block);
				$blockByZone[$zone][$block->getPosition()] = $block;
			}
		}
		$this->_em->flush();
		return $blockByZone;
	}
}

