<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Presta\CMSCoreBundle\Factory\ModelFactoryInterface;

/**
 * Manage themes configuration
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeManager
{
    /**
     * @var ModelFactoryInterface
     */
    protected $themeFactory;

    /**
     * @var array
     */
    protected $themes;

    /**
     * @var array
     */
    protected $themesConfiguration;

    /**
     * @var Theme
     */
    protected $currentTheme;

    public function __construct()
    {
        $this->themes = null;
        $this->themesConfiguration = array();
    }

    /**
     * @param ModelFactoryInterface $themeFactory
     */
    public function setThemeFactory(ModelFactoryInterface $themeFactory)
    {
        $this->themeFactory = $themeFactory;
    }

    /**
     * @return ModelFactoryInterface
     */
    public function getThemeFactory()
    {
        return $this->themeFactory;
    }

    /**
     * @return Theme
     */
    public function getCurrentTheme()
    {
        return $this->currentTheme;
    }

    /**
     * @param array $configuration
     */
    public function addThemeConfiguration(array $configuration)
    {
        $this->themesConfiguration[$configuration['name']] = $configuration;
    }

    /**
     * Return theme configuration
     *
     * @param  string $name theme identifier
     * @return array
     */
    protected function getThemeConfiguration($name)
    {
        if (!isset($this->themesConfiguration[$name])) {
            return false;
        }

        return $this->themesConfiguration[$name];
    }

    /**
     * Return all themes declared in configuration
     *
     * @return array
     */
    public function getAvailableThemes()
    {
        if (!is_array($this->themes)) {
            $this->themes = array();
            foreach ($this->themesConfiguration as $configuration) {
                $this->themes[$configuration['name']] = $this->getThemeFactory()->create($configuration);
            }
        }

        return $this->themes;
    }

    /**
     * Check if a theme is registered or not
     *
     * @param  string $name a theme identifier
     * @return bool
     */
    public function hasTheme($name)
    {
        return (isset($this->themesConfiguration[$name]));
    }

    /**
     * Return theme by name
     *
     * @param  string  $name
     * @param  Website $website
     * @return Theme
     */
    public function getTheme($name, Website $website = null)
    {
        if (!$this->hasTheme($name)) {
            return false;
        }
        $configuration = $this->getThemeConfiguration($name);
        $configuration['website'] = $website;

        $this->currentTheme = $this->getThemeFactory()->create($configuration);

        return $this->currentTheme;
    }
}
