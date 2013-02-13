<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ODM\PHPCR\Document\Generic;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RedirectRoute;

use Presta\CMSCoreBundle\Document\Page;
use Presta\CMSCoreBundle\Document\Zone;
use Presta\CMSCoreBundle\Document\Block;

/**
 * Base fixtures methods to easily create routes
 */
abstract class BaseRouteFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 40;
    }

    /**
     * Default page configuration for menu
     *
     * @param  array $page
     * @return array
     */
    protected function configurePage(array $page)
    {
        $page += array(
            'url' => null,
            'children' => null,
            'url-pattern' => null,
            'url-default' => null
        );
        if (!is_array($page['url'])) {
            $page['url'] = array();
            foreach ($this->getLocales() as $locale) {
                $page['url'][$locale] = $page['name'];
            }
        }

        return $page;
    }

    /**
     * @param $root
     * @param $name
     * @param $content
     * @param $locale
     * @return \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route
     */
    protected function createRoute($root, $name, $content, $locale, $urlPattern = null, $urlDefault = null)
    {
        $route = new Route;
        $route->setPosition($root, $name);
        $route->setDefault('_locale', $locale);
        $route->setRequirement('_locale', $locale);
        if ($urlPattern != null) {
            $route->setVariablePattern($urlPattern);
        }
        if ($urlDefault != null) {
            foreach ($urlDefault as $key => $value) {
                $route->setDefault($key, $value);
            }
        }
        $route->setRouteContent($content);
        $this->manager->persist($route);

        return $route;
    }

    /**
     * Create route for a content
     *
     * @param $dm
     * @param $parentEn
     * @param $parentFr
     * @param $page
     * @param $contentPath
     */
    protected function createRouteForPage($parent, $locale, $page, $contentPath)
    {
        $page = $this->configurePage($page);
        $contentPath .= '/' . $page['name'];
        $content = $this->manager->find(null, $contentPath);

        $route = $this->createRoute($parent, $page['url'][$locale], $content, $locale, $page['url-pattern'], $page['url-default']);

        if ($page['children'] != null) {
            foreach ($page['children'] as $child) {
                $this->createRouteForPage($route, $locale, $child, $contentPath);
            }
        }
    }
}