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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Block Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockManager
{
    /** @var ArrayCollection */
    protected $blocks;

    /** @var array */
    protected $configurations;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    /**
     * @param string $type
     *
     * @return ArrayCollection
     */
    public function getBlocks($type = 'global')
    {
        $availableBlocks = $this->blocks;

        if (count($this->getExcludedBlocks($type))) {
            foreach ($availableBlocks as $block) {
                if (in_array($block, $this->getExcludedBlocks($type))) {
                    $availableBlocks->removeElement($block);
                }
            }
        }

        if (count($this->getAcceptedBlocks($type))) {
            $availableBlocks = new ArrayCollection();
            foreach ($this->getAcceptedBlocks($type) as $block) {
                $availableBlocks->add($block);
            }
        }

        return $availableBlocks;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getExcludedBlocks($type = 'global')
    {
        if (isset($this->configurations[$type]['excluded'])) {
            return $this->configurations[$type]['excluded'];
        }
        return array();
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getAcceptedBlocks($type = 'global')
    {
        if (isset($this->configurations[$type]['accepted'])) {
            return $this->configurations[$type]['accepted'];
        }
        return array();
    }

    /**
     * @param  string $blockServiceId
     *
     * @return BlockManager
     */
    public function addBlock($blockServiceId)
    {
        $this->blocks->add($blockServiceId);

        return $this;
    }

    /**
     * @param string $type
     * @param array  $configuration
     *
     * @return BlockManager
     */
    public function addConfiguration($type, $configuration)
    {
        $this->configurations[$type] = $configuration;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }
}
