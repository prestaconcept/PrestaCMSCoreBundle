<?php

namespace Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Model\Template;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Theme;
use Presta\CMSCoreBundle\Model\Website;
use PHPCR\Util\NodeHelper;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * @var ZoneFactory
     */
    protected $zoneFactory;

    /**
     * @param ZoneFactory $zoneFactory
     */
    public function setZoneFactory($zoneFactory)
    {
        $this->zoneFactory = $zoneFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $configuration += array(
            'cols' => 12,
            'admin_style' => null,
            'block_styles'  => array(),
            'zones' => array(),
            'page_template' => array(),
            'website' => null
        );

        $theme = new $this->modelClassName($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setTemplate($configuration['template']);
        $theme->setScreenshot($configuration['screenshot']);
        $theme->setAdminStyle($configuration['admin_style']);
        $theme->setBlockStyles($configuration['block_styles']);
        $theme->setCols($configuration['cols']);

        $website = $configuration['website'];
        $zones = array();

        if ($website != null) {
            $zones = array();
            $themeNode = $this->getObjectManager()->findTranslation(
                $this->modelClassName,
                $website->getId() . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . $configuration['name'],
                $website->getLocale()
            );

            if ($themeNode != null) {
                foreach ($themeNode->getZones() as $zone) {
                    foreach ($zone->getBlocks() as $block) {
                        $this->getObjectManager()->bindTranslation($block, $website->getLocale());
                    }
                    $zones[$zone->getName()] = $zone;

                }
            }
        }

        foreach ($configuration['zones'] as $zoneConfiguration) {

            if (!isset($zones[$zoneConfiguration['name']])) {
                $zone = new Zone($zoneConfiguration['name']);
            } else {
                $zone = $zones[$zoneConfiguration['name']];
            }

            $zone->setConfiguration($zoneConfiguration);
            $theme->addZone($zone);
        }

        foreach ($configuration['page_template'] as $templateName => $templateConfiguration) {
            $template = new Template($templateName, $templateConfiguration['path']);

            $zones = array();

            foreach ($templateConfiguration["zones"] as $zoneName => $zoneConfiguration) {
                $zone = new Zone($zoneName);
                $zone->setConfiguration($zoneConfiguration);
                $zones[$zoneName] = $zone;
            }

            $template->setZones($zones);
            $theme->addPageTemplate($template);
        }

        return $theme;
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
        $session = $this->getObjectManager()->getPhpcrSession();
        NodeHelper::createPath($session, $website->getPath() . '/theme');

        //Create website theme default association
        $websiteTheme = new Theme();
        $websiteTheme->setParent($website);
        $websiteTheme->setName('theme/' . $configuration['name']);
        $this->getObjectManager()->persist($websiteTheme);

        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (!isset($zoneConfiguration['blocks']) || count($zoneConfiguration['blocks']) == 0) {
                continue;
            }

            $zoneConfiguration['parent'] = $websiteTheme;
            $zoneConfiguration['website'] = $website;
            $websiteThemeZone = $this->zoneFactory->create($zoneConfiguration);

            $websiteTheme->addZone($websiteThemeZone);
        }
        $this->getObjectManager()->flush();

        return $websiteTheme->getZones();
    }
}
