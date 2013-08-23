<?php

namespace Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Block;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ZoneFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $website = $configuration['website'];

        $zone = new Zone();
        $zone->setParentDocument($configuration['parent']);
        $zone->setName($configuration['name']);
        $this->getObjectManager()->persist($zone);

        foreach ($configuration['blocks'] as $position => $blockConfiguration) {
            $block = $this->createBlock($blockConfiguration, $zone, $position, $website);

            $zone->addBlock($block);
        }

        return $zone;
    }

    /**
     * Create a block
     *
     * @param  array    $blockConfiguration
     * @param  $parent
     * @param  integer  $position
     * @param  Website  $website
     * @return Block
     */
    protected function createBlock($blockConfiguration, $parent, $position, $website)
    {
        $blockConfiguration += array(
            'settings' => array(),
            'is_editable' => true,
            'is_deletable'=> true,
            'position'    => $position
        );
        $block = new Block();
        $block->setParent($parent);
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
        $this->getObjectManager()->persist($block);

        foreach ($website->getAvailableLocales() as $locale) {
            if (isset($blockConfiguration['settings'][$locale])) {
                $block->setSettings($blockConfiguration['settings'][$locale]);
            } else {
                $block->setSettings($blockConfiguration['settings']);
            }

            //Fail with : Notice: Undefined index: isEditable in AttributeTranslationStrategy.php line 48
            //if (isset($blockConfiguration['children'])) {
            //    foreach ($blockConfiguration['children'] as $position => $childConfiguration) {
            //        $this->createBlock($childConfiguration, $block, $position, $website);
            //    }
            //}
            $this->getObjectManager()->bindTranslation($block, $locale);
        }

        return $block;
    }
}
