<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\EventListener;

use PrestaSonata\AdminBundle\Event\ConfigureMenuEvent;

/**
 * Event listener for Administration main navigation construction
 *
 * @package    PrestaSonata
 * @subpackage AdminBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class ConfigureMenuListener
{
    /**
     * @param \PrestaSonata\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $cmsMenuItem = $menu->addChild('CMS', array('route' => 'presta_cms_website'));
        $cmsMenuItem->setAttribute('class', 'dropdown');
        $cmsMenuItem->setLabel('CMS <span class="caret"></span>');
        $cmsMenuItem->setExtra('safe_label', true);
        $cmsMenuItem->setLinkAttribute('class', 'dropdown-toggle  nav-menu nav-menu-cms');
        $cmsMenuItem->setLinkAttribute('data-toggle', 'dropdown');
        $cmsMenuItem->setChildrenAttribute('class', 'dropdown-menu');
                
        $websiteItem = $cmsMenuItem->addChild('Websites', array('route' => 'presta_cms_website'));
		$websiteItem->setExtra('safe_label', true);
		$websiteItem->setLabel('Websites <p>Lorem ipsum websites</p>');
		$websiteItem->setLinkAttribute('class', 'nav-submenu nav-submenu-cms-websites');

		$pageItem = $cmsMenuItem->addChild('Pages', array('route' => 'presta_cms_page'));
		$pageItem->setExtra('safe_label', true);
		$pageItem->setLabel('Pages <p>Lorem ipsum pages</p>');
		$pageItem->setLinkAttribute('class', 'nav-submenu nav-submenu-cms-pages');

        $themeItem = $cmsMenuItem->addChild('Themes', array('route' => 'presta_cms_theme'));
		$themeItem->setExtra('safe_label', true);
		$themeItem->setLabel('Themes <p>Lorem ipsum themes</p>');
		$themeItem->setLinkAttribute('class', 'nav-submenu nav-submenu-cms-themes');
    }
}