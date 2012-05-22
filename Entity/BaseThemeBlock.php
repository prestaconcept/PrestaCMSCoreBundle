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

/**
 * PrestaCMS\CoreBundle\Entity\BaseThemeBlock
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseThemeBlock
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
     * @var integer $position
     */
    protected $position;

    /**
     * @var boolean $is_active
     */
    protected $is_active;

    /**
     * @var string $block_type
     */
    protected $block_type;

    /**
     * @var text $content
     */
    protected $content;

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

    /**
     * Set position
     *
     * @param integer $position
     * @return BaseThemeBlock
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return BaseThemeBlock
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
     * Set block_type
     *
     * @param string $blockType
     * @return BaseThemeBlock
     */
    public function setBlockType($blockType)
    {
        $this->block_type = $blockType;
        return $this;
    }

    /**
     * Get block_type
     *
     * @return string 
     */
    public function getBlockType()
    {
        return $this->block_type;
    }

    /**
     * Set content
     *
     * @param text $content
     * @return BaseThemeBlock
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }
}