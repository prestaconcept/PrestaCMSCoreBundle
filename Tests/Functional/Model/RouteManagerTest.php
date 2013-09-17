<?php
/**
 * This file is part of the PrestaCMSCoreBundle project.
 *
 * ( c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional\Model;

use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;

/**
 * phpunit -c . Tests/Functional/Model/RouteManagerTest.php
 *
 * @author Alain Flaus <aflaus@prestaconcept.net>
 */
class RouteManagerTest extends BaseFunctionalTestCase
{
    /**
     * @return RouteManager
     */
    protected function getRouteManager()
    {
        return $this->container->get('presta_cms.manager.route');
    }

    /**
     * @return Route
     */
    protected function getRoute()
    {
        return $this->documentManager->find(null, '/website/sandbox/route/en/page-children');
    }

    //    public function testGetRoutesForWebsite()
    //    {
    //
    //    }
    //
    //    public function testUpdatePageRoutingUrlComplete()
    //    {
    //
    //    }

    /**
     * @test RouteManager::initializePageRouting
     */
    public function testInitializePageRouting()
    {
        $homepage = $this->documentManager->find(null, '/website/sandbox/page/home');
        $homepage = $this->getRouteManager()->initializePageRouting($homepage);

        //Homepage
        $this->assertFalse($homepage->isUrlCompleteMode());
        $this->assertEquals('/', $homepage->getUrlRelative());
        $this->assertEquals('/', $homepage->getPathComplete());
        $this->assertEquals('/', $homepage->getUrlComplete());

        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children');
        $page = $this->getRouteManager()->initializePageRouting($page);

        //Page under root
        $this->assertFalse($page->isUrlCompleteMode());
        $this->assertEquals('/page-children', $page->getUrlRelative());
        $this->assertEquals('/', $page->getPathComplete());
        $this->assertEquals('/page-children', $page->getUrlComplete());

        //Children page
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');

        //urlCompleteMode -> false
        $page = $this->getRouteManager()->initializePageRouting($page);
        $this->assertEquals('/block-simple', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/block-simple', $page->getUrlComplete());

        //urlCompleteMode -> true
        $page->setUrlCompleteMode(true);
        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/block-simple', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/block-simple', $page->getUrlComplete());
    }


    //    public function testIndexRedirectRouteToCreate()
    //    {
    //        $redirectRoutes = array();
    //        $route          = $this->generateRouteHierarchy();
    //
    //        $this->getRouteManager()->indexRedirectRouteToCreate($redirectRoutes, $route);
    //
    //        $this->assertEquals(3, count($redirectRoutes));
    //    }
}
