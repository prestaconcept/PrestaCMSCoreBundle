<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Navigation;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuNode;

/**
 * Navigation Root Node
 *
 * @PHPCRODM\Document(translator="attribute")
 */
class RootMenuNode extends MultilangMenuNode
{
    //in the future will store aditional features like can add : remove children...
}
