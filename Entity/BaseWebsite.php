<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Presta\CMSCoreBundle\Model\TranslatableEntity;

/**
 * Presta\CMSCoreBundle\Entity\BaseWebsite
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseWebsite extends TranslatableEntity
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $host
     */
    protected $host;

    /**
     * @var string relative path
     */
    protected $relative_path;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var boolean $is_active
     */
    protected $is_active;

    /**
     * @var boolean $is_default
     */
    protected $is_default;

    /**
     * @var string $default_locale
     */
    protected $default_locale;

    /**
     * @var array $available_locales
     */
    protected $available_locales;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $pages;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $themeBlocks;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->themeBlocks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return BaseWebsite
     */
    public function setHost($host)
    {
        // Clean the host
        if (strpos($host, 'http://') === 0) {
            $parsedUrl = parse_url($host);
            $this->host = $parsedUrl['host'];
        }
        else {
            $this->host = $host;
        }
        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set relative path
     *
     * @param string $relativePath
     */
    public function setRelativePath($relativePath)
    {
        $this->relative_path = $relativePath;
    }

    /**
     * Get relative path
     *
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * Return website complete url
     *
     * @return string
     */
    public function getUrl($baseUrl = false)
    {
		//On pourra améliorer ça avec un getProtocole()!
        return 'http://' . $this->getHost() . $baseUrl . $this->getRelativePath();
    }

    /**
     * Set theme
     *
     * @param  string $theme
     * @return BaseWebsite
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BaseWebsite
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return BaseWebsite
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set is_default
     *
     * @param boolean $isDefault
     * @return BaseWebsite
     */
    public function setIsDefault($isDefault)
    {
        $this->is_default = $isDefault;
        return $this;
    }

    /**
     * Get is_default
     *
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Set default_locale
     *
     * @param string $defaultLocale
     * @return BaseWebsite
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->default_locale = $defaultLocale;
        return $this;
    }

    /**
     * Get default_locale
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->default_locale;
    }

    /**
     * Set available_locales
     *
     * @param  array $availableLocales
     * @return BaseWebsite
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->available_locales = $availableLocales;
        return $this;
    }

    /**
     * Get default_locale
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->available_locales;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return BaseWebsite
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return BaseWebsite
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return BaseWebsite
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Add pages
     *
     * @param Application\Presta\CMSCoreBundle\Entity\Page $pages
     * @return BaseWebsite
     */
    public function addPage(\Application\Presta\CMSCoreBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
        return $this;
    }

    /**
     * Get pages
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add themeBlocks
     *
     * @param Application\Presta\CMSCoreBundle\Entity\ThemeBlock $themeBlocks
     * @return BaseWebsite
     */
    public function addThemeBlock(\Application\Presta\CMSCoreBundle\Entity\ThemeBlock $themeBlocks)
    {
        $this->themeBlocks[] = $themeBlocks;
        return $this;
    }

    /**
     * Get themeBlocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getThemeBlocks()
    {
        return $this->themeBlocks;
    }
}