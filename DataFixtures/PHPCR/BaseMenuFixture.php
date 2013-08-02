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

use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
use Presta\CMSCoreBundle\Document\NavigationRootMenuNode;

/**
 * Base fixtures methods to easily create menu
 */
abstract class BaseMenuFixture extends BaseFixture
{
    const NAVIGATION_ROOT_MENU_NODE_CLASS = '\Presta\CMSCoreBundle\Document\NavigationRootMenuNode';
    const MENU_NODE_CLASS = '\Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode';

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
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
            'meta' => array('title' => null),
            'children' => null
        );
        if (!is_array($page['meta']['title'])) {
            $page['meta']['title'] = array();
            foreach ($this->getLocales() as $locale) {
                $page['meta']['title'][$locale] = $page['name'];
            }
        }

        return $page;
    }

    /**
     * Create menu for a page
     *
     * @param $parent
     * @param $page
     * @param $contentPath
     */
    protected function createMenuForPage($parent, $page, $contentPath)
    {
        $page = $this->configurePage($page);
        $contentPath .= '/' . $page['name'];

        $menu = $this->createMenuNode($parent, $page['name'], $page['meta']['title'], $this->manager->find(null, $contentPath));
        if ($page['children'] != null) {
            foreach ($page['children'] as $child) {
                $this->createMenuForPage($menu, $child, $contentPath);
            }
        }

        return $menu;
    }

    /**
     * @see CMF-Sandbox
     * @return \Knp\Menu\NodeInterface a Navigation instance with the specified information
     */
    protected function createMenuNode($parent, $name, $label, $content, $uri = null, $route = null, $type = null)
    {
        if ($type == null) {
            $type = self::NAVIGATION_ROOT_MENU_NODE_CLASS;
        }

        $menuNode = new $type();
        $menuNode->setParent($parent);
        $menuNode->setName($name);

        $this->manager->persist($menuNode); // do persist before binding translation

        if (null !== $content) {
            $menuNode->setContent($content);
        } elseif (null !== $uri) {
            $menuNode->setUri($uri);
        } elseif (null !== $route) {
            $menuNode->setRoute($route);
        }

        if (is_array($label)) {
            foreach ($label as $locale => $l) {
                $menuNode->setLabel($l);
                //@todo
                //[Doctrine\ODM\PHPCR\PHPCRException]
                //This document is not translatable, do not use bindTranslation: Main navigation (/website/sandbox/menu/main)

                //$this->manager->bindTranslation($menuNode, $locale);
            }
        } else {
            $menuNode->setLabel($label);
        }

        return $menuNode;
    }

    /**
     * Create a navigation root item
     *
     * @param $parent
     * @param $name
     * @param $label
     * @return \Knp\Menu\NodeInterface
     */
    protected function createNavigationRootNode($parent, $name, $label, $contentPath)
    {
        //Due to https://github.com/symfony-cmf/MenuBundle/pull/37/files
        //we have to link every menu node to a route
        return $this->createMenuNode($parent, $name, $label, $this->manager->find(null, $contentPath . '/home'), null, null, self::NAVIGATION_ROOT_MENU_NODE_CLASS);
    }
}
