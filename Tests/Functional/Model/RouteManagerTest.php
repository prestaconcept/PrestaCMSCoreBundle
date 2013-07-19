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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Presta\CMSCoreBundle\Tests\Functional\BaseFunctionalTestCase;
use Symfony\Cmf\Bundle\RoutingBundle\Document\Route;

/**
 * RouteManager functionnal test class
 * 
 * @author Alain Flaus <aflaus@prestaconcept.net>
 */
class RouteManagerTest extends BaseFunctionalTestCase
{
    /**
     * @return \Presta\CMSCoreBundle\Model\RouteManager
     */
    protected function getRouteManager()
    {
        return $this->container->get('presta_cms.route_manager');
    }

    public function testIndexRedirectRouteToCreate()
    {
        $redirectRoutes = array();
        $route          = $this->generateRouteHierarchy();

        $this->getRouteManager()->indexRedirectRouteToCreate($redirectRoutes, $route);

        $this->assertEquals(3, count($redirectRoutes));
    }

    public function testGenerateNewPath()
    {
        $route = $this->generateRouteHierarchy();

        $newPath = $this->getRouteManager()->generateNewPath($route, 'new');

        $this->assertEquals($newPath, '/route/new');
    }

    /**
     * @return Route
     */
    protected function generateRouteHierarchy()
    {
        $route1 = new Route();
        $route1->setId('/route/main');
        $route1->setName('main');

        $route11 = new Route();
        $route11->setId('/route/main/1');
        $route11->setName('1');

        $route12 = new Route();
        $route12->setId('/route/main/2');
        $route12->setName('2');

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