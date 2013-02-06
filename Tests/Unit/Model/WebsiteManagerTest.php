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
use Presta\CMSCoreBundle\Model\WebsiteManager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManagerTest extends BaseUnitTestCase
{
    const DEFAULT_WEBSITE_CODE = 'default_website';

    /**
     * @return \Presta\CMSCoreBundle\Model\WebsiteManager
     */
    protected function getWebsiteManager()
    {
        $websiteManager = new WebsiteManager();

        return $websiteManager;
    }

    public function testDefaultWebsite()
    {
        $websiteManager = $this->getWebsiteManager();
        $this->assertNotEquals(self::DEFAULT_WEBSITE_CODE, $websiteManager->getDefaultWebsiteCode());
        $websiteManager->setDefaultWebsiteCode(self::DEFAULT_WEBSITE_CODE);
        $this->assertEquals(self::DEFAULT_WEBSITE_CODE, $websiteManager->getDefaultWebsiteCode());
    }

    public function testMultipleHost()
    {
        $websiteManager = $this->getWebsiteManager();
        $this->assertEquals(false, $websiteManager->getMultipleWebsite());
        $websiteManager->setMultipleWebsite(true);
        $this->assertEquals(true, $websiteManager->getMultipleWebsite());
        $websiteManager->setMultipleWebsite(false);
        $this->assertEquals(false, $websiteManager->getMultipleWebsite());

        //@next see to and logic when add hosts
    }

    public function testRegisterHost()
    {
        $websiteManager = $this->getWebsiteManager();

        $websiteManager->registerHost(array(
            'host'      => 'www.prestaconcept.net',
            'website'   => '/website/prestaconcept',
            'locale'    =>  'fr'
        ));
        $websiteManager->registerHost(array(
            'host'      => 'www.liip.ch',
            'website'   => '/website/liip',
            'locale'    =>  'fr'
        ));

        $websiteManager->registerHost(array(
            'host'      => 'www.symfony.com',
            'website'   => '/website/symfony',
            'locale'    =>  'en'
        ));

        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.prestaconcept.net'));
        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.liip.ch'));

        //www
        $this->assertEquals(false, $websiteManager->hasHostRegistered('prestaconcept.net'));
        $this->assertEquals(false, $websiteManager->hasHostRegistered('liip.ch'));

        //Extension
        $this->assertEquals(false, $websiteManager->hasHostRegistered('www.prestaconcept.com'));
        $this->assertEquals(false, $websiteManager->hasHostRegistered('www.liip.com'));

        //@next see to add exception on malformed configuration
    }

    public function testSetCurrentWebsite()
    {
        $websiteManager = $this->getWebsiteManager();
        $this->assertEquals(null, $websiteManager->getCurrentWebsite());
        $websiteManager->setCurrentWebsite(null);
        $this->assertEquals(null, $websiteManager->getCurrentWebsite());
    }

}
