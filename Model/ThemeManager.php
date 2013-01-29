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

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Document\Zone;

/**
 * Theme Manager
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManager
{
//    const WEBSITE_CLASS = 'Presta\CMSCoreBundle\Document\Website';

    /**
     * @var \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var array 
     */
    protected $themes;
    
    /**
     * @var array 
     */
    protected $themesConfiguration;
    
//    /**
//     * @var array
//     */
//    protected $_blockTypes;
    
    /**
     * @var \Presta\CMSCoreBundle\Model\Theme
     */
    protected $currentTheme;
    
    /**
     * @var type 
     */
    protected $_repository;

    public function __construct()
    {
        $this->themes = null;
        $this->_repository = null;
        $this->themesConfiguration = array();
    }

    /**
     * @param \Sonata\AdminBundle\Model\ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * @return \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    public function getModelManager()
    {
        return $this->modelManager;
    }
        
//    /**
//     * Return Theme block repository
//     *
//     * @return todo
//     */
//    protected function _getRepository()
//    {
//        if ($this->_repository == null) {
//            $this->_repository =$this->_container->get('doctrine_phpcr.odm.default_document_manager')
//                ->getRepository('Presta\CMSCoreBundle\Document\Theme');
//        }
//        return $this->_repository;
//    }

    /**
     * Add a new theme configuration
     *
     * @param  array $configuration
     * @return \Presta\CMSCoreBundle\Model\ThemeManager
     */
    public function addThemeConfiguration(array $configuration)
    {
        $this->themesConfiguration[$configuration['name']] = $configuration;

        return $this;
    }

    /**
     * Return all themes codes declared in configuration
     *
     * @rturn array
     */
    public function getAvailableThemeCodes()
    {
        return array_keys($this->themesConfiguration);
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
     * Return all themes declared in configuration
     *
     * @rturn array
     */
    public function getAvailableThemes()
    {
        if (!is_array($this->themes)) {
            foreach ($this->themesConfiguration as $configuration) {
                $this->themes[$configuration['name']] = $this->_buildTheme($configuration);
            }
        }
        return $this->themes;
    }

	/**
	 * @return \Presta\CMSCoreBundle\Model\Theme
	 */
	public function getCurrentTheme()
	{
		return $this->currentTheme;
	}





    /**
     * Build Theme model with data
     * 
     * @param  array $configuration
     * @return \Presta\CMSCoreBundle\Model\Theme
     */
    protected function _buildTheme(array $configuration, $website = null)
    {
        $configuration += array(
            'cols' => 12,
            'admin_style' => null,
            'zones' => array(),
            'page_template' => array()
        );

        $theme = new Theme($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setTemplate($configuration['template']);
        $theme->setScreenshot($configuration['screenshot']);
		$theme->setAdminStyle($configuration['admin_style']);
        $theme->setCols($configuration['cols']);//var_dump(serialize(array('content'=>'<p>hello</p>')));die;

//        if ($website != null) {
//            $zones = $this->_getRepository()->getZones($configuration['name'], $website);
//            if (count($zones) == 0) {
//                //If there is no corresponding data, initialisation with default configuration
//                $zones = $this->_getRepository()->initializeForWebsite($website, $configuration);
//            }
//        }

        foreach ($configuration['zones'] as $zoneConfiguration) {
            $zone = new Zone($zoneConfiguration['name']);
            $zone->setConfiguration($zoneConfiguration);
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
     * Return theme by name
     * 
     * @param  string $name
     * @return Theme 
     */
    public function getTheme($name, $website = null)
    {
        if (!isset($this->themesConfiguration[$name])) {
            return false;
        }
        $this->currentTheme = $this->_buildTheme($this->themesConfiguration[$name], $website);

        return $this->currentTheme;
    }
    
//    /**
//     * Returns page templates defined by a theme
//     *
//     * @param  string $theme
//     * @return false|array
//     */
//    public function getPageTemplates($theme)
//    {
//        if (!isset($this->themesConfiguration[$theme])) {
//            return false;
//        }
//
//        return $this->themesConfiguration[$theme]['page_template'];
//    }
    
    /**
     * Return Template model initialised with $data
     * 
     * @param  string $template
     * @param  array $data
     * @return false|Template 
     */
    public function getPageTemplate($template, $page)
    {
        if (!$this->currentTheme instanceof Theme) {
            return false;
        }
        $theme = $this->currentTheme->getName();
        if (!isset($this->themesConfiguration[$theme]['page_template'][$template])) {
            return false;
        }

        return $this->buildThemeTemplate($template, $this->themesConfiguration[$theme]['page_template'][$template], $page);
    }

	/**
	 * Return Template file
     *
     * Use when display a page on front
	 *
	 * @param  string $templateName
	 * @return false|string
	 */
	public function getPageTemplateFile($templateName)
	{
        if (!$this->currentTheme instanceof Theme) {
            return false;
        }
		$theme = $this->currentTheme->getName();
		if (!isset($this->themesConfiguration[$theme]['page_template'][$templateName])) {
			return false;
		}

		return $this->themesConfiguration[$theme]['page_template'][$templateName]['path'];
	}

	/**
	 * Return Template configuration
     *
     * Used for page creation
	 *
	 * @param  string $template
	 * @param  array $data
	 * @return array
	 */
	public function getPageTemplateConfiguration($template)
	{
        if (!$this->currentTheme instanceof Theme) {
            return false;
        }
		$theme = $this->currentTheme->getName();
		if (!isset($this->themesConfiguration[$theme]['page_template'][$template])) {
			return array();
		}

		return $this->themesConfiguration[$theme]['page_template'][$template];
	}
    
    /**
     * Build template model with data
     * 
     * @param  string $name
     * @param  array $configuration
     * @param  array $data
     * @return \Presta\CMSCoreBundle\Model\Template
     */
    protected function buildThemeTemplate($name, array $configuration, $page = null)
    {
        $zones = $page->getZones();
        $template = new Template($name, $configuration['path']);
        foreach ($configuration['zones'] as $zoneConfiguration) {
            if (!isset($data[$zoneConfiguration['name']])) {
                $data[$zoneConfiguration['name']] = array();
            }
            $zone = new Zone($zoneConfiguration['name'], $zoneConfiguration);
            $zone->setId($page->getPath() . '/' . $zoneConfiguration['name']); //voir pour faire un set parent ?
            if (isset($zones[$zoneConfiguration['name']])) {
                $zone->setBlocks($zones[$zoneConfiguration['name']]->getBlocks());
            }
            $template->addZone($zone);
        }

        return $template;
    }
}
