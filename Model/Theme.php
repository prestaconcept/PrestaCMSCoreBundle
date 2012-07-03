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
    protected $_template;
        
    /**
     * @var string 
     */
    protected $_screenshot;

	/**
	 * @var string
	 */
	protected $adminStyle;

    /**
     * @var integer 
     */
    protected $_cols;
    
    /**
     * @var array 
     */
    protected $_zones;
    
    /**
     * @var array 
     */
    protected $_pageTemplates;
    
    /**
     * @var array 
     */
    protected $_navigations;
    
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name  = $name;
        $this->_zones = array();
        $this->_pageTemplates = array();
        $this->_navigations = array();
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
     * Returns template
     * 
     * @return string 
     */
    public function getTemplate() {
        return $this->_template;
    }

    /**
     * Set template
     * 
     * @param  string $template
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setTemplate($template) {
        $this->_template = $template;
        return $this;
    }
        
    /**
     * Returns screenshot
     * 
     * @return string 
     */
    public function getScreenshot() {
        return $this->_screenshot;
    }

    /**
     * Set screenshot
     * 
     * @param  string $screenshot
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setScreenshot($screenshot) {
        $this->_screenshot = $screenshot;
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
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setCols($cols)
    {
        $this->_cols = $cols;
        return $this;
    }
    
    /**
     * Returns zones
     * 
     * @return array 
     */
    public function getZones() {
        return $this->_zones;
    }
    
    /**
     * Set zones
     * 
     * @param  array $zones
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setZones(array $zones) {
        $this->_zones = $zones;
        return $this;
    }
    
    /**
     * Add a zone
     * 
     * @param  Zone $zone
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function addZone(Zone $zone)
    {
        $this->_zones[$zone->getName()] = $zone;
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
        $this->_pageTemplates[$template->getName()] = $template;
        return $this;
    }
    
    /**
     * Returns navigations
     * 
     * @return array 
     */
    public function getNavigations() {
        return $this->_navigations;
    }

    /**
     * Set navigations
     * 
     * @param  array $navigations
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function setNavigations(array $navigations) {
        $this->_navigations = $navigations;
        return $this;
    }
    
    /**
     * Add a navigation
     * 
     * @param  string $navigation
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    public function addNavigation($navigation)
    {
        $this->_navigations[$navigation] = $navigation;
        return $this;
    }

	/**
	 * @param string $adminStyle
	 */
	public function setAdminStyle($adminStyle)
	{
		$this->adminStyle = $adminStyle;
	}

	/**
	 * @return string
	 */
	public function getAdminStyle()
	{
		return $this->adminStyle;
	}
}