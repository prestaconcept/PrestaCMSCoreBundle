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
use Presta\CMSCoreBundle\Model\Template;
use Presta\CMSCoreBundle\Model\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class TemplateTest extends BaseUnitTestCase
{
    public function testConstruct()
    {
        $template = new Template('default', 'PrestaCMSCoreBundle:Theme/Default/Page:default.html.twig');

        $this->assertEquals('default', $template->getName());
        $this->assertEquals('PrestaCMSCoreBundle:Theme/Default/Page:default.html.twig', $template->getPath());
    }

    public function testZones()
    {
        $template = new Template('default', 'PrestaCMSCoreBundle:Theme/Default/Page:default.html.twig');

        $this->assertEquals(0, count($template->getZones()));

//        $zoneContent = new Zone('content', array());
//        $template->addZone($zoneContent);
//
//        $this->assertEquals(1, count($template->getZones()));

    }




}

