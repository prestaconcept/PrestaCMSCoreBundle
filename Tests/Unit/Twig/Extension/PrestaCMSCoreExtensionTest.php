<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit\Twig\Extension;

use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Tests\Unit\BaseUnitTestCase;
use Presta\CMSCoreBundle\Twig\Extension\PrestaCMSCoreExtension;
use Symfony\Cmf\Bundle\CoreBundle\Templating\Helper\CmfHelper;

/**
 * phpunit -c . --filter testExtractLink Tests/Unit/Twig/Extension/PrestaCMSCoreExtensionTest.php
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PrestaCMSCoreExtensionTest extends BaseUnitTestCase
{
    /**
     * @test PrestaCMSCoreExtension::extractLinks()
     */
    public function testExtractLinks()
    {
        $extension = new PrestaCMSCoreExtension(new CmfHelper(), new RouteManager(), new WebsiteManager());

        $links = $extension->extractLinks(
            '<div class="cms-block-content">
            <p><strong><a href="##internal#/website/sandbox/page/404#">PrestaCMS</a></strong> is a content management
            bundle based on <a href="##internal#/website/sandbox/page/projects#">Symfony2</a>,SymfonyCMF and Sonata<br>
            This is an open source&nbsp;supported by <a href="http://prest.net">PrestaConcept</a>.</p>
            <p>&nbsp;</p>
            <a href="##internal#/website/sandbox/page/projects#">Another link</a>
            <p><img alt="" src="/uploads/media/prestacms/0001/01/thumb_3_prestacms_page_wide.jpeg"></p>
            </div>'
        );

        $this->assertEquals(3, count($links));
        $this->assertEquals(2, count($links[0]));
        $this->assertEquals(2, count($links[1]));
        $this->assertEquals('##internal#/website/sandbox/page/404#', $links[0][0]);
        $this->assertEquals('/website/sandbox/page/404', $links[0][1]);
        $this->assertEquals('##internal#/website/sandbox/page/projects#', $links[1][0]);
        $this->assertEquals('/website/sandbox/page/projects', $links[1][1]);
        $this->assertEquals('##internal#/website/sandbox/page/projects#', $links[2][0]);
        $this->assertEquals('/website/sandbox/page/projects', $links[2][1]);
    }
}
