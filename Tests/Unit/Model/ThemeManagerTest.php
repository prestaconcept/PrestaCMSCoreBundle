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

use Presta\CMSCoreBundle\Doctrine\Phpcr\Theme;
use Presta\CMSCoreBundle\Model\ThemeManager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManagerTest extends BaseUnitTestCase
{
    /**
     * @return ThemeManager
     */
    protected function getThemeManager()
    {
        $themeManager = new ThemeManager();

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(FIXTURES_DIR . 'themes.yml'));

        foreach ($datas['themes'] as $themeConfiguration) {
            $themeManager->addThemeConfiguration($themeConfiguration);
        }

        return $themeManager;
    }

    /**
     * @test ThemeManager::getAvailableThemeCodes()
     */
    public function testGetAvailableThemeCode()
    {
        $themeManager = $this->getThemeManager();

        $this->assertEquals(array('default', 'prestaconcept', 'liip'), $themeManager->getAvailableThemeCodes());
    }

    /**
     * @test ThemeManager::getAvailableThemeCodesForSelect()
     */
    public function testGetAvailableThemeCodesForSelect()
    {
        $themeManager = $this->getThemeManager();

        $this->assertEquals(
            array('default' => 'default', 'prestaconcept' => 'prestaconcept', 'liip' => 'liip'),
            $themeManager->getAvailableThemeCodesForSelect()
        );
    }

    /**
     * @test ThemeManager::getTheme()
     */
    public function testGetThemeError()
    {
        $themeManager = $this->getThemeManager();

        $theme = $themeManager->getTheme('no-theme');
        $this->assertEquals(false, $theme);
    }


    //Older test, as we have factory now, we should create new unit test for factory and move this to functional

    //
    //    public function testGetThemeFullConfiguration()
    //    {
    //        $themeManager = $this->getThemeManager();
    //
    //        $theme = $themeManager->getTheme('default');
    //        $this->assertEquals($theme, $themeManager->getCurrentTheme());
    //        $this->assertTrue($theme instanceof Theme);
    //        $this->assertEquals('default', $theme->getName());
    //        $this->assertEquals('Presta CMS default theme', $theme->getDescription());
    //        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default:layout.html.twig', $theme->getTemplate());
    //        $this->assertEquals('bundles/prestacmscore/theme/default/screenshot.jpg', $theme->getScreenshot());
    //        $this->assertEquals('bundles/prestacmscore/theme/prestaconcept/admin/admin.css', $theme->getAdminStyle());
    //        $this->assertEquals(12, $theme->getCols());
    //        $this->assertEquals(5, count($theme->getZones()));
    //
    //        //@todo test zone
    //
    //        $this->assertEquals(2, count($theme->getPageTemplates()));
    //    }
    //
    //    public function testGetThemeMinimalConfiguration()
    //    {
    //        $themeManager = $this->getThemeManager();
    //
    //        $theme = $themeManager->getTheme('liip');
    //        $this->assertTrue($theme instanceof Theme);
    //        $this->assertEquals('liip', $theme->getName());
    //        $this->assertEquals(12, $theme->getCols());
    //        $this->assertEquals(0, count($theme->getZones()));
    //        $this->assertEquals(0, count($theme->getPageTemplates()));
    //        $this->assertEquals(null, $theme->getAdminStyle());
    //    }
    //
    //    public function testGetPageTemplateFile()
    //    {
    //        $themeManager = $this->getThemeManager();
    //
    //        $template = $themeManager->getPageTemplateFile('default');
    //        $this->assertEquals(false, $template); //Current theme is not setted
    //
    //        $theme = $themeManager->getTheme('default');
    //        $template = $themeManager->getPageTemplateFile('default');
    //        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default/Page:default.html.twig', $template);
    //
    //        $template = $themeManager->getPageTemplateFile('sidebar');
    //        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default/Page:sidebar.html.twig', $template);
    //
    //        $template = $themeManager->getPageTemplateFile('no-template');
    //        $this->assertEquals(false, $template);
    //    }
    //
    //    public function testGetPageTemplateConfiguration()
    //    {
    //        $themeManager = $this->getThemeManager();
    //
    //        $configuration = $themeManager->getPageTemplateConfiguration('default');
    //        $this->assertEquals(false, $configuration); //Current theme is not setted
    //
    //        $theme = $themeManager->getTheme('default');
    //        $configuration = $themeManager->getPageTemplateConfiguration('default');
    //
    //        $this->assertEquals('default', $configuration['name']);
    //        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default/Page:default.html.twig', $configuration['path']);
    //        $this->assertEquals(1, count($configuration['zones']));
    //        $this->assertEquals(6, count($configuration['zones']['content']));
    //
    //        //todo default initialisation !
    //    }
}
