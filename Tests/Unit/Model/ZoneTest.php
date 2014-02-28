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

use Presta\CMSCoreBundle\Doctrine\Phpcr\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ZoneTest extends BaseUnitTestCase
{
    /**
     * @test Zone::getHtmlId()
     */
    public function testHtmlId()
    {
        $zone = new Zone('content');
        $zone->setId('/website/default/theme/.._../__._default/content');

        $this->assertEquals('websitedefaultthemedefaultcontent', $zone->getHtmlId());
    }
}
