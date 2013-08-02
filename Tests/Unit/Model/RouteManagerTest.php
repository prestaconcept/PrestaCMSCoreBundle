<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit\Model;

use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Document\Website;

/**
 * RouteManagerTest
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author David Epely <depely@prestaconcept.net>
 */
class RouteManagerTest extends \PHPUnit_Framework_Testcase
{
    protected $routeManager;

    public function setUp()
    {
        $this->routeManager = new RouteManager();
        $this->routeManager->setRouteProvider($this->getMock('Symfony\Cmf\Component\Routing\RouteProviderInterface'));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function testFindRoutesByWebsite()
    {
        $website = new Website();
        $this->routeManager->findRoutesByWebsite($website);
    }

    /**
     * @test
     */
    public function testGetRouteCollectionForHierarchy()
    {
        $routeCollection = $this->routeManager->getRouteCollectionForHierarchy($this->generateRouteHierarchy());
        $this->assertInstanceOf('\Symfony\Component\Routing\RouteCollection', $routeCollection);
        $this->assertEquals(3, $routeCollection->count());
    }

    /**
     * @return \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route
     */
    protected function generateRouteHierarchy()
    {
        $route1 = new Route();
        $route1->setId('/route/main');
        $route11 = new Route();
        $route11->setId('/route/main/1');
        $route12 = new Route();
        $route12->setId('/route/main/2');

        $refl = new \ReflectionClass($route1);
        $prop = $refl->getProperty('children');
        $prop->setAccessible(true);
        $prop->setValue($route1, array(
            $route11,
            $route12,
        ));

        return $route1;
    }
}
