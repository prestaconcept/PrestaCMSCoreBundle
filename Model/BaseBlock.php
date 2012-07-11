<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\BlockBundle\Model\BaseBlock as SonataBaseBlock;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * BaseBlock Model
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseBlock extends SonataBaseBlock
{    
    /**
     * @var boolean 
     */
    protected $is_editable;
    
    /**
     * @var boolean 
     */
    protected $is_deletable;
    
    /**
     * @var boolean $is_active
     */
    protected $is_active;

	/**
	 * @var bool
	 */
	protected $isAdminMode = false;

	/**
     * @return boolean 
     */
    public function isEditable()
    {
        return $this->is_editable;
    }

    /**
     * Set if block is editable 
     * 
     * @param  boolean $isEditable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsEditable($isEditable)
    {
        $this->is_editable = $isEditable;
        return $this;
    }

    /**
     * @return boolean 
     */
    public function isDeletable() 
    {
        return $this->is_deletable;
    }

    /**
     * Set if block is delitable 
     * 
     * @param  boolean $isDeletable
     * @return \PrestaCMS\CoreBundle\Block\BaseBlockService 
     */
    public function setIsDeletable($isDeletable)
    {
        $this->is_deletable = $isDeletable;
        return $this;
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
	 * Set admin mode
	 */
	public function setAdminMode()
	{
		$this->isAdminMode = true;
	}

	/**
	 * @return boolean
	 */
	public function isAdminMode()
	{
		return $this->isAdminMode;
	}

    /*** Translatable entity ***/
    
    /**
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    protected $locale;
    
    /**
     * @var ArrayCollection 
     */
    protected $translations;
        
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
    
    /**
     * Set current locale
     *
     * @param  string $locale
     * @return TranslatableEntity
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    /**
     * Get current locale
     * 
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    /**
     * Returns translations
     * 
     * @return Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add new translation
     * 
     * @param  AbstractPersonalTranslation $translation 
     * @return TranslatableEntity
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setObject($this);
        }
        return $this;
    }
    /*** End - Translatable entity ***/
}