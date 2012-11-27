<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Presta\CMSCoreBundle\Model\BaseBlock;

/**
 * Presta\CMSCoreBundle\Entity\BasePageBlock
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BasePageRevisionBlock  extends BaseBlock
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

//    /**
//     * @var string $type
//     */
//    protected $type;

    
//    /**
//     * Set locale
//     *
//     * @param  string $locale
//     * @return BasePageBlock
//     */
//    public function setLocale($locale)
//    {
//        $this->locale = $locale;
//        return $this;
//    }

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

//    /**
//     * Set position
//     *
//     * @param integer $position
//     * @return BasePageBlock
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
//     * Set is_active
//     *
//     * @param boolean $isActive
//     * @return BasePageBlock
//     */
//    public function setIsActive($isActive)
//    {
//        $this->is_active = $isActive;
//        return $this;
//    }
//
//    /**
//     * Get is_active
//     *
//     * @return boolean 
//     */
//    public function getIsActive()
//    {
//        return $this->is_active;
//    }

//    /**
//     * Set type
//     *
//     * @param  string $type
//     * @return BasePageBlock
//     */
//    public function setType($type)
//    {
//        $this->type = $type;
//        return $this;
//    }
//
//    /**
//     * Get type
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
//     * @return BasePageBlock
//     */
//    public function setContent($content)
//    {
//        $this->content = $content;
//        return $this;
//    }
//
//    /**
//     * Get content
//     *
//     * @return text 
//     */
//    public function getContent()
//    {
//        return $this->content;
//    }
    /**
     * @var Application\Presta\CMSCoreBundle\Entity\PageRevision
     */
    protected $pageRevision;


    /**
     * Set pageRevision
     *
     * @param Application\Presta\CMSCoreBundle\Entity\PageRevision $pageRevision
     * @return BasePageBlock
     */
    public function setPageRevision(\Application\Presta\CMSCoreBundle\Entity\PageRevision $pageRevision = null)
    {
        $this->pageRevision = $pageRevision;
        return $this;
    }

    /**
     * Get pageRevision
     *
     * @return Application\Presta\CMSCoreBundle\Entity\PageRevision
     */
    public function getPageRevision()
    {
        return $this->pageRevision;
    }
}