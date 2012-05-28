<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;

use \PrestaCMS\CoreBundle\Block\BaseBlockService;

/**
 * Zone Model
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
class Zone
{
    /**
     * @var string 
     */
    protected $_name;
    
    /**
     * @var boolean 
     */
    protected $_canAddBlock;
    
    /**
     * @var boolean 
     */
    protected $_canSortBlock;
        
    /**
     * @var array 
     */
    protected $_blocks;
    
    /**
     * @param string $name
     * @param array  $configuration
     */
    public function __construct($name, $configuration, $container, $blockTypes)
    {
        $this->_name = $name;
        $this->_blocks = array();
        foreach ($configuration['blocks'] as $blockConfiguration) {
            if (!isset($blockTypes[$blockConfiguration['block_type']])) {
                throw new \RuntimeException('Block type : ' . $blockConfiguration['block_type'] . ' has to be defined!');
            }
            $blockClass = $blockTypes[$blockConfiguration['block_type']];
            $block = new $blockClass($blockConfiguration['block_type'], $container->get('templating'));
            $block->setIsEditable($blockConfiguration['is_editable']);
            $block->setIsDeletable($blockConfiguration['is_deletable']);
            $block->setIsSortable($blockConfiguration['is_sortable']);
            $block->setPosition($blockConfiguration['position']);    
            $this->addBlock($block);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * @return boolean
     */
    public function canAddBlock() {
        return $this->_canAddBlock;
    }
    
    /**
     * Set if block can be added to the zone
     * 
     * @param  boolean $canAddBlock
     * @return \PrestaCMS\CoreBundle\Model\Zone 
     */
    public function setCanAddBlock($canAddBlock)
    {
        $this->_canAddBlock = $canAddBlock;
        return $this;
    }

    /**
     * @return boolean
     */
    public function canSortBlock() {
        return $this->_canSortBlock;
    }
    
    /**
     * Set if block can be sorted in the zone
     * 
     * @param  boolean $canSortBlock
     * @return \PrestaCMS\CoreBundle\Model\Zone 
     */
    public function setCanSortBlock($canSortBlock)
    {
        $this->_canSortBlock = $canSortBlock;
        return $this;
    }
    
    /**
     * Returns blocks
     * 
     * @return array 
     */
    public function getBlocks() {
        return $this->_blocks;
    }

    /**
     * Set blocks
     * 
     * @param  array $blocks
     * @return \PrestaCMS\CoreBundle\Model\Zone 
     */
    public function setBlocks(array $blocks) {
        $this->_blocks = $blocks;
        return $this;
    }
    
    /**
     * Add block
     * 
     * @param  \PrestaCMS\CoreBundle\Block\BaseBlockService $block
     * @return \PrestaCMS\CoreBundle\Model\Zone 
     */
    public function addBlock(BaseBlockService $block)
    {
        $this->_blocks[$block->getPosition()] = $block;
        return $this;
    }
}