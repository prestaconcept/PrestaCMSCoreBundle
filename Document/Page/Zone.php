<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document\Page;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\BlockBundle\Document\ContainerBlock;

/**
 * Page Zone Document
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class Zone extends ContainerBlock
{

    public function getType()
    {
        return 'presta_cms.page.zone';
    }

    public function addBlock($block)
    {
        $this->addChild($block);
    }

    public function getBlocks()
    {
        return $this->getChildren();
    }
}