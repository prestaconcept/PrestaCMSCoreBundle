<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Model\Block;
use Presta\CMSCoreBundle\Model\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ZoneFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * @var string
     */
    protected $blockModelClassName;

    /**
     * @param string $blockModelClassName
     */
    public function setBlockModelClassName($blockModelClassName)
    {
        $this->blockModelClassName = $blockModelClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $website = $configuration['website'];

        $configuration += array(
            'editable'  => true,
            'sortable'  => false
        );

        $zone = new $this->modelClassName();
        if (isset($configuration['parent'])) {
            $zone->setParent($configuration['parent']);
            $zone->setName($configuration['name']);
        } elseif ($configuration['id'] != null) {
            //Check if zone already exist : add block case
            $zone = $this->getObjectManager()->find(null, $configuration['id']);
            if ($zone == null) {
                $zone = new Zone();
                $zone->setId($configuration['id']);
            }
        }

        $zone->setEditable($configuration['editable']);
        $zone->setSortable($configuration['sortable']);

        $this->getObjectManager()->persist($zone);

        foreach ($configuration['blocks'] as $position => $blockConfiguration) {

            $blockConfiguration += array(
                'website'   => $website,
                'position'  => $position,
                'parent'    => $zone
            );

            $block = $this->createBlock($blockConfiguration);

            $zone->addBlock($block);
        }

        return $zone;
    }

    /**
     * Create a block
     *
     * @param array $blockConfiguration
     *
     * @return Block
     */
    public function createBlock(array $blockConfiguration)
    {
        $blockConfiguration += array(
            'settings'  => array(),
            'editable'  => true,
            'deletable' => true,
            'position'  => null,
            'parent'    => null
        );

        $parent = $blockConfiguration['parent'];
        if ($blockConfiguration['position'] == null && $parent != null) {
            //Add block case from BlockController
            $blockConfiguration['position'] = (count($parent->getBlocks()) + 1) * 10;
        }

        $block = new $this->blockModelClassName();
        if ($parent != null) {
            $block->setParentDocument($parent);
        } else {
            $block->setId($blockConfiguration['id']);
        }

        $block->setType($blockConfiguration['type']);
        if (isset($blockConfiguration['name']) && strlen($blockConfiguration['name'])) {
            $block->setName($blockConfiguration['name']);
        } else {
            $block->setName($blockConfiguration['type'] . '-' . $blockConfiguration['position']);
        }
        $block->setEditable($blockConfiguration['editable']);
        $block->setDeletable($blockConfiguration['deletable']);
        $block->setPosition($blockConfiguration['position']);
        $block->setEnabled(true);
        $this->getObjectManager()->persist($block);

        if (isset($blockConfiguration['children'])) {
            foreach ($blockConfiguration['children'] as $position => $childConfiguration) {
                $childConfiguration['website'] = $blockConfiguration['website'];
                $childConfiguration['id'] = $block->getId() . '/' . $childConfiguration['name'];
                $this->createBlock($childConfiguration);
            }
        }

        $website = $blockConfiguration['website'];
        foreach ($website->getAvailableLocales() as $locale) {
            if (isset($blockConfiguration['settings'][$locale])) {
                $block->setSettings($blockConfiguration['settings'][$locale]);
            } else {
                $block->setSettings($blockConfiguration['settings']);
            }

            $this->getObjectManager()->bindTranslation($block, $locale);
        }

        return $block;
    }
}
