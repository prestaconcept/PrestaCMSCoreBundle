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
 * PrestaCMS\CoreBundle\Entity\BasePageRevision
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BasePageRevision
{   
    /**
     * @var integer $page_id
     */
    protected $page_id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $meta_keywords
     */
    protected $meta_keywords;

    /**
     * @var string $meta_description
     */
    protected $meta_description;

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $blocks;

    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\Page
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
     * Set name
     *
     * @param string $name
     * @return BasePageRevision
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
     * Set title
     *
     * @param string $title
     * @return BasePageRevision
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
     * @return BasePageRevision
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
     * @return BasePageRevision
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
     * Add blocks
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\PageBlock $blocks
     * @return BasePageRevision
     */
    public function addPageBlock(\Application\PrestaCMS\CoreBundle\Entity\PageBlock $blocks)
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
     * @param Application\PrestaCMS\CoreBundle\Entity\Page $page
     * @return BasePageRevision
     */
    public function setPage(\Application\PrestaCMS\CoreBundle\Entity\Page $page = null)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return Application\PrestaCMS\CoreBundle\Entity\Page 
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
}