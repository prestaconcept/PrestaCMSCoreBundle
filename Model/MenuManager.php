<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;

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
    public function getNavigationRootsForWebsite(Website $website)
    {
        $menuRoot = $this->getDocumentManager()->find(null, $website->getMenuRoot());

        $navigationRoots = array();
        foreach ($menuRoot->getChildren() as $child) {
            if ($child instanceof RootMenuNode) {
                $navigationRoots[$child->getId()] = $child->getLabel();
            }
        }

        return $navigationRoots;
    }

    /**
     * Create a new menu entry
     *
     * @param  $parent
     * @param  $name
     * @param  $label
     * @param  $content
     * @param  null $uri
     * @param  null $route
     * @param  null $type
     * @return MenuNode
     */
    public function create($parent, $name, $label, $content, $uri = null, $route = null, $type = null)
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
}
