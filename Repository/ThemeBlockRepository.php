<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Application\PrestaCMS\CoreBundle\Entity\Website;
use Application\PrestaCMS\CoreBundle\Entity\ThemeBlock;

class ThemeBlockRepository extends EntityRepository
{
    /**
     * Return theme content for website ordered by zone
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @return type 
     */
    public function getBlocksForWebsiteByZone(Website $website)
    {
        $query = $this->createQueryBuilder('tb')
            ->where('tb.theme = :theme and tb.website = :website')
            ->setParameters(array('theme'=> 'default', 'website' => $website))
            ->orderBy('tb.zone', 'ASC')
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
            )->setHint(
                \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $website->getLocale()
            );
        
        $blockByZone = array();
        foreach ($query->execute() as $block) {
            if (!isset($blockByZone[$block->getZone()])) {
                $blockByZone[$block->getZone()] = array();
            }
            $blockByZone[$block->getZone()][$block->getPosition()] = $block;
        }
        return $blockByZone;
    }
    
    /**
     * Initialize data for a website
     * 
     * @param  Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @param  array $configuration 
     * @return 
     */
    public function initializeForWebsite(Website $website, array $configuration)
    {
        $theme = $configuration['name'];
        $blockByZone = array();
        foreach ($configuration['zones'] as $zoneConfiguration) {
            $zone = $zoneConfiguration['name'];
            $blockByZone[$zone] = array();
            foreach ($zoneConfiguration['blocks'] as $blockConfiguration) {
                $block = new ThemeBlock();
                $block->setWebsite($website);
                $block->setTheme($theme);
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

