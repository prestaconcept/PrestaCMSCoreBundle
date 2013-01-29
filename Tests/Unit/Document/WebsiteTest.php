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
use Presta\CMSCoreBundle\Document\Website;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteTest extends BaseUnitTestCase
{
    public function testConstruct()
    {
        $website = new Website();

        $this->assertFalse($website->isDefault());
        $this->assertTrue($website->isActive());
    }

    public function testWebsite()
    {
        $website = new Website();

        $this->assertNull($website->getDefaultLocale());
        $website->setAvailableLocales(array('fr', 'de', 'en'));
        $this->assertEquals(array('fr', 'de', 'en'), $website->getAvailableLocales());
        $this->assertEquals('fr', $website->getDefaultLocale());
        $website->setDefaultLocale('en');
        $this->assertEquals('en', $website->getDefaultLocale());
        $website->setAvailableLocales(array('de', 'en'));
        $this->assertEquals('en', $website->getDefaultLocale());

        $this->assertFalse($website->isDefault());
        $website->setIsDefault(true);
        $this->assertTrue($website->isDefault());

        $website->setName('default');
        $this->assertEquals('default', $website->__toString());
    }
}

