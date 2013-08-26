<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional\Model;

use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\Website;
use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;
use Symfony\Component\Yaml\Parser;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManagerTest extends BaseFunctionalTestCase
{
    /**
     * @return ThemeManager
     */
    protected function getThemeManager()
    {
        $themeManager = $this->container->get('presta_cms.manager.theme');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(FIXTURES_DIR . 'themes.yml'));

        foreach ($datas['themes'] as $themeConfiguration) {
            $themeManager->addThemeConfiguration($themeConfiguration);
        }

        return $themeManager;
    }

    /**
     * @return Website
     */
    protected function getDefaultWebsite()
    {
        $websiteManager = $this->container->get('presta_cms.manager.website');

        $websiteManager->setCurrentEnvironment('dev');

        return $websiteManager->loadWebsiteById('/website/default', 'en', 'dev');
    }

    /**
     * @test ThemeManager::getTheme()
     */
    public function testGetTheme()
    {
        $themeManager = $this->getThemeManager();

        //Database initialisation
        $theme = $themeManager->getTheme('default');

        $this->assertEquals(5, count($theme->getZones()));

        $website = $this->getDefaultWebsite();

        //Database initialisation
        $theme = $themeManager->getTheme('default', $website);

        $this->assertEquals(5, count($theme->getZones()));
    }

    /**
     * @test ThemeManager::getThemeCurrentTheme()
     */
    public function testGetCurrentTheme()
    {
        $themeManager = $this->getThemeManager();
        $theme = $themeManager->getTheme('default');

        $currentTheme = $themeManager->getCurrentTheme();
        $this->assertEquals('default', $currentTheme->getName());
    }

    /**
     * @test ThemeManager::getAvailableThemes()
     */
    public function testGetAvailableThemes()
    {
        $themeManager = $this->getThemeManager();

        $this->assertEquals(
            array('default' => 'Default', 'prestaconcept' => 'Prestaconcept', 'liip' => 'Liip'),
            $themeManager->getAvailableThemes()
        );
    }
}
