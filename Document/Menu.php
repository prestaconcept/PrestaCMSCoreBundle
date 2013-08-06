<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document;

use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu as BaseMenu;

/**
 * Navigation Root Node
 *
 */
class Menu extends BaseMenu
{
    //in the future will store additional features like can add : remove children...
    protected $labelx;
}
