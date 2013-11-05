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
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Block Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockManager
{
    /**
     * @var ArrayCollection
     */
    protected $blocks;

    /**
     * array
     */
    protected $configurations;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getBlocks()
    {
        $availableBlocks = $this->blocks;

        if (count($this->getExcludedBlocks())) {
            foreach ($availableBlocks as $block) {
                if (in_array($block, $this->getExcludedBlocks())) {
                    $availableBlocks->removeElement($block);
                }
            }
        }

        if (count($this->getAcceptedBlocks())) {
            $availableBlocks = new ArrayCollection();
            foreach ($this->getAcceptedBlocks() as $block) {
                $availableBlocks->add($block);
            }
        }

        return $availableBlocks;
    }

    /**
     * @return array
     */
    protected function getExcludedBlocks()
    {
        if (
            isset($this->configurations['global']['excluded'])
            && count($this->configurations['global']['excluded'])
        ) {
            return $this->configurations['global']['excluded'];
        }

        return array();
    }

    /**
     * @return array
     */
    protected function getAcceptedBlocks()
    {
        if (
            isset($this->configurations['global']['accepted'])
            && count($this->configurations['global']['accepted'])
        ) {
            return $this->configurations['global']['accepted'];
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
     * @param array $configuration
     *
     * @return $this
     *
     * @throws InvalidConfigurationException
     */
    public function addConfiguration($configuration)
    {
        $configurations = array();
        foreach ($configuration as $type => $config) {
            if (count($config)) {
                $configurations[$type] = $config;
            }
        }

        if (count($configurations) > 1) {
            throw new InvalidConfigurationException("Cannot have accepted AND excluded blocks lists.");
        }

        $this->configurations['global'] = $configurations;

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
