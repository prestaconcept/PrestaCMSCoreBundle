<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Theme;

use Doctrine\ODM\PHPCR\DocumentRepository as BaseDocumentRepository;

use PHPCR\Util\NodeHelper;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Document\Theme\Zone;
use Presta\CMSCoreBundle\Document\Block;


/**
 * Website Theme Repository
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Repository extends BaseDocumentRepository
{
    public function getZones($themeName, $website)
    {
        $websiteTheme = $this->getDocumentManager()->find('Presta\CMSCoreBundle\Document\Theme', $website->getId() . '/theme/' . $themeName);
        if ($websiteTheme != null) {
            return $websiteTheme->getZones();
        }

        return array();
    }

    /**
     * Initialize data for a website
     *
     * @param  Application\Presta\CMSCoreBundle\Entity\Website $website
     * @param  array $configuration
     * @return
     */
    public function initializeForWebsite(Website $website, array $configuration)
    {
        $session = $this->getDocumentManager()->getPhpcrSession();
        NodeHelper::createPath($session, $website->getPath() . '/theme');

        //Create website theme default association
        $websiteTheme = new Theme();
        $websiteTheme->setParent($website);
        $websiteTheme->setName('theme/' . $configuration['name']);
        $this->getDocumentManager()->persist($websiteTheme);


        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (count($zoneConfiguration['blocks']) == 0) {
                continue;
            }
            $websiteThemeZone = new Zone();
            $websiteThemeZone->setParentDocument($websiteTheme);
            $websiteThemeZone->setName($zoneConfiguration['name']);
            $this->getDocumentManager()->persist($websiteThemeZone);
            foreach ($zoneConfiguration['blocks'] as $blockConfiguration) {
                $block = new Block();
                $block->setParent($websiteThemeZone);
                $block->setLocale($website->getLocale());
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
                $block->setSettings(array());
                $this->getDocumentManager()->persist($block);
                $websiteThemeZone->addBlock($block);
                $websiteTheme->addZone($websiteThemeZone);
            }
        }
        $this->getDocumentManager()->flush();

        return $websiteTheme->getZones();
    }
}