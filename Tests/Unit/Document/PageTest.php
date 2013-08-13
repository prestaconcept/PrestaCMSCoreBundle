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
use Doctrine\Common\Collections\ArrayCollection;

use Presta\CMSCoreBundle\Document\Page;
use Presta\CMSCoreBundle\Document\Zone;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTest extends BaseUnitTestCase
{
    public function testPage()
    {
        $page = new Page();

        $page->setIsActive(true);
        $this->assertEquals(true, $page->isActive());

        $page->setMetaDescription('this is a great page description');
        $this->assertEquals('this is a great page description', $page->getMetaDescription());

        $this->assertEquals('this is a great page description', $page->getDescription());

        $page->setMetaKeywords('great, awesome, even great page');
        $this->assertEquals('great, awesome, even great page', $page->getMetaKeywords());

        $page->setType('presta_cms');
        $this->assertEquals('presta_cms', $page->getType());

        //@todo test SEO
//        $page->setUrl('awesome-page.html');
//        $this->assertEquals('awesome-page.html', $page->getUrl());

        $this->assertEquals($page, $page->getRouteContent());

        $page->setTemplate('default');
        $this->assertEquals('default', $page->getTemplate());
    }

//    public function testChildren()
//    {
//        $page = new Page();
//
//        $childOne = new Page();
//        $page->addChild($childOne);
//        $this->assertEquals(1, count($page->getChildren()));
//
//        $childTwo = new Page();
//        $page->addChild($childTwo);
//        $this->assertEquals(2, count($page->getChildren()));
//
//        $children = $page->getChildren();
//
//        $page->setChildren(new ArrayCollection());
//        $this->assertEquals(0, count($page->getChildren()));
//        $page->setChildren($children);
//        $this->assertEquals(2, count($page->getChildren()));
//    }

//    public function testZone()
//    {
//        $page = new Page();
//
//        $zoneOne = new Zone('content');
//        $page->addZone($zoneOne);
//        $this->assertEquals(1, count($page->getZones()));
//
//        $zoneTwo = new Zone('sidebar');
//        $page->addZone($zoneTwo);
//        $this->assertEquals(2, count($page->getZones()));
//    }

//    public function testChildrenAndZone()
//    {
//        $page = new Page();
//
//        $childOne = new Page();
//        $page->addChild($childOne);
//        $this->assertEquals(1, count($page->getChildren()));
//
//        $zoneOne = new Zone('content');
//        $page->addZone($zoneOne);
//        $this->assertEquals(1, count($page->getZones()));
//    }
}
