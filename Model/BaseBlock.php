<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\BlockBundle\Block\BaseBlockService as SonataBaseBlockService;

/**
 * Base Block Service
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseBlockService extends SonataBaseBlockService
{    
    /**
     * @var boolean 
     */
    protected $_isEditable;
    
    /**
     * @var boolean 
     */
    protected $_isDeletable;
        
    /**
     * @var boolean 
     */
    protected $_isSortable;
    
    /**
     * @var integer 
     */
    protected $_position;
    
    /**
     * @var string 
     */
    protected $_baseTemplate = 'PrestaCMSCoreBundle:Block:base_block.html.twig';
    
    /**
     * @return boolean 
     */
    public function isEditable()
    {
        return $this->_isEditable;
    }

    /**
     * Set if block is editable 
     * 
     * @param  boolean $isEditable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsEditable($isEditable)
    {
        $this->_isEditable = $isEditable;
        return $this;
    }

    /**
     * @return boolean 
     */
    public function isDeletable() 
    {
        return $this->_isDeletable;
    }

    /**
     * Set if block is delitable 
     * 
     * @param  boolean $isDeletable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsDeletable($isDeletable)
    {
        $this->_isDeletable = $isDeletable;
        return $this;
    }

    /**
     * @return boolean 
     */
    public function isSortable()
    {
        return $this->_isSortable;
    }

    /**
     * Set if block is sortable 
     * 
     * @param  boolean $isSortable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsSortable($isSortable)
    {
        $this->_isSortable = $isSortable;
        return $this;
    }

    /**
     * Set base block template (depends of context)
     * 
     * @param  string $baseTemplate
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setBaseTemplate($baseTemplate)
    {
        $this->_baseTemplate = $baseTemplate;
        return $this;
    }
    
    /**
     * Returns block position
     * 
     * @return integer 
     */
    public function getPosition() {
        return $this->_position;
    }

    /**
     * Set block position
     * 
     * @param  integer $position
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setPosition($position) {
        $this->_position = $position;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        $parameters['base_template'] = isset($parameters['base_template']) ? $parameters['base_template'] : $this->_baseTemplate;
        return parent::renderResponse($view, $parameters, $response);
    }
}