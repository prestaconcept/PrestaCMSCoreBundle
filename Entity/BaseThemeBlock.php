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

//    /**
//     * @var integer $position
//     */
//    protected $position;

//    /**
//     * @var boolean $is_active
//     */
//    protected $is_active;

//    /**
//     * @var string
//     */
//    protected $type;
//
//    /**
//     * @var text
//     */
//    protected $settings;

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

//    /**
//     * Set position
//     *
//     * @param integer $position
//     * @return BaseThemeBlock
//     */
//    public function setPosition($position)
//    {
//        $this->position = $position;
//        return $this;
//    }
//
//    /**
//     * Get position
//     *
//     * @return integer 
//     */
//    public function getPosition()
//    {
//        return $this->position;
//    }

    

//    /**
//     * Set block_type
//     *
//     * @param string $type
//     * @return BaseThemeBlock
//     */
//    public function setType($type)
//    {
//        $this->type = $type;
//        return $this;
//    }
//
//    /**
//     * Get block_type
//     *
//     * @return string 
//     */
//    public function getType()
//    {
//        return $this->type;
//    }

//    /**
//     * Set content
//     *
//     * @param text $content
//     * @return BaseThemeBlock
//     */
//    public function setSettings(array $settings = array())
//    {
//        $this->settings = $settings;
//        return $this;
//    }
//
//    /**
//     * Get content
//     *
//     * @return text 
//     */
//    public function getSettings()
//    {
//        if (!is_array($this->settings)) {
//            return array($this->settings);
//        }
//        return $this->settings;
//    }
//    
//    public function prePersist()
//    {var_dump('persist!');die;
//        $this->settings = serialize($this->settings);
//        var_dump($this);die;
//    }
//    
//    public function postLoad()
//    {
//        $this->settings = unserialize($this->settings);
//    }
}