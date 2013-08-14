<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional\Model;

use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Document\Theme;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManagerTest extends BaseFunctionalTestCase
{
    /**
     * @return \Presta\CMSCoreBundle\Model\ThemeManager
     */
    protected function getThemeManager()
    {
        $themeManager = $this->container->get('presta_cms.manager.theme');

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../../Functional/fixtures/themes.yml'));

        foreach ($datas['themes'] as $themeConfiguration) {
            $themeManager->addThemeConfiguration($themeConfiguration);
        }

        return $themeManager;
    }

    protected function getDefaultWebsite()
    {
        $websiteManager = $this->container->get('presta_cms.manager.website');

        $websiteManager->setCurrentEnvironment('dev');
        
        return $websiteManager->loadWebsiteById('/website/default', 'en', 'dev');
    }

    public function testGetThemeWithData()
    {
        $themeManager = $this->getThemeManager();

        $website = $this->getDefaultWebsite();
        //Database initialisation
        $theme = $themeManager->getTheme('default', $website);

        $this->assertEquals(5, count($theme->getZones()));

        //Fetching database data
        $theme = $themeManager->getTheme('default', $website);

        $this->assertEquals(5, count($theme->getZones()));
    }
}
