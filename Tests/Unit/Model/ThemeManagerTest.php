<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit\Model;

use Presta\CMSCoreBundle\Tests\Unit\BaseUnitTestCase;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Model\ThemeManager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManagerTest extends BaseUnitTestCase
{
    /**
     * @return \Presta\CMSCoreBundle\Model\ThemeManager
     */
    protected function getThemeManager()
    {
        $themeManager = new ThemeManager();

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/../../Functional/fixtures/themes.yml'));

        foreach ($datas['themes'] as $themeConfiguration) {
            $themeManager->addThemeConfiguration($themeConfiguration);
        }

        return $themeManager;
    }


    public function testGetAvailableThemes()
    {
        $themeManager = $this->getThemeManager();

        $availableThemes = $themeManager->getAvailableThemes();

        $this->assertEquals(3, count($availableThemes));

        $this->assertEquals(array('default', 'prestaconcept', 'liip'), $themeManager->getAvailableThemeCodes());

        $this->assertEquals(
            array('default' => 'default', 'prestaconcept' => 'prestaconcept', 'liip' => 'liip'),
            $themeManager->getAvailableThemeCodesForSelect()
        );


    }

    public function testGetThemeFullConfiguration()
    {
        $themeManager = $this->getThemeManager();

        $theme = $themeManager->getTheme('default');
        $this->assertTrue($theme instanceof Theme);
        $this->assertEquals('default', $theme->getName());
        $this->assertEquals('Presta CMS default theme', $theme->getDescription());
        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default:layout.html.twig', $theme->getTemplate());
        $this->assertEquals('bundles/prestacmscore/theme/default/screenshot.jpg', $theme->getScreenshot());
        $this->assertEquals('bundles/prestacmscore/theme/prestaconcept/admin/admin.css', $theme->getAdminStyle());
        $this->assertEquals(12, $theme->getCols());
        $this->assertEquals(5, count($theme->getZones()));

        //@todo test zone

        $this->assertEquals(2, count($theme->getPageTemplates()));

        //@todo test page template
    }

    public function testGetThemeMinimalConfiguration()
    {
        $themeManager = $this->getThemeManager();

        $theme = $themeManager->getTheme('liip');
        $this->assertTrue($theme instanceof Theme);
        $this->assertEquals('liip', $theme->getName());
        $this->assertEquals(12, $theme->getCols());
        $this->assertEquals(0, count($theme->getZones()));
        $this->assertEquals(0, count($theme->getPageTemplates()));
        $this->assertEquals(null, $theme->getAdminStyle());
    }


}

