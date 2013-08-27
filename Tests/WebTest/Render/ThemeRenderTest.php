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
class ThemeRenderTest extends BaseFunctionalTestCase
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
     * Test the rendering of theme blocks
     */
    public function testBlockRender()
    {
        $urls = array('/', '/page-children', '/page-children/block-simple', '/page-children/block-sitemap');

        foreach ($urls as $url) {
            $crawler = $this->client->request('GET', $url);
            $res = $this->client->getResponse();
            $this->assertEquals(200, $res->getStatusCode());

            //Navigation
            $links = array(
                'Homepage',
                'page-children'
            );
            foreach ($links as $link) {
                $this->assertCount(1, $crawler->filter('html:contains("'. $link . '")'));
            }

            //@todo breadcrumb

            //Footer
            $this->assertCount(1, $crawler->filter('html:contains("About US")'));
            $this->assertCount(1, $crawler->filter('html:contains("PrestaCMS is an open-source CMS base on Symfony2, CMF and Sonata.")'));
            $this->assertCount(1, $crawler->filter('html:contains("Useful Links")'));
        }
    }
}
