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
    const TYPE_GLOBAL = 'global';

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
     * @param string $type
     *
     * @return array
     */
    public function getBlocks($type)
    {
        if (!isset($this->configurations[$type])) {
            $type = self::TYPE_GLOBAL;
        }
        $availableBlocks = $this->blocks->toArray();

        $excludedBlocks = $this->getExcludedBlocks($type);
        if (count($excludedBlocks)) {
            $availableBlocks = array_diff($availableBlocks, $excludedBlocks);
        }

        $acceptedBlocks = $this->getAcceptedBlocks($type);
        if (count($acceptedBlocks)) {
            $availableBlocks = array_merge($availableBlocks, $acceptedBlocks);
        }

        return array_unique($availableBlocks);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getExcludedBlocks($type)
    {
        return $this->configurations[$type]['excluded'];
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getAcceptedBlocks($type)
    {
        return $this->configurations[$type]['accepted'];
    }

    /**
     * @param string $blockServiceId
     *
     * @return BlockManager
     */
    public function addBlock($blockServiceId)
    {
        $this->blocks->add($blockServiceId);

        return $this;
    }

    /**
     * @param array  $configuration
     * @param string $type
     *
     * @return $this
     */
    public function addConfiguration($configuration, $type = self::TYPE_GLOBAL)
    {
        $initialConfiguration = array(
            'excluded' => array(),
            'accepted' => array(),
        );

        if (!isset($this->configurations[self::TYPE_GLOBAL])) {
            $this->configurations[self::TYPE_GLOBAL] = $initialConfiguration;
        }

        $this->configurations[$type] = $configuration + $initialConfiguration;

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
