<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use PrestaCMS\CoreBundle\Model\BaseBlock;

/**
 * PrestaCMS\CoreBundle\Entity\BaseThemeBlock
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseThemeBlock extends BaseBlock
{
    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\Website
     */
    protected $website;

    /**
     * @var string $theme
     */
    protected $theme;

    /**
     * @var string $zone
     */
    protected $zone;

    /**
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * Set locale
     *
     * @param  string $locale
     * @return BaseThemeBlock
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    /**
     * Set website
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @return BaseThemeBlock
     */
    public function setWebsite(\Application\PrestaCMS\CoreBundle\Entity\Website $website = null)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return Application\PrestaCMS\CoreBundle\Entity\Website 
     */
    public function getWebsite()
    {
        return $this->website;
    }
    
    /**
     * Set theme
     *
     * @param string $theme
     * @return BaseThemeBlock
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
     * Set zone
     *
     * @param string $zone
     * @return BaseThemeBlock
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }
}