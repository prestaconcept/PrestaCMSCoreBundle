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

use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Tests\Unit\BaseUnitTestCase;
use Presta\CMSCoreBundle\Model\WebsiteManager;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManagerTest extends BaseUnitTestCase
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @return WebsiteManager
     */
    protected function getWebsiteManager()
    {
        if (is_null($this->websiteManager)) {
            $this->websiteManager = new WebsiteManager();
        }

        return $this->websiteManager;
    }

    protected function loadWebsites($file)
    {
        $websiteManager = $this->getWebsiteManager();

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents($file));
        $config = $datas['presta_cms_core'];

        if (isset($config['websites']) && is_array($config['websites'])) {
            foreach ($config['websites'] as $websiteConfiguration) {
                $websiteManager->registerWebsite($websiteConfiguration);
            }
        }
    }

    public function testMultipleWebsite()
    {
        $websiteManager = $this->getWebsiteManager();

        $this->assertEquals(false, $websiteManager->hasMultipleWebsite());

        //Load sandbox website
        $this->loadWebsites(__DIR__ . '/../../Functional/config/prestacmscore.yml');

        $this->assertEquals(false, $websiteManager->hasMultipleWebsite());

        //Load multiple website
        $this->loadWebsites(__DIR__ . '/../fixtures/websites.yml');
        $this->assertEquals(true, $websiteManager->hasMultipleWebsite());
    }

    public function testRegisterWebsite()
    {
        $websiteManager = $this->getWebsiteManager();

        //Load sandbox website
        $this->loadWebsites(__DIR__ . '/../../Functional/config/prestacmscore.yml');
        $this->loadWebsites(__DIR__ . '/../fixtures/websites.yml');

        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.prestaconcept.net'));
        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.prestaconcept.local'));
        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.symfony.fr'));
        $this->assertEquals(true, $websiteManager->hasHostRegistered('docs.doctrine-project.org'));
        $this->assertEquals(true, $websiteManager->hasHostRegistered('www.symfony.fr'));

        //www
        $this->assertEquals(false, $websiteManager->hasHostRegistered('prestaconcept.net'));
        $this->assertEquals(false, $websiteManager->hasHostRegistered('symfony.com'));

        //Extension
        $this->assertEquals(false, $websiteManager->hasHostRegistered('www.prestaconcept.com'));
        $this->assertEquals(false, $websiteManager->hasHostRegistered('www.symfony.ch'));

        //@next see to add exception on malformed configuration
    }

    public function testSetCurrentWebsite()
    {
        $websiteManager = $this->getWebsiteManager();
        $this->assertEquals(null, $websiteManager->getCurrentWebsite());

        // @todo: find an other way to test setCurrentWebsite()
        // $websiteManager->setCurrentWebsite(null);
        // $this->assertEquals(null, $websiteManager->getCurrentWebsite());
    }
}
