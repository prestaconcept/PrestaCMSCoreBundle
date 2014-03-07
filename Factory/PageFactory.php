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

use Presta\CMSCoreBundle\Doctrine\Phpcr\Page;
use Presta\CMSCoreBundle\Model\Page\PageTypeCMSPage;
use Presta\CMSCoreBundle\Model\Website;
use Symfony\Cmf\Bundle\CoreBundle\Slugifier\SlugifierInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'default';

    /**
     * @var ZoneFactory
     */
    protected $zoneFactory;

    /**
     * @var SlugifierInterface
     */
    protected $slugifier;

    /**
     * @param ZoneFactory $zoneFactory
     */
    public function setZoneFactory($zoneFactory)
    {
        $this->zoneFactory = $zoneFactory;
    }

    /**
     * @param SlugifierInterface $slugifier
     */
    public function setSlugifier(SlugifierInterface $slugifier)
    {
        $this->slugifier = $slugifier;
    }

    /**
     * Default page configuration
     *
     * @param  array $configuration
     * @return array
     */
    protected function configurePage(array $configuration)
    {
        $configuration += array(
            'type'     => PageTypeCMSPage::SERVICE_ID,
            'meta'     => array('title' => array(), 'keywords' => array(), 'description' => array()),
            'template' => $this->defaultTemplate,
            'zones'    => array(),
            'children' => null
        );
        $configuration = $this->configureZones($configuration);

        foreach ($configuration['zones'] as $zoneName => $zone) {
            if (!isset($zone['blocks'])) {
                continue;
            }
            foreach ($zone['blocks'] as $position => $blockConfiguration) {
                $configuration['zones'][$zoneName]['blocks'][$position] = $this->configureBlock($blockConfiguration);
            }
        }

        return $configuration;
    }

    /**
     * Initialise zone and block
     *
     * @param  array $configuration
     * @return array
     */
    protected function configureZones(array $configuration)
    {
        return $configuration;
    }

    /**
     * Default block configuration
     *
     * @param  array $block
     * @return array
     */
    protected function configureBlock(array $block)
    {
        $block += array(
            'name'          => null,
            'editable'      => false,
            'deletable'     => false,
            'settings'      => array(),
            'children'      => array()
        );
        if (!is_array($block['settings'])) {
            $block['settings'] = array();
        }

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $configuration = $this->configurePage($configuration);
        $website = $configuration['website'];

        /** @var Page */
        $page = new $this->modelClassName();
        $page->setName($configuration['name']);
        $page->setParent($configuration['parent']);
        $page->setEnabled(true);
        $page->setType($configuration['type']);
        $page->setTemplate($configuration['template']);
        $page->setLastCacheModifiedDate(new \DateTime());
        $page->setUrlCompleteMode(false);
        $this->getObjectManager()->persist($page);

        $meta = $configuration['meta'];

        foreach ($website->getAvailableLocales() as $locale) {
            $page->setLocale($locale);
            $page->setTitle(isset($meta['title'][$locale]) ? $meta['title'][$locale] : $configuration['name']);
            $page->setMetaDescription(isset($meta['description'][$locale]) ? $meta['description'][$locale] : '');
            $page->setMetaKeywords(isset($meta['keywords'][$locale]) ? $meta['keywords'][$locale] : '');
            $this->getObjectManager()->bindTranslation($page, $locale);
        }

        //Creation des blocks
        if ($configuration['zones'] != null) {
            foreach ($configuration['zones'] as $zoneConfiguration) {
                if (!isset($zoneConfiguration['blocks']) || count($zoneConfiguration['blocks']) == 0) {
                    continue;
                }

                $zoneConfiguration['parent'] = $page;
                $zoneConfiguration['website'] = $website;

                $pageZone = $this->zoneFactory->create($zoneConfiguration);
                $page->addZone($pageZone);
            }
        }

        if ($configuration['children'] != null) {
            foreach ($configuration['children'] as $childConfiguration) {
                $childConfiguration['parent'] = $page;
                $childConfiguration['website'] = $website;
                $this->create($childConfiguration);
            }
        }

        return $page;
    }

    /**
     * Create configuration for page construction
     *
     * @param Website $website
     * @param string  $parentId
     * @param string  $title
     * @param string  $template
     *
     * @return array
     */
    public function getConfiguration(Website $website, $parentId, $title, $template)
    {
        $configuration = array(
            'website'   => $website,
            'meta'      => array('title' => array($website->getLocale() => $title)),
            'type'      => PageTypeCMSPage::SERVICE_ID,
            'template'  => $template
        );

        if ($parentId === null) {
            $parentId = $website->getPageRoot();
        }

        $configuration['parent'] = $this->getObjectManager()->find(null, $parentId);
        $configuration['name']   = (is_null($title)) ? uniqid('page-') : $this->slugifier->slugify($title);

        return $configuration;
    }
}
