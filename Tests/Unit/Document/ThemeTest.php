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
use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Document\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeTest extends BaseUnitTestCase
{
    public function testConstruct()
    {
        $theme = new Theme('default');

        $this->assertEquals('default', $theme->getName());
        $this->assertEquals(0, count($theme->getZones()));
        $this->assertEquals(0, count($theme->getPageTemplates()));
    }

    public function testTheme()
    {
        $theme = new Theme('default');

        $this->assertEquals('default', $theme->__toString());

        $theme->setPageTemplates(array());
        $this->assertEquals(0, count($theme->getPageTemplates()));

        $zoneContent = new Zone('content');
        $theme->addZone($zoneContent);

        $this->assertEquals(1, count($theme->getZones()));
        $this->assertEquals('content', $theme->getZones()->get('content')->getName());
    }
}

