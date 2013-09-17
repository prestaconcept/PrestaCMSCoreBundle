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

use Symfony\Cmf\Bundle\MenuBundle\Model\Menu as CmfMenuModel;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Menu extends CmfMenuModel
{
    //in the future will store additional features like can add : remove children...

    //#94 : Page tree not working due to PHPCR updates
    //If this property is not declare here PhpcrOdnTree does not get children
    public $children = array();

    /**
     * Used for tree rendering
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getLabel();
    }
}
