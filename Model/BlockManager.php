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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Block Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockManager
{
    /**
     * @var ArrayCollectiion
     */
    protected $blocks;

    public function __construct()
    {
        $this->blocks = new ArrayCollection;
    }

    /**
     * @return ArrayCollectiion
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param  string       $blockServiceId
     * @return BlockManager
     */
    public function addBlock($blockServiceId)
    {
        $this->blocks->add($blockServiceId);

        return $this;
    }

}
