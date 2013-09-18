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
 * phpunit -c . --filter testUpdatePageRoutingUrlComplete Tests/Functional/Model/RouteManagerTest.php
 *
 * @author Alain Flaus <aflaus@prestaconcept.net>
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
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

    /**
     * @test RouteManager::getRoutesForWebsite
     */
    public function testGetRoutesForWebsite()
    {
        $website = $this->documentManager->find(null, '/website/sandbox');
        $website->setLocale('en');

        $routes = $this->getRouteManager()->getRoutesForWebsite($website);

        $this->assertEquals(5, count($routes));

        foreach ($routes as $route) {
            $this->assertTrue(strpos($route->getId(), $website->getRoutePrefix()) === 0);
        }
    }

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

    /**
     * @test RouteManager::updatePageRouting
     */
    public function testUpdatePageRouting()
    {
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $this->getRouteManager()->updatePageRouting($page);

        $this->assertEquals('', $page->getUrlRelative());
        $this->assertEquals('', $page->getPathComplete());
        $this->assertEquals('', $page->getUrlComplete());
    }

    /**
     * @test RouteManager::updatePageRoutingUrlRelative
     */
    public function testUpdatePageRoutingUrlRelative()
    {
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page = $this->getRouteManager()->initializePageRouting($page);
        $page->setUrlRelative('new-awesome-url');
        $page->setUrlCompleteMode(false);

        $this->getRouteManager()->updatePageRouting($page);
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/new-awesome-url', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/new-awesome-url', $page->getUrlComplete());

        $page->setUrlRelative('new-awesome-url');
        $this->getRouteManager()->updatePageRouting($page);
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');

        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/new-awesome-url', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/new-awesome-url', $page->getUrlComplete());

        //#58 : urlComplete to urlRelative
        $page->setUrlCompleteMode(true);
        $page->setUrlComplete('/page-children-new-pattern/and-a-level-more/new-awesome-url');
        $this->getRouteManager()->updatePageRouting($page);
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page->setUrlCompleteMode(false);
        $page->setUrlRelative('another-awesome-url');
        $this->getRouteManager()->updatePageRouting($page);

        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/another-awesome-url', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/another-awesome-url', $page->getUrlComplete());
    }

    /**
     * @test RouteManager::updatePageRoutingUrlComplete
     */
    public function testUpdatePageRoutingUrlComplete()
    {
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page->setUrlCompleteMode(true);

        //test existing parent route (ie like relative mode)
        $page->setUrlComplete('/page-children/new-awesome-url');
        $this->getRouteManager()->updatePageRouting($page);
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/new-awesome-url', $page->getUrlRelative());
        $this->assertEquals('/page-children/', $page->getPathComplete());
        $this->assertEquals('/page-children/new-awesome-url', $page->getUrlComplete());

        //test with different url
        $page->setUrlComplete('/page-children-new-pattern/and-a-level-more/new-awesome-url');
        $this->getRouteManager()->updatePageRouting($page);
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children/block-simple');
        $page = $this->getRouteManager()->initializePageRouting($page);

        $this->assertEquals('/new-awesome-url', $page->getUrlRelative());
        $this->assertEquals('/page-children-new-pattern/and-a-level-more/', $page->getPathComplete());
        $this->assertEquals('/page-children-new-pattern/and-a-level-more/new-awesome-url', $page->getUrlComplete());
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
