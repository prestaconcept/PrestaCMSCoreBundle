<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document;

/**
 * Website Document
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 */
class Website
{
    const WEBSITE_PREFIX    = 'website';
    const THEME_PREFIX      = 'theme';
    const ROUTE_PREFIX      = 'route';
    const MENU_PREFIX       = 'menu';
    const PAGE_PREFIX       = 'page';

    /**
     * to create the document at the specified location. read only for existing documents.
     *
     */
    protected $id;

//    /**
//     * @PHPCRODM\Node
//     */
//    public $node;

    /**
     * @var string $locale
     *
     */
    protected $locale;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var boolean $active
     */
    protected $active;

    /**
     * @var boolean $default
     */
    protected $default;

    /**
     * @var string $defaultLocale
     */
    protected $defaultLocale;

    /**
     * @var array $availableLocales
     */
    protected $availableLocales;

    public function __construct()
    {
        $this->setActive(true);
        $this->setDefault(false);
        $this->availableLocales = array();
    }

    /**
     * Used by Admin edition
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getPath();
    }

    /**
     * @param array $availableLocales
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        if (is_null($this->defaultLocale)) {
            return array_shift($this->availableLocales);
        }

        return $this->defaultLocale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param boolean $isActive
     */
    public function setActive($isActive)
    {
        $this->active = $isActive;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $isDefault
     */
    public function setDefault($isDefault)
    {
        $this->default = $isDefault;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->id = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRoutePrefix()
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . self::ROUTE_PREFIX . DIRECTORY_SEPARATOR . $this->getLocale();
    }

    /**
     * @return string
     */
    public function getMenuRoot()
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . self::MENU_PREFIX;
    }

    /**
     * @return string
     */
    public function getPageRoot()
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . self::PAGE_PREFIX;
    }
}
