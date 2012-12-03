<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Website\Theme;

use Doctrine\ODM\PHPCR\DocumentRepository as BaseDocumentRepository;

use Presta\CMSCoreBundle\Document\Website;
use Presta\CMSCoreBundle\Document\Website\Theme;
use Presta\CMSCoreBundle\Document\Website\Theme\Zone;
use Presta\CMSCoreBundle\Document\Block\SimpleBlock;

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
        $websiteTheme = $this->getDocumentManager()->find('Presta\CMSCoreBundle\Document\Website\Theme', $website->getId() . '/' . $themeName);
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
        //Create website theme default association
        $websiteTheme = new Theme();
        $websiteTheme->setParent($website);
        $websiteTheme->setName($configuration['name']);
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
                $block = new $blockConfiguration['block_type']();
                $block->setParent($websiteThemeZone);
                $block->setLocale($website->getLocale());
                $block->setName('simple'.$blockConfiguration['position']);
                $block->setIsEditable($blockConfiguration['is_editable']);
                $block->setIsDeletable($blockConfiguration['is_deletable']);
                $block->setPosition($blockConfiguration['position']);
                $block->setIsActive(true);
                $block->setSettings(array());
                $this->getDocumentManager()->persist($block);
            }
        }
        $this->getDocumentManager()->flush();

        //todo ici les zones ne sont pas remontés!

        return $websiteTheme->getZones();
    }
}