<?php

namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;

class Website implements TranslatableInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string $locale
     */
    protected $locale;

    /**
     * @var boolean $enabled
     */
    protected $enabled = true;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var array $availableLocales
     */
    protected $availableLocales;

    public function __construct()
    {
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
     * Explicitly set the primary id, if the storage layer permits this.
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
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
}
