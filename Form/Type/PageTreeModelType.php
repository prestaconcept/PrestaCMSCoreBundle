<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) PrestaConcept http://www.prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form\Type;

use Sonata\DoctrinePHPCRAdminBundle\Form\Type\TreeModelType;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\TreeInterface;

/**
 * Custom form type to display a list of page
 *
 * Make a reel extension instead of getParent() method to retrieve 
 * custom tree set in service declaration instead of parent one
 * 
 * @todo: search better way to init service to use symfony standard
 * FormType extension
 *
 * @author  Alain Flaus <aflaus@prestaconcept.net>
 */
class PageTreeModelType extends TreeModelType
{
    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var TreeInterface
     */
    protected $tree;

    /**
     * Setter
     * 
     * @param array $defaults
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * Setter
     * 
     * @param TreeInterface $tree
     */
    public function setTree(TreeInterface $tree)
    {
        $this->tree = $tree;
    }

    /**
     * Getter
     * 
     * @return string
     */
    public function getParent()
    {
        return 'field';
    }

    /**
     * Getter
     * 
     * @return string
     */
    public function getName()
    {
        return 'presta_cms_doctrine_phpcr_odm_page_tree';
    }
}
