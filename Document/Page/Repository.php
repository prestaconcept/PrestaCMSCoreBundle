<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Page;

use Doctrine\ODM\PHPCR\DocumentRepository as BaseDocumentRepository;

use Presta\CMSCoreBundle\Document\Page;
use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;

/**
 * Page Repository
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Repository extends BaseDocumentRepository
{
    /**
     * Initialize page for a template
     *
     * @param  Page  $page
     * @param  array $configuration
     * @return Page
     */
    public function initializeForTemplate(Page $page, array $configuration)
    {
        $locales = $this->getDocumentManager()->getLocalesFor($page);

        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (count($zoneConfiguration['blocks']) == 0) {
                continue;
            }
            $pageZone = new Zone();
            $pageZone->setParentDocument($page);
            $pageZone->setName($zoneConfiguration['name']);
            $this->getDocumentManager()->persist($pageZone);
            foreach ($zoneConfiguration['blocks'] as $blockConfiguration) {
                $blockConfiguration += array(
                    'settings' => array(),
                    'is_editable' => true,
                    'is_deletable'=> true
                );
                $block = new Block();
                $block->setParent($pageZone);
                $block->setType($blockConfiguration['type']);
                if (strlen($blockConfiguration['name'])) {
                    $block->setName($blockConfiguration['name']);
                } else {
                    $block->setName($blockConfiguration['type'] . '-' . $blockConfiguration['position']);
                }
                $block->setIsEditable($blockConfiguration['is_editable']);
                $block->setIsDeletable($blockConfiguration['is_deletable']);
                $block->setPosition($blockConfiguration['position']);
                $block->setIsActive(true);

                $this->getDocumentManager()->persist($block);

                foreach ($locales as $locale) {
                    $block->setSettings($blockConfiguration['settings']);
                    $this->getDocumentManager()->bindTranslation($block, $locale);
                }
                $pageZone->addBlock($block);
            }

            $page->addZone($pageZone);
        }

        $this->getDocumentManager()->flush();

        return $page;
    }
}
