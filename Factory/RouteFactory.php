<?php

namespace Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\Website;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class RouteFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @param RouteManager $routeManager
     */
    public function setRouteManager($routeManager)
    {
        $this->routeManager = $routeManager;
    }

    /**
     * Default configuration
     *
     * @param  array $configuration
     * @return array
     */
    protected function configurePage(array $configuration)
    {
        $configuration += array(
            'url' => null,
            'children' => null,
            'url_pattern' => null,
            'url_default' => null,
            'locale' => null
        );
        if ($configuration['url'] != null) {
            if (is_array($configuration['url'])) {
                $configuration['url'] = $configuration['url'][$configuration['locale']];
            }
        } else {
            //If url is not set, we take the page name as default
            $configuration['url'] = $configuration['name'];
        }

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $configuration = $this->configurePage($configuration);

        $route = new Route();
        $route->setPosition($configuration['parent'], $configuration['url']);
        $route->setDefault('_locale', $configuration['locale']);
        $route->setRequirement('_locale', $configuration['locale']);
        if ($configuration['url_pattern'] != null) {
            $route->setVariablePattern($configuration['url_pattern']);
        }
        if ($configuration['url_default'] != null) {
            foreach ($configuration['url_default'] as $key => $value) {
                $route->setDefault($key, $value);
            }
        }
        if (isset($configuration['content'])) {
            $content = $configuration['content'];
        } else {
            $content = $this->getObjectManager()->find(null, $configuration['content_path']);
        }

        $route->setContent($content);

        $this->getObjectManager()->persist($route);

        if ($configuration['children'] != null) {
            foreach ($configuration['children'] as $childConfiguration) {
                $childConfiguration['parent'] = $route;
                $childConfiguration['locale'] = $configuration['locale'];
                $childConfiguration['content_path'] = $configuration['content_path'] . '/' . $childConfiguration['name'];
                $this->create($childConfiguration);
            }
        }

        return $route;
    }

    /**
     * Create configuration for route construction
     *
     * @param Website $website
     * @param Page    $page
     * @param string  $locale
     *
     * @return array
     */
    public function getConfiguration(Website $website, Page $page, $locale)
    {
        $configuration = array(
            'website'   => $website,
            'locale'    => $locale,
            'name'      => $page->getName(),
            'content'   => $page
        );

        $parentPage = $page->getParent();
        if ($parentPage instanceof Page) {
            $parent = $this->routeManager->getRouteForPage($parentPage, $locale);
        } else {
            //if page is not a child, its routing is under website root node
            $website->setLocale($locale);
            $parent = $this->getObjectManager()->find(null, $website->getRoutePrefix());
        }
        $configuration['parent'] = $parent;

        return $configuration;
    }
}
