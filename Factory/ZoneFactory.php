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

        foreach ($configuration['blocks'] as $blockConfiguration) {
            $blockConfiguration += array(
                'settings' => array(),
                'is_editable' => true,
                'is_deletable'=> true
            );
            $block = new Block();
            $block->setParent($zone);
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

                $this->getObjectManager()->bindTranslation($block, $locale);
            }

            $zone->addBlock($block);
        }

        return $zone;
    }
}
