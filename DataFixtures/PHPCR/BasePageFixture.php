<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Doctrine\ODM\PHPCR\Document\Generic;

use Presta\CMSCoreBundle\Document\Page;
use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;
use Presta\CMSCoreBundle\Model\Page\PageTypeCMSPage;

/**
 * Base fixtures methods to easily create pages
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BasePageFixture extends BaseFixture
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'default';

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * Default page configuration
     *
     * @param  array $page
     * @return array
     */
    protected function configurePage(array $page)
    {
        $page += array(
            'type'     => PageTypeCMSPage::SERVICE_ID,
            'meta'     => array('title' => array(), 'keywords' => array(), 'description' => array()),
            'template' => $this->defaultTemplate,
            'zones'    => null,
            'children' => null
        );
        $page = $this->configureZones($page);

        return $page;
    }

    /**
     * Initialise zone and block
     *
     * @param  array $page
     * @return array
     */
    abstract protected function configureZones(array $page);

    /**
     * Default block configuration
     *
     * @param  array $block
     * @return array
     */
    protected function configureBlock(array $block)
    {
        $block += array(
            'name'         => null,
            'is_editable'  => false,
            'is_deletable' => false,
            'settings'     => array(),
            'children'     => array()
        );
        if (!is_array($block['settings'])) {
            $block['settings'] = array();
        }

        return $block;
    }

    /**
     * Create a Page document
     *
     * @param  array        $pageConfiguration
     * @param  Page|Generic $root
     * @return Page
     */
    protected function createPage($pageConfiguration, $root)
    {
        $pageConfiguration = $this->configurePage($pageConfiguration);

        $page = new Page();
        $page->setName($pageConfiguration['name']);
        $page->setParent($root);
        $page->setIsActive(true);
        $page->setType($pageConfiguration['type']);
        $page->setTemplate($pageConfiguration['template']);
        $page->setLastCacheModifiedDate(new \DateTime());
        $this->manager->persist($page);

        $meta = $pageConfiguration['meta'];
        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $page->setTitle(isset($meta['title'][$locale]) ? $meta['title'][$locale] : '');
            $page->setMetaDescription(isset($meta['description'][$locale]) ? $meta['description'][$locale] : '');
            $page->setMetaKeywords(isset($meta['keywords'][$locale]) ? $meta['keywords'][$locale] : '');
            $this->manager->bindTranslation($page, $locale);
        }

        //Creation des blocks
        if ($pageConfiguration['zones'] != null) {
            foreach ($pageConfiguration['zones'] as $zoneName => $zone) {
                $pageZone = new Zone();
                $pageZone->setParentDocument($page);
                $pageZone->setName($zoneName);
                $this->manager->persist($pageZone);
                foreach ($zone as $position => $blockConfiguration) {
                    $this->createBlock($blockConfiguration, $pageZone, $position);
                }
            }
        }

        if ($pageConfiguration['children'] != null) {
            foreach ($pageConfiguration['children'] as $child) {
                $this->createPage($child, $page);
            }
        }

        return $page;
    }

    /**
     * Create a block
     *
     * @param $blockConfiguration
     * @param $parent
     * @param $position
     * @return Block
     */
    protected function createBlock($blockConfiguration, $parent, $position)
    {
        $blockConfiguration = $this->configureBlock($blockConfiguration);
        $block = new Block();
        $block->setParent($parent);
        $block->setType($blockConfiguration['type']);
        $block->setName((strlen($blockConfiguration['name'])) ? $blockConfiguration['name'] : $blockConfiguration['type'] . '-' . $position);
        $block->setIsEditable($blockConfiguration['is_editable']);
        $block->setIsDeletable($blockConfiguration['is_deletable']);
        $block->setPosition($position);
        $block->setIsActive(true);

        $this->manager->persist($block);
        foreach ($this->getLocales() as $locale) {
            $settings = (isset($blockConfiguration['settings'][$locale])) ? $blockConfiguration['settings'][$locale] : $blockConfiguration['settings'];
            $block->setSettings($settings);
            $this->manager->bindTranslation($block, $locale);
        }

        foreach ($blockConfiguration['children'] as $position => $childConfiguration) {
            $this->createBlock($childConfiguration, $block, $position);
        }

        return $block;
    }
}
