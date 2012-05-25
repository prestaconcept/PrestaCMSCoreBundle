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

    public function __construct( $container)
    {
        $this->_container = $container;
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
     * @param  Theme $theme
     * @return \PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    public function addTheme(Theme $theme)
    {
        $this->_themes[$theme->getName()] = $theme;
        return $this;
    }
}
