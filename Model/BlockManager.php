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
     * @return array
     */
    public function getBlocks()
    {
        $availableBlocks = $this->blocks;

        if (count($this->getExcludedBlocks())) {
            return array_diff($availableBlocks->toArray(), $this->getExcludedBlocks());
        }

        if (count($this->getAcceptedBlocks())) {
            return array_intersect($availableBlocks->toArray(), $this->getAcceptedBlocks());
        }

        return $availableBlocks->toArray();
    }

    /**
     * @return array
     */
    public function getExcludedBlocks()
    {
        return $this->configurations['global']['excluded'];
    }

    /**
     * @return array
     */
    public function getAcceptedBlocks()
    {
        return $this->configurations['global']['accepted'];
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
        $initialConfiguration = array(
            'excluded' => array(),
            'accepted' => array(),
        );

        $this->configurations['global'] = $configuration + $initialConfiguration;

        if (count($this->getExcludedBlocks()) && count($this->getAcceptedBlocks())) {
            throw new InvalidConfigurationException("Cannot have accepted AND excluded blocks lists.");
        }

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
