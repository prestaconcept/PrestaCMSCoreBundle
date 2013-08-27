<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional\Model;

use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;

use Presta\CMSCoreBundle\Model\Website;

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
        return $this->container->get('presta_cms.manager.website');
    }

    public function testGetWebsite()
    {
        $websiteManager = $this->getWebsiteManager();

        $websiteManager->setCurrentEnvironment('dev');
        $prestaconceptWebsite = $websiteManager->loadWebsiteById(
            '/website/prestaconcept',
            'fr',
            'dev'
        );
        $this->assertEquals('dev', $websiteManager->getCurrentEnvironment());
        $this->assertEquals(true, $prestaconceptWebsite instanceof Website);
        $this->assertEquals('fr', $prestaconceptWebsite->getLocale());

        $websiteManager->setCurrentEnvironment('prod');
        $prestaconceptWebsite = $websiteManager->loadWebsiteById(
            '/website/prestaconcept',
            'en',
            'prod'
        );
        $this->assertEquals('prod', $websiteManager->getCurrentEnvironment());
        $this->assertEquals('en', $prestaconceptWebsite->getLocale());

        $websiteManager->setCurrentEnvironment('dev');
        $this->assertEquals(null, $websiteManager->loadWebsiteById('prestaconcept', 'fr', 'dev'));

        $websiteManager->setCurrentEnvironment('dev');
        $liipWebsite = $websiteManager->loadWebsiteById(
            '/website/liip',
            'fr',
            'dev'
        );
        $this->assertEquals('dev', $websiteManager->getCurrentEnvironment());
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

        $this->assertEquals(5, $websiteManager->getAvailableWebsites()->count());
    }

    public function testLoadWebsiteByHost()
    {
        $websiteManager = $this->getWebsiteManager();

        $websiteManager->registerWebsite(
            array(
                'path'   => '/website/liip',
                'hosts'      => array(
                    'dev' => array(
                        'fr' => array('locale' => 'fr', 'host' => 'www.liip.fr.local')
                    ),
                    'prod' => array(
                        'fr' => array('locale' => 'fr', 'host' => 'www.liip.fr')
                    )
                )
            )
        );

        $liipWebsite = $websiteManager->loadWebsiteByHost('www.liip.fr');
        $this->assertEquals('/website/liip', $liipWebsite->getPath());
        $this->assertEquals('liip', $liipWebsite->getName());

        $this->assertEquals($liipWebsite, $websiteManager->getCurrentWebsite());

        $this->assertEquals(false, $websiteManager->loadWebsiteByHost('www.no-website.com'));
    }
}
