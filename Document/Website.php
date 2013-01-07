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

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Website Document
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true, translator="attribute", repositoryClass="Presta\CMSCoreBundle\Document\Website\Repository")
 */
class Website
{
    /**
     * to create the document at the specified location. read only for existing documents.
     *
     * @PHPCRODM\Id
     */
    protected $path;

    /**
     * @PHPCRODM\Node
     */
    public $node;

    /**
     * @var string $locale
     *
     * @PHPCRODM\Locale
     */
    protected $locale;

    /**
     * @var string $host
     * @PHPCRODM\String(translated=true)
     */
    protected $host;

    /**
     * @var string $theme
     * @PHPCRODM\String(translated=true)
     */
    protected $theme;

    /**
     * @var string $name
     * @Assert\NotBlank
     * @PHPCRODM\String(translated=true)
     */
    protected $name;

    /**
     * @var boolean $is_active
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $is_active;

    /**
     * @var boolean $is_default
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $is_default;

    /**
     * @var string $default_locale
     * @PHPCRODM\String()
     */
    protected $default_locale;

    /**
     * @var array $available_locales
     * @PHPCRODM\String(multivalue=true)
     */
    protected $available_locales;

    /**
     * Used by Admin edition
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->getPath();
    }

    /**
     * @param array $available_locales
     */
    public function setAvailableLocales($available_locales)
    {
        $this->available_locales = $available_locales;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->available_locales;
    }

    /**
     * @param string $default_locale
     */
    public function setDefaultLocale($default_locale)
    {
        $this->default_locale = $default_locale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->default_locale;
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
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param boolean $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param boolean $is_default
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->path = '/website/' . $name; //todo!
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
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getRoutePrefix()
    {
        return $this->getPath() . '/route/' . $this->getLocale();
    }



}