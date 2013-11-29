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
use Symfony\Cmf\Bundle\RoutingBundle\Model\RedirectRoute;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;

/**
 * phpunit -c . Tests/Functional/Model/RouteManagerTest.php
 * phpunit -c . --filter testUpdatePageRoutingUrlRelative Tests/Functional/Model/RouteManagerTest.php
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
     * Allow us to test protected/private method from RouteManager
     *
     * @param  string $name
     * @return \ReflectionMethod
     */
    protected function getRouterManagerMethodByName($name)
    {
        $class = new \ReflectionClass(get_class($this->getRouteManager()));
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
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
        $this->assertEquals('', $homepage->getUrlRelative());
        $this->assertEquals('/', $homepage->getPathComplete());
        $this->assertEquals('', $homepage->getUrlComplete());

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

    /**
     * @test RouteManager::getRedirectRouteForPage
     */
    public function testGetRedirectRouteForPage()
    {
        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children');

        $redirectRoutes = $this->getRouteManager()->getRedirectRouteForPage($page);

        $this->assertEquals(2, count($redirectRoutes));

        foreach ($redirectRoutes as $redirect) {
            $this->assertTrue($redirect instanceof RedirectRoute);
        }
    }

    /**
     * @test RouteManager::getCorrespondingUrls
     */
    public function testGetCorrespondingUrls()
    {
        $method = $this->getRouterManagerMethodByName('getCorrespondingUrls');
        $route  = $this->documentManager->find(null, '/website/sandbox/route/en/page-children');

        $urls = $method->invokeArgs(
            $this->getRouteManager(),
            array($route, '/website/sandbox/route/en/new-page-children')
        );

        $this->assertEquals(4, count($urls));

        $this->assertEquals(
            '/website/sandbox/route/en/new-page-children',
            $urls['/website/sandbox/route/en/page-children']
        );
        $this->assertEquals(
            '/website/sandbox/route/en/new-page-children/block-simple',
            $urls['/website/sandbox/route/en/page-children/block-simple']
        );
        $this->assertEquals(
            '/website/sandbox/route/en/new-page-children/block-sitemap',
            $urls['/website/sandbox/route/en/page-children/block-sitemap']
        );
        $this->assertEquals(
            '/website/sandbox/route/en/new-page-children/block-container',
            $urls['/website/sandbox/route/en/page-children/block-container']
        );
    }

    /**
     * @test RouteManager::generateRedirects
     */
    public function testGenerateRedirects()
    {
        $method = $this->getRouterManagerMethodByName('generateRedirects');

        $method->invokeArgs(
            $this->getRouteManager(),
            array(
                array(
                    '/website/sandbox/route/en/old-page-children' => '/website/sandbox/route/en/page-children',
                    '/website/sandbox/route/en/old-page-children/block-simple' => '/website/sandbox/route/en/page-children/block-simple',
                    '/website/sandbox/route/en/old-page-children/block-sitemap' => '/website/sandbox/route/en/page-children/block-sitemap',
                    '/website/sandbox/route/en/old-page-children/block-container' => '/website/sandbox/route/en/page-children/block-container'
                )
            )
        );

        $page = $this->documentManager->find(null, '/website/sandbox/page/page-children');
        $redirectRoutes = $this->getRouteManager()->getRedirectRouteForPage($page);

        //should be 3 when #10 will be fix
        $this->assertEquals(2, count($redirectRoutes));
    }
}
