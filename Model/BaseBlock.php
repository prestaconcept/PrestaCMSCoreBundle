<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;


use Sonata\BlockBundle\Model\BaseBlock as SonataBaseBlock;

/**
 * BaseBlock Model
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseBlock extends SonataBaseBlock
{    
    /**
     * @var boolean 
     */
    protected $is_editable;
    
    /**
     * @var boolean 
     */
    protected $is_deletable;
    
    
    /**
     * @var boolean $is_active
     */
    protected $is_active;
    
//    /**
//     * @var integer 
//     */
//    protected $_position;
    
    /**
     * @return boolean 
     */
    public function isEditable()
    {
        return $this->is_editable;
    }

    /**
     * Set if block is editable 
     * 
     * @param  boolean $isEditable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsEditable($isEditable)
    {
        $this->is_editable = $isEditable;
        return $this;
    }

    /**
     * @return boolean 
     */
    public function isDeletable() 
    {
        return $this->is_deletable;
    }

    /**
     * Set if block is delitable 
     * 
     * @param  boolean $isDeletable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsDeletable($isDeletable)
    {
        $this->is_deletable = $isDeletable;
        return $this;
    }

//    /**
//     * @return boolean 
//     */
//    public function isSortable()
//    {
//        return $this->_isSortable;
//    }
//
//    /**
//     * Set if block is sortable 
//     * 
//     * @param  boolean $isSortable
//     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
//     */
//    public function setIsSortable($isSortable)
//    {
//        $this->_isSortable = $isSortable;
//        return $this;
//    }
    
    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return BaseThemeBlock
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
    
//    /**
//     * Returns block position
//     * 
//     * @return integer 
//     */
//    public function getPosition() {
//        return $this->_position;
//    }
//
//    /**
//     * Set block position
//     * 
//     * @param  integer $position
//     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
//     */
//    public function setPosition($position) {
//        $this->_position = $position;
//        return $this;
//    }
}