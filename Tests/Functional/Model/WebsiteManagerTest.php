<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional\Model;

use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;

use Presta\CMSCoreBundle\Document\Website;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManagerTest extends BaseFunctionalTestCase
{
    /**
     * @return \Presta\CMSCoreBundle\Model\WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->container->get('presta_cms.website_manager');
    }

    public function testGetWebsite()
    {
        $websiteManager = $this->getWebsiteManager();

        $prestaconceptWebsite = $websiteManager->getWebsite('/website/prestaconcept', 'fr');

        $this->assertEquals(true, $prestaconceptWebsite instanceof Website);
        $this->assertEquals('fr', $prestaconceptWebsite->getLocale());

        $prestaconceptWebsite = $websiteManager->getWebsite('/website/prestaconcept', 'en');
        $this->assertEquals('en', $prestaconceptWebsite->getLocale());

        $this->assertEquals(null, $websiteManager->getWebsite('prestaconcept', 'en'));

        $liipWebsite = $websiteManager->getWebsite('/website/liip', 'fr');
        $this->assertEquals('liip', $liipWebsite->getName());
        $this->assertEquals(array('fr', 'en', 'de'), $liipWebsite->getAvailableLocales());
        $this->assertEquals('liip', $liipWebsite->getTheme());
        $this->assertEquals('fr', $liipWebsite->getDefaultLocale());
        $this->assertEquals(false, $liipWebsite->isDefault());
        $this->assertEquals('/website/liip', $liipWebsite->getPath());
    }

    public function testGetAvailableWebsites()
    {
        $websiteManager = $this->getWebsiteManager();

        $this->assertEquals(4, $websiteManager->getAvailableWebsites()->count());
    }

    public function testLoadWebsiteByHost()
    {
        $websiteManager = $this->getWebsiteManager();
        $websiteManager->setDefaultWebsiteCode('/website/default');

        $websiteManager->registerHost(
            array(
                'host'      => 'www.liip.ch',
                'website'   => '/website/liip',
                'locale'    =>  'fr'
            )
        );

        $defaultWebsite = $websiteManager->loadWebsiteByHost('www.no-website.com');
        $this->assertEquals('/website/default', $defaultWebsite->getPath());
        $this->assertEquals('default', $defaultWebsite->getName());

        $liipWebsite = $websiteManager->loadWebsiteByHost('www.liip.ch');
        $this->assertEquals('/website/liip', $liipWebsite->getPath());
        $this->assertEquals('liip', $liipWebsite->getName());

        $this->assertEquals($liipWebsite, $websiteManager->getCurrentWebsite());
    }
}
