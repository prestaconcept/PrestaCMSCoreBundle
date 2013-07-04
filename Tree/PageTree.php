<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Alain Flaus <aflaus@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tree;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\PHPCRTree;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;

use PHPCR\Util\NodeHelper;
use PHPCR\PropertyType;

/**
 * PrestaCMS page tree
 */
class PageTree extends PHPCRTree
{
    /**
     * @var \PHPCR\SessionInterface
     */
    private $session;

    /**
     * Constructor
     * 
     * @param ManagerRegistry $manager     
     * @param string          $sessionName
     */
    public function __construct(ManagerRegistry $manager, $sessionName)
    {
        $this->session = $manager->getConnection($sessionName);
    }

    /**
     * Return list of page type node
     * 
     * @param  string $path
     * @return array
     */
    public function getChildren($path)
    {
        $root = $this->session->getNode($path);

        $children = array();

        foreach ($root->getNodes() as $name => $node) {
            // keep only Page node
            if ($node->getPropertyValue('phpcr:class') == 'Presta\CMSCoreBundle\Document\Page') {
                if (NodeHelper::isSystemItem($node)) {
                    continue;
                }
                $child = $this->nodeToArray($name, $node);

                foreach ($node->getNodes() as $childname => $grandson) {
                    // keep only Page node
                    if ($grandson->getPropertyValue('phpcr:class') == 'Presta\CMSCoreBundle\Document\Page') {
                        $child['children'][] = $this->nodeToArray($childname, $grandson);
                    }
                }

                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * { @inherit }
     */
    private function nodeToArray($name, $node)
    {
        $has_children = $node->hasNodes();
        return array(
            'data'  => $name,
            'attr'  => array(
                'id' => $node->getPath(),
                'url_safe_id' => substr($node->getPath(), 1),
                'rel' => 'node'
            ),
            'state' => $has_children ? 'closed' : null,
        );
    }

    /**
     * { @inherit }
     */
    public function getAlias()
    {
        return 'presta_cms_page_tree';
    }
}