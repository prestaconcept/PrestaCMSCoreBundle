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

/**
 * Theme Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
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
    
    /**
     * @var \Presta\CMSCoreBundle\Model\Theme
     */
    protected $_currentTheme;
    
    /**
     * @var type 
     */
    protected $_repository;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->_themes = null;
        $this->_repository = null;
        $this->_themesConfiguration = array();
    }
        
    /**
     * Return Theme block repository
     * 
     * @return todo
     */
    protected function _getRepository()
    {
        if ($this->_repository == null) {
            $this->_repository =$this->_container->get('doctrine_phpcr.odm.default_document_manager')
                ->getRepository('Presta\CMSCoreBundle\Document\Theme');
        }
        return $this->_repository;
    }

	/**
	 * @return \Presta\CMSCoreBundle\Model\Theme
	 */
	public function getCurrentTheme()
	{
		return $this->_currentTheme;
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
     * Return all themes codes indexed by themes code for select
     * 
     * @return  array
     */
    public function getAvailableThemeCodesForSelect()
    {
        $themeCodes = $this->getAvailableThemeCodes();
        return array_combine($themeCodes, $themeCodes);
    }

    /**
     * Build Theme model with data
     * 
     * @param  array $configuration
     * @return \Presta\CMSCoreBundle\Model\Theme
     */
    protected function _buildTheme(array $configuration, $website = null)
    {
        $theme = new Theme($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setTemplate($configuration['template']);
        $theme->setScreenshot($configuration['screenshot']);
		$theme->setAdminStyle($configuration['admin_style']);
        $theme->setCols($configuration['cols']);//var_dump(serialize(array('content'=>'<p>hello</p>')));die;

        if ($website != null) {
            $zones = $this->_getRepository()->getZones($configuration['name'], $website);
            if (count($zones) == 0) {
                //If there is no corresponding data, initialisation with default configuration
                $zones = $this->_getRepository()->initializeForWebsite($website, $configuration);
            }
        }
		foreach ($configuration['navigations'] as $navigation) {
			$theme->addNavigation($navigation);
		}

        foreach ($configuration['zones'] as $zoneConfiguration) {
            $zone = new Zone($zoneConfiguration['name'], $zoneConfiguration);
            if (isset($zones[$zoneConfiguration['name']])) {
                $zone->setBlocks($zones[$zoneConfiguration['name']]->getBlocks());
            }
            $theme->addZone($zone);
        } 
        foreach ($configuration['page_template'] as $templateName => $templateConfiguration) {
            $template = new Template($templateName, $templateConfiguration['path']);
            $theme->addPageTemplate($template);
        }
        return $theme;
    }
    
    /**
     * Add a new theme configuration
     * 
     * @param  array $configuration
     * @return \Presta\CMSCoreBundle\Model\ThemeManager
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
        $this->_currentTheme = $this->_buildTheme($this->_themesConfiguration[$name], $website);
        return $this->_currentTheme;
    }
    
    /**
     * Returns page templates defined by a theme
     * 
     * @param  string $theme
     * @return false|array 
     */
    public function getPageTemplates($theme)
    {
        if (!isset($this->_themesConfiguration[$theme])) {
            return false;
        }
        return $this->_themesConfiguration[$theme]['page_template'];
    }
    
    /**
     * Return Template model initialised with $data
     * 
     * @param  string $template
     * @param  array $data
     * @return false|Template 
     */
    public function getPageTemplate($template, $data = null)
    {
        $theme = $this->_currentTheme->getName();
        if (!isset($this->_themesConfiguration[$theme]['page_template'][$template])) {
            return false;
        }
        return $this->_buildThemeTemplate($template, $this->_themesConfiguration[$theme]['page_template'][$template], $data);
    }

	/**
	 * Return Template file
	 *
	 * @param  string $templateName
	 * @return false|string
	 */
	public function getPageTemplateFile($templateName)
	{
		$theme = $this->_currentTheme->getName();
		if (!isset($this->_themesConfiguration[$theme]['page_template'][$templateName])) {
			return false;
		}
		return $this->_themesConfiguration[$theme]['page_template'][$templateName]['path'];
	}

	/**
	 * Return Template configuration
	 *
	 * @param  string $template
	 * @param  array $data
	 * @return array
	 */
	public function getPageTemplateConfiguration($template)
	{
		$theme = $this->_currentTheme->getName();
		if (!isset($this->_themesConfiguration[$theme]['page_template'][$template])) {
			return array();
		}
		return $this->_themesConfiguration[$theme]['page_template'][$template];
	}
    
    /**
     * Build template model with data
     * 
     * @param  string $name
     * @param  array $configuration
     * @param  array $data
     * @return \Presta\CMSCoreBundle\Model\Template
     */
    protected function _buildThemeTemplate($name, array $configuration, $zones = null)
    {
        $template = new Template($name, $configuration['path']);
        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (!isset($data[$zoneConfiguration['name']])) {
                $data[$zoneConfiguration['name']] = array();
            }
            $zone = new Zone($zoneConfiguration['name'], $zoneConfiguration);

            if (isset($zones[$zoneConfiguration['name']])) {
                $zone->setBlocks($zones[$zoneConfiguration['name']]->getBlocks());
            }

            $template->addZone($zone);
        } 
        return $template;
    }
}
