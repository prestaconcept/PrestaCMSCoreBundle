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

class PageTree extends PHPCRTree
{
    /**
     * Get the alias for this tree
     *
     * @return string
     */
    public function getAlias()
    {
        return 'presta_cms_page_tree';
    }
}