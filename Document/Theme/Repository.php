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
use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;

/**
 * Website Theme Repository
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Repository extends BaseDocumentRepository
{
    /**
     * @param $themeName
     * @param $website
     * @return array
     */
    public function getZones($themeName, $website)
    {
        $zones = array();
        $websiteTheme = $this->getDocumentManager()->find('Presta\CMSCoreBundle\Document\Theme', $website->getId() . '/theme/' . $themeName);
        if ($websiteTheme != null) {
            foreach ($websiteTheme->getZones() as $zone) {
                $zones[$zone->getName()] = $zone;
            }
        }

        return $zones;
    }

    /**
     * Initialize data for a website
     *
     * @param Website $website
     * @param array   $configuration
     * @return
     */
    public function initializeForWebsite(Website $website, array $configuration)
    {
        $session = $this->getDocumentManager()->getPhpcrSession();
        NodeHelper::createPath($session, $website->getPath() . '/theme');

        //Create website theme default association
        $websiteTheme = new Theme();
        $websiteTheme->setParentDocument($website);
        $websiteTheme->setName('theme/' . $configuration['name']);
        $this->getDocumentManager()->persist($websiteTheme);

        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (!isset($zoneConfiguration['blocks']) || count($zoneConfiguration['blocks']) == 0) {
                continue;
            }
            $websiteThemeZone = new Zone();
            $websiteThemeZone->setParentDocument($websiteTheme);
            $websiteThemeZone->setName($zoneConfiguration['name']);
            $this->getDocumentManager()->persist($websiteThemeZone);
            foreach ($zoneConfiguration['blocks'] as $blockConfiguration) {
                $blockConfiguration += array(
                    'settings' => array(),
                    'is_editable' => true,
                    'is_deletable'=> true
                );
                $block = new Block();
                $block->setParent($websiteThemeZone);
                $block->setType($blockConfiguration['type']);
                if (isset($blockConfiguration['name']) && strlen($blockConfiguration['name'])) {
                    $block->setName($blockConfiguration['name']);
                } else {
                    $block->setName($blockConfiguration['type'] . '-' . $blockConfiguration['position']);
                }
                $block->setIsEditable($blockConfiguration['is_editable']);
                $block->setIsDeletable($blockConfiguration['is_deletable']);
                $block->setPosition($blockConfiguration['position']);
                $block->setIsActive(true);
                $this->getDocumentManager()->persist($block);

                foreach ($website->getAvailableLocales() as $locale) {
                    $block->setSettings($blockConfiguration['settings']);
                    $this->getDocumentManager()->bindTranslation($block, $locale);
                }

                $websiteThemeZone->addBlock($block);
            }
            $websiteTheme->addZone($websiteThemeZone);
        }
        $this->getDocumentManager()->flush();

        return $websiteTheme->getZones();
    }
}
