<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Alain Flaus <aflaus@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\TreeInterface;

/**
 * 
 */
class PageTreeModelType extends AbstractType
{
    protected $defaults = array();
    protected $tree;

    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    public function setTree(TreeInterface $tree)
    {
        $this->tree = $tree;
    }

    public function getParent()
    {
        return 'doctrine_phpcr_odm_tree';
    }

    public function getName()
    {
        return 'presta_cms_doctrine_phpcr_odm_page_tree';
    }
}
