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
 * PrestaCMS\CoreBundle\Entity\BasePageBlock
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BasePageBlock
{
    /**
     * @var integer $page_revision_id
     */
    protected $page_revision_id;

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
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * Set locale
     *
     * @param  string $locale
     * @return BasePageBlock
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Set page_revision_id
     *
     * @param integer $pageRevisionId
     * @return BasePageBlock
     */
    public function setPageRevisionId($pageRevisionId)
    {
        $this->page_revision_id = $pageRevisionId;
        return $this;
    }

    /**
     * Get page_revision_id
     *
     * @return integer 
     */
    public function getPageRevisionId()
    {
        return $this->page_revision_id;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return BasePageBlock
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
     * @return BasePageBlock
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
     * @return BasePageBlock
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
     * @return BasePageBlock
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
     * @return BasePageBlock
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
    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\PageRevision
     */
    protected $pageRevision;


    /**
     * Set pageRevision
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\PageRevision $pageRevision
     * @return BasePageBlock
     */
    public function setPageRevision(\Application\PrestaCMS\CoreBundle\Entity\PageRevision $pageRevision = null)
    {
        $this->pageRevision = $pageRevision;
        return $this;
    }

    /**
     * Get pageRevision
     *
     * @return Application\PrestaCMS\CoreBundle\Entity\PageRevision 
     */
    public function getPageRevision()
    {
        return $this->pageRevision;
    }
}