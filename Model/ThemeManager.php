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
 * Theme Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class ThemeManager
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $_container;
    
    /**
     * @var array 
     */
    protected $_themes;
    
    /**
     * @var array 
     */
    protected $_blockTypes;

    public function __construct($container, $blockTypes)
    {
        $this->_container = $container;        
        $this->_blockTypes = $blockTypes;
        $this->_themes = array();
    }
    
    /**
     * Return theme by name
     * 
     * @param  string $name
     * @return Theme 
     */
    public function getTheme($name)
    {
        if (!isset($this->_themes[$name])) {
            return false;
        }
        return $this->_themes[$name];
    }
    
    /**
     * Return all themes declared in configuration
     * 
     * @rturn array 
     */
    public function getAvailableThemes()
    {
        return $this->_themes;
    }
    
    /**
     * Add a new theme
     * 
     * @param  array $configuration
     * @return \PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    public function addTheme(array $configuration)
    {
        $theme = new Theme($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setLayout($configuration['layout']);
        $theme->setScreenshot($configuration['screenshot']);
        foreach ($configuration['zones'] as $zoneConfiguration) {
            $zone = new Zone($zoneConfiguration['name'], $zoneConfiguration, $this->_container, $this->_blockTypes);
            $theme->addZone($zone);
        } 
        foreach ($configuration['page_template'] as $templateConfiguration) {
            $template = new Template($templateConfiguration['name'], $templateConfiguration['path']);
            $theme->addPageTemplate($template);
        }        
        $this->_themes[$theme->getName()] = $theme;
        return $this;
    }
}
