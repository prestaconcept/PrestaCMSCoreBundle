<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\WebTest\Admin;

use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockAdminTest extends BaseFunctionalTestCase
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
     * Admin edition test case for Simple block
     */
    public function testBlockSimpleEdit()
    {
        $urlParams = '?website=/website/sandbox&id=/website/sandbox/theme/test/footer_left/presta_cms.block.simple-10';

        $crawler = $this->client->request('GET', '/admin/cms/block/edit/en' . $urlParams);
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('input[value="About US"]'));

        $crawler = $this->client->request('GET', '/admin/cms/block/edit/fr' . $urlParams);
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('input[value="A propos"]'));
    }

    /**
     * Admin edition test case for Sitemap block
     */
    public function testBlockSitemapEdit()
    {
        $urlParams = '?website=/website/sandbox&id=/website/sandbox/theme/test/footer_middle/presta_cms.block.sitemap-10';

        $crawler = $this->client->request('GET', '/admin/cms/block/edit/en' . $urlParams);
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('input[value="Useful Links"]'));
        $this->assertCount(1, $crawler->filter('input[value="1"]'));
        $this->assertCount(1, $crawler->filter('input[value="/website/sandbox/page/page-children"]'));

        $crawler = $this->client->request('GET', '/admin/cms/block/edit/fr' . $urlParams);
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('input[value="Liens utiles"]'));
        $this->assertCount(1, $crawler->filter('input[value="1"]'));
        $this->assertCount(1, $crawler->filter('input[value="/website/sandbox/page/page-children"]'));
    }

    /**
     * Admin edition test case for Page Children block
     */
    public function testBlockPageChildrenEdit()
    {
        $urlParams = '?website=/website/sandbox&id=/website/sandbox/page/page-children/content/presta_cms.block.page_children-10';

        $crawler = $this->client->request('GET', '/admin/cms/block/edit/en' . $urlParams);
        $res = $this->client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());

        $this->assertCount(1, $crawler->filter('input[value="This is a page children block"]'));
    }
}
