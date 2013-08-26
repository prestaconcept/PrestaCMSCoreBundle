<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
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
     * Return ThemeManager initialized with fixture configuration
     *
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
     * @test ThemeManager::hasTheme()
     */
    public function testHasTheme()
    {
        $themeManager = $this->getThemeManager();

        $this->assertEquals(false, $themeManager->hasTheme('no-theme'));

        $this->assertEquals(true, $themeManager->hasTheme('prestaconcept'));
        $this->assertEquals(true, $themeManager->hasTheme('default'));
        $this->assertEquals(true, $themeManager->hasTheme('liip'));
    }
}
