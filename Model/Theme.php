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

/**
 * Theme Model
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
class Theme
{
    /**
     * @var string 
     */
    protected $_name;
    
    /**
     * @var string 
     */
    protected $_description;
    
    /**
     * @var string 
     */
    protected $_layout;
    
    /**
     * @var array 
     */
    protected $_pageTemplates;
    
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
        $this->_pageTemplates = array();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Returns description
     * 
     * @param  string $description
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    /**
     * Returns layout
     * 
     * @return string 
     */
    public function getLayout() {
        return $this->_layout;
    }

    /**
     * Set layout
     * 
     * @param  string $layout
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setLayout($layout) {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * Returns pages templates
     * 
     * @return array 
     */
    public function getPageTemplates() {
        return $this->_pageTemplates;
    }

    /**
     * Set pages templates
     * 
     * @param  array $pageTemplates
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setPageTemplates(array $pageTemplates) {
        $this->_pageTemplates = $pageTemplates;
        return $this;
    }
    
    /**
     * Add a page template
     * 
     * @param  Template $template
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function addPageTemplate(Template $template)
    {
        $this->_pageTemplates[$template->getName()] = $template->getPath();
        return $this;
    }
}