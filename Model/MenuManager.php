<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * Menu Manager
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class MenuManager
{
    /**
     * @var ModelManagerInterface
     */
    protected $documentManager;

    public function __construct(ModelManagerInterface $modelManager)
    {
        $this->documentManager = $modelManager->getDocumentManager();
    }

    /**
     * @return ModelManagerInterface
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * Return navigation for a given website
     *
     * @param  Website $website
     * @return array
     */
    public function getWebsiteMenus(Website $website)
    {
        $menuRoot = $this->getDocumentManager()->find(null, $website->getMenuRoot());

        $menus = array();
        foreach ($menuRoot->getChildren() as $child) {
            if ($child instanceof Menu) {
                $menus[$child->getId()] = $child->getLabel();
            }
        }

        return $menus;
    }

    /**
     * Create a new menu entry
     *
     * @param  MenuNode $parent
     * @param  string   $name
     * @param  string   $label
     * @param  Page     $content
     * @param  null     $uri
     * @param  null     $route
     * @return MenuNode
     */
    public function create(MenuNode $parent, $name, $label, Page $content, $uri = null, $route = null)
    {
        $menuNode = new MenuNode();
        $menuNode->setParent($parent);
        $menuNode->setName($name);

        $this->getDocumentManager()->persist($menuNode); // do persist before binding translation

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
                $this->getDocumentManager()->bindTranslation($menuNode, $locale);
            }
        } else {
            $menuNode->setLabel($label);
            $menuNode->setLocale($content->getLocale());
            $this->getDocumentManager()->bindTranslation($menuNode, $content->getLocale());
        }
        $this->getDocumentManager()->flush();

        return $menuNode;
    }

    /**
     * Return current website main menu
     *
     * @param  Website $website
     * @return array $menus
     */
    public function getWebsiteMainMenu(Website $website)
    {
        return $this->getDocumentManager()->find(null, $website->getMainMenuRootPath());
    }
}
