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

use Presta\CMSCoreBundle\Model\TranslatableEntity;

/**
 * Presta\CMSCoreBundle\Entity\BasePageRevision
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BasePageRevision extends TranslatableEntity
{   
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';
    /**
     * @var integer $page_id
     */
    protected $page_id;

    /**
     * @var date $created
     */
    protected $created;

    /**
     * @var datetime $updated
     */
    protected $updated;
    
    /**
     * @var string $template
     */
    protected $template;
    
    /**
     * @var string $status
     */
    protected $status;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $blocks;

    /**
     * @var Application\Presta\CMSCoreBundle\Entity\Page
     */
    protected $page;

    public function __construct()
    {
        $this->blocks = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set page_id
     *
     * @param integer $pageId
     * @return BasePageRevision
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;
        return $this;
    }

    /**
     * Get page_id
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->page_id;
    }    

    /**
     * Add blocks
     *
     * @param Application\Presta\CMSCoreBundle\Entity\PageRevisionBlock $blocks
     * @return BasePageRevision
     */
    public function addBlock(\Application\Presta\CMSCoreBundle\Entity\PageRevisionBlock $blocks)
    {
        $this->blocks[] = $blocks;
        return $this;
    }

    /**
     * Get blocks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * Set page
     *
     * @param Application\Presta\CMSCoreBundle\Entity\Page $page
     * @return BasePageRevision
     */
    public function setPage(\Application\Presta\CMSCoreBundle\Entity\Page $page = null)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return Application\Presta\CMSCoreBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * Set created
     *
     * @param date $created
     * @return BasePageRevision
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     * @return BasePageRevision
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * Set template
     *
     * @param string $template
     * @return BasePageRevision
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * Set status
     *
     * @param  string $status
     * @return BasePageRevision
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Return block organized by zone
     * 
     * @return array 
     */
    public function getBlocksByZone()
    {
        $blockByZone = array();
        foreach ($this->getBlocks() as $block) {
            $block->setLocale($this->getLocale()); //Todo vérifier que le contenu est traduit !
            if (!isset($blockByZone[$block->getZone()])) {
                $blockByZone[$block->getZone()] = array();
            }
            $blockByZone[$block->getZone()][$block->getPosition()] = $block;
        }
        return $blockByZone;
    }
}