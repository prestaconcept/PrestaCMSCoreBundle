<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\WebTest\Render;

use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageRenderTest extends BaseFunctionalTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
    }

    /**
     * @test SimpleBlock
     */
    public function testSimpleBlockRendering()
    {
        $crawler = $this->client->request('GET', '/page-children/block-simple');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("This is a testing application for PrestaCMSCoreBundle")'));
        $this->assertCount(1, $crawler->filter('html:contains("this is just a simple text block")'));
    }

    /**
     * @test SitemapBlock
     */
    public function testSitemapBlockRendering()
    {
        $crawler = $this->client->request('GET', '/page-children/block-sitemap');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("This is a sitemap block")'));
    }

    /**
     * @test ContainerBlock
     */
    public function testContainerBlockRendering()
    {
        $crawler = $this->client->request('GET', '/page-children/block-container');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("Container example")'));
    }

    /**
     * @test PageChildrenBlock
     */
    public function testPageChildrenBlockRendering()
    {
        $crawler = $this->client->request('GET', '/page-children');
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('html:contains("This is a page children block")'));
        $this->assertCount(1, $crawler->filter('html:contains("this is just a example of a list of children page")'));
    }
}
