<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use PrestaCMS\CoreBundle\Model\TranslatableEntity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * PrestaCMS\CoreBundle\Entity\BasePage
 */
abstract class BasePage extends TranslatableEntity
{
    /**
     * @var string $name
     */
    protected $name;
    
    /**
     * @var boolean $isActive
     */
    protected $isActive;

    /**
     * @var string $url
     */
    protected $url;
    
    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $metaKeywords
     */
    protected $metaKeywords;

    /**
     * @var string $metaDescription
     */
    protected $metaDescription;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var integer $left
     */
    protected $left;

    /**
     * @var integer $right
     */
    protected $right;

    /**
     * @var integer $root
     */
    protected $root;

    /**
     * @var integer $level
     */
    protected $level;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $children;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $revisions;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $urlRewrites;

    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\Website
     */
    protected $website;

    /**
     * @var Application\PrestaCMS\CoreBundle\Entity\Page
     */
    protected $parent;
    
    /**
     * @var text
     */
    protected $settings;
    
    public function __construct()
    {
        parent::__construct();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->urlRewrites = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('url', new NotBlank());
        $metadata->addPropertyConstraint('title', new NotBlank());
        $metadata->addPropertyConstraint('metaKeywords', new NotBlank());
        $metadata->addPropertyConstraint('metaDescription', new NotBlank());
        $metadata->addPropertyConstraint('name', new NotBlank());
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return BasePage
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return BasePage
     */
    public function setActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return BasePage
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
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
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return BasePageRevision
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return BasePageRevision
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }
    
    /**
     * Set type
     *
     * @param  string $type
     * @return BasePage
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set left
     *
     * @param integer $left
     * @return BasePage
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * Get left
     *
     * @return integer 
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set right
     *
     * @param integer $right
     * @return BasePage
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    /**
     * Get right
     *
     * @return integer 
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return BasePage
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return BasePage
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Add children
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\Page $children
     * @return BasePage
     */
    public function addPage(\Application\PrestaCMS\CoreBundle\Entity\Page $children)
    {
        $this->children[] = $children;
        return $this;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add revisions
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\PageRevision $revisions
     * @return BasePage
     */
    public function addPageRevision(\Application\PrestaCMS\CoreBundle\Entity\PageRevision $revisions)
    {
        $this->revisions[] = $revisions;
        return $this;
    }

    /**
     * Get revisions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * Add urlRewrites
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\PageUrlRewrite $urlRewrites
     * @return BasePage
     */
    public function addPageUrlRewrite(\Application\PrestaCMS\CoreBundle\Entity\PageUrlRewrite $urlRewrites)
    {
        $this->urlRewrites[] = $urlRewrites;
        return $this;
    }

    /**
     * Get urlRewrites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUrlRewrites()
    {
        return $this->urlRewrites;
    }

    /**
     * Set website
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\Website $website
     * @return BasePage
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
     * Set parent
     *
     * @param Application\PrestaCMS\CoreBundle\Entity\Page $parent
     * @return BasePage
     */
    public function setParent(\Application\PrestaCMS\CoreBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Application\PrestaCMS\CoreBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        if (!is_array($this->settings)) {
            //If translation is not created yet, Gedmo return an empty string
            return array();
        }
        return $this->settings;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSettings($settings = array())
    {
        if (!is_array($this->settings)) {
            //If translation is not created yet, Gedmo return an empty string
            $settings = array();
        }
        $this->settings = $settings;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }
}