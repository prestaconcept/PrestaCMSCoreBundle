<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Presta\CMSCoreBundle\Model\Block;
use Doctrine\Common\Collections\Collection;

/**
 * Zone Model
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Zone
{
    protected $id;

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
     * @var ArrayCollection
     */
    protected $_blocks;
    
    protected $_cols;
    
    protected $_rows; 
    
    /**
     * @param string $name
     * @param array  $blocks
     */
    public function __construct($name, $configuration)
    {
        $this->_name = $name;
        $this->_cols = $configuration['cols'];
        $this->_rows = $configuration['rows'];
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
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
     * @return \Presta\CMSCoreBundle\Model\Zone
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
     * @return \Presta\CMSCoreBundle\Model\Zone
     */
    public function setCanSortBlock($canSortBlock)
    {
        $this->_canSortBlock = $canSortBlock;
        return $this;
    }
    
    /**
     * Returns blocks
     * 
     * @return Collection
     */
    public function getBlocks() {
        return $this->_blocks;
    }

    /**
     * Set blocks
     * 
     * @param  Collection $blocks
     * @return \Presta\CMSCoreBundle\Model\Zone
     */
    public function setBlocks(Collection $blocks) {
        $this->_blocks = $blocks;
        return $this;
    }
    
    /**
     * Add block
     * 
     * @param  \Presta\CMSCoreBundle\Model\BaseBlock $block TODO create class!
     * @return \Presta\CMSCoreBundle\Model\Zone
     */
    public function addBlock( $block)
    {
        $this->_blocks[$block->getPosition()] = $block;
        return $this;
    }
    
    /**
     * Return the number of columns the layout is based on
     * 
     * @return integer 
     */
    public function getCols()
    {
        return $this->_cols;
    }
    
    /**
     * Set the number of columns the layout is based on
     * 
     * @param  integer $cols
     * @return \Presta\CMSCoreBundle\Model\Theme
     */
    public function setCols($cols)
    {
        $this->_cols = $cols;
        return $this;
    }
    
    /**
     * Return the number of rows
     * 
     * @return integer 
     */
    public function getRows()
    {
        return $this->_rows;
    }
    
    /**
     * Set the number of rows
     * 
     * @param  integer $rows
     * @return \Presta\CMSCoreBundle\Model\Theme
     */
    public function setRows($rows)
    {
        $this->_rows = $rows;
        return $this;
    }
}