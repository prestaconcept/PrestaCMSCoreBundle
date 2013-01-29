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

use Presta\CMSCoreBundle\Document\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ZoneTest extends BaseUnitTestCase
{
    public function testConstruct()
    {
        $zone = new Zone('content');

        $this->assertEquals('content', $zone->getName());
        $this->assertEquals(false, $zone->getCanAddBlock());
        $this->assertEquals(false, $zone->getCanSortBlock());

        $this->assertEquals('presta_cms.zone', $zone->getType());
    }

    public function testSetConfiguration()
    {
        $zone = new Zone('content');

        $zone->setConfiguration(array());
        $this->assertEquals(12, $zone->getCols());
        $this->assertEquals(1, $zone->getRows());
        $this->assertEquals(false, $zone->getCanAddBlock());
        $this->assertEquals(false, $zone->getCanSortBlock());

        $zone->setConfiguration(array(
            'cols' => 3,
            'rows' => 12,
            'can_add_block'  => true,
            'can_sort_block' => true
        ));
        $this->assertEquals(3, $zone->getCols());
        $this->assertEquals(12, $zone->getRows());
        $this->assertEquals(true, $zone->getCanAddBlock());
        $this->assertEquals(true, $zone->getCanSortBlock());
    }

    public function testHtmlId()
    {
        $zone = new Zone('content');
        $zone->setId('/website/default/theme/.._../__._default/content');

        $this->assertEquals('websitedefaultthemedefaultcontent', $zone->getHtmlId());
    }
}

