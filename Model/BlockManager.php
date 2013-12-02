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
     * @param string $zone
     *
     * @return array
     */
    public function getBlocks($zone = 'global')
    {
        if (!isset($this->configurations[$zone])) {
            $zone = 'global';
        }
        $availableBlocks = $this->blocks->toArray();

        $excludedBlocks = $this->getExcludedBlocks($zone);
        if (count($excludedBlocks)) {
            $availableBlocks = array_diff($availableBlocks, $excludedBlocks);
        }

        $acceptedBlocks = $this->getAcceptedBlocks($zone);
        if (count($acceptedBlocks)) {
            $availableBlocks = array_merge($availableBlocks, $acceptedBlocks);
        }

        return array_unique($availableBlocks);
    }

    /**
     * @param string $zone
     *
     * @return array
     */
    public function getExcludedBlocks($zone = 'global')
    {
        return $this->configurations[$zone]['excluded'];
    }

    /**
     * @param string $zone
     *
     * @return array
     */
    public function getAcceptedBlocks($zone = 'global')
    {
        return $this->configurations[$zone]['accepted'];
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
     * @param array  $configuration
     * @param string $zone
     *
     * @return $this
     *
     * @throws InvalidConfigurationException
     */
    public function addConfiguration($configuration, $zone = 'global')
    {
        $zone = (is_int($zone)) ? 'global' : $zone;
        $initialConfiguration = array(
            'excluded' => array(),
            'accepted' => array(),
        );

        $this->configurations[$zone] = $configuration + $initialConfiguration;

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
