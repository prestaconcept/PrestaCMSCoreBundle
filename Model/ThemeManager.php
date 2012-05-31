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
    protected $_themesConfiguration;
    
    /**
     * @var array 
     */
    protected $_blockTypes;
    
    
    protected $_repository;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_themes = null;
        $this->_repository = null;
        $this->_themesConfiguration = array();
    }
        
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository =$this->_container->get('doctrine')->getEntityManager()
                ->getRepository('Application\PrestaCMS\CoreBundle\Entity\ThemeBlock');
        }
        return $this->_repository;
    }
    /**
     * Return all themes declared in configuration
     * 
     * @rturn array 
     */
    public function getAvailableThemes()
    {
        if (!is_array($this->_themes)) {
            foreach ($this->_themesConfiguration as $configuration) {
                $this->_themes[$configuration['name']] = $this->_buildTheme($configuration);
            }
        }
        return $this->_themes;
    }
    
    /**
     * Return all themes codes declared in configuration
     * 
     * @rturn array 
     */
    public function getAvailableThemeCodes()
    {
        return array_keys($this->_themesConfiguration);
    }
    
    /**
     * Add a new theme
     * 
     * @param  array $configuration
     * @return \PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    protected function _buildTheme(array $configuration, $website = null)
    {
        $theme = new Theme($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setTemplate($configuration['template']);
        $theme->setScreenshot($configuration['screenshot']);
        $theme->setCols($configuration['cols']);//var_dump(serialize(array('content'=>'<p>hello</p>')));die;
        //Voir pour les éventuels thèmes sans contenu editable!
        $data = array();
        if ($website != null) {
            $data = $this->_getRepository()
                ->getBlocksForWebsiteByZone($website);
            if (count($data) == 0) {
                //If there is no corresponding data, initialisation with default configuration
                $data = $this->_getRepository()->initializeForWebsite($website, $configuration);
            }
        }
        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (!isset($data[$zoneConfiguration['name']])) {
                $data[$zoneConfiguration['name']] = array();
            }
            $zone = new Zone($zoneConfiguration['name'], $zoneConfiguration, $data[$zoneConfiguration['name']]);
            $theme->addZone($zone);
        } 
        foreach ($configuration['page_template'] as $templateConfiguration) {
            $template = new Template($templateConfiguration['name'], $templateConfiguration['path']);
            $theme->addPageTemplate($template);
        }
        return $theme;
    }
    
    /**
     * Add a new theme configuration
     * 
     * @param  array $configuration
     * @return \PrestaCMS\CoreBundle\Model\ThemeManager 
     */
    public function addThemeConfiguration(array $configuration)
    {
        $this->_themesConfiguration[$configuration['name']] = $configuration;
        return $this;
    }
    
    /**
     * Return theme by name
     * 
     * @param  string $name
     * @return Theme 
     */
    public function getTheme($name, $website = null)
    {
        if (!isset($this->_themesConfiguration[$name])) {
            return false;
        }        
        return $this->_buildTheme($this->_themesConfiguration[$name], $website);
    }
}
