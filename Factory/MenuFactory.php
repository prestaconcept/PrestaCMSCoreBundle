<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Model\Page;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class MenuFactory extends AbstractModelFactory implements ModelFactoryInterface
{
    /**
     * @var string
     */
    protected $menuNodeClassName;

    /**
     * @param string $menuNodeClassName
     */
    public function setMenuNodeClassName($menuNodeClassName)
    {
        $this->menuNodeClassName = $menuNodeClassName;
    }

    /**
     * Default configuration
     *
     * @param  array $configuration
     * @return array
     */
    protected function configure(array $configuration)
    {
        $configuration += array(
            'content_path'  => null,
            'content'       => null,
            'uri'           => null,
            'route'         => null,
            'children'      => null,
            'title'         => $configuration['name']
        );

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $configuration = array())
    {
        $configuration = $this->configure($configuration);

        if ($configuration['content'] == null
            && $configuration['content_path'] == null
            && $configuration['uri'] == null
            && $configuration['route'] == null
        ) {
            $menuNode =  new $this->modelClassName();
        } else {
            $menuNode =  new $this->menuNodeClassName();

            if ($configuration['content'] != null) {
                $menuNode->setContent($configuration['content']);
            } elseif ($configuration['content_path'] != null) {
                $content = $this->getObjectManager()->find(null, $configuration['content_path']);
                $menuNode->setContent($content);
            } elseif ($configuration['uri'] != null) {
                $menuNode->setUri($configuration['uri']);
            } elseif ($configuration['route'] != null) {
                $menuNode->setRoute($configuration['route']);
            }
        }

        $menuNode->setParent($configuration['parent']);
        $menuNode->setName($configuration['name']);

        // do persist before binding translation
        $this->getObjectManager()->persist($menuNode);

        if (is_array($configuration['title'])) {
            foreach ($configuration['title'] as $locale => $title) {
                $menuNode->setLabel($title);
                $this->getObjectManager()->bindTranslation($menuNode, $locale);
            }
        } else {
            $menuNode->setLabel($configuration['title']);
        }

        if ($configuration['children'] != null) {
            foreach ($configuration['children'] as $childConfiguration) {
                $childConfiguration['parent'] = $menuNode;
                if (isset($childConfiguration['meta']['title'])) {
                    $childConfiguration['title'] = $childConfiguration['meta']['title'];
                }

                if (isset($configuration['children_content_path'])) {
                    $contentPath = ($configuration['children_content_path']);
                } else {
                    $contentPath = ($configuration['content_path']);
                }

                $childConfiguration['content_path'] = $contentPath . '/' . $childConfiguration['name'];
                $this->create($childConfiguration);
            }
        }

        return $menuNode;
    }

    /**
     * Create configuration for menu construction
     *
     * @param Page   $page
     * @param string $parent
     *
     * @return array
     */
    public function getConfiguration(Page $page, $parent)
    {
        $configuration = array(
            'content'   => $page,
            'title'     => $page->getTitle(),
            'name'      => $page->getName(),
        );

        $parent = $this->getObjectManager()->find(null, $parent);

        if ($parent instanceof Page) {
            $parent = $parent->getMenuNodes()->first();
        }

        $configuration['parent'] = $parent;

        return $configuration;
    }
}
