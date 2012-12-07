<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\BlockBundle\Model\BaseBlock as SonataBaseBlock;
use Symfony\Cmf\Bundle\BlockBundle\Document\BaseBlock as CmfBaseBlock;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * BaseBlock Model
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(translator="attribute")
 */
abstract class BaseBlockXXX extends CmfBaseBlock
{    
    /**
     * @var boolean
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $isEditable;
    
    /**
     * @var boolean
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $isDeletable;
    
    /**
     * @var boolean $is_active
     * @PHPCRODM\Boolean(translated=true)
     */
    protected $isActive;

	/**
	 * @var bool
	 */
	protected $isAdminMode = false;

    /**
     * @PHPCRODM\String(multivalue=true)
     */
    protected $settings;

    /**
     * @PHPCRODM\Locale
     */
    protected $locale;

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

	/**
     * @return boolean 
     */
    public function isEditable()
    {
        return $this->isEditable;
    }

    /**
     * Set if block is editable 
     * 
     * @param  boolean $isEditable
     * @return \Presta\CMSCoreBundle\Block\BaseBlockService
     */
    public function setIsEditable($isEditable)
    {
        $this->isEditable = $isEditable;
        return $this;
    }

    /**
     * @return boolean 
     */
    public function isDeletable() 
    {
        return $this->isDeletable;
    }

    /**
     * Set if block is delitable 
     * 
     * @param  boolean $isDeletable
     * @return \Presta\CMSCoreBundle\Block\BaseBlockService
     */
    public function setIsDeletable($isDeletable)
    {
        $this->isDeletable = $isDeletable;
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
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
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
//
//    /*** Translatable entity ***/
//
//    /**
//     * Used locale to override Translation listener`s locale
//     * this is not a mapped field of entity metadata, just a simple property
//     */
//    protected $locale;
//
//    /**
//     * @var ArrayCollection
//     */
//    protected $translations;
//
//    public function __construct()
//    {
//        $this->translations = new ArrayCollection();
//    }
//
//    /**
//     * Set current locale
//     *
//     * @param  string $locale
//     * @return TranslatableEntity
//     */
//    public function setLocale($locale)
//    {
//        $this->locale = $locale;
//        return $this;
//    }
//
//    /**
//     * Get current locale
//     *
//     * @return string
//     */
//    public function getLocale()
//    {
//        return $this->locale;
//    }
//
//    /**
//     * Returns translations
//     *
//     * @return Doctrine\Common\Collections\ArrayCollection
//     */
//    public function getTranslations()
//    {
//        return $this->translations;
//    }
//
//    /**
//     * Add new translation
//     *
//     * @param  AbstractPersonalTranslation $translation
//     * @return TranslatableEntity
//     */
//    public function addTranslation(AbstractPersonalTranslation $translation)
//    {
//        if (!$this->translations->contains($translation)) {
//            $this->translations[] = $translation;
//            $translation->setObject($this);
//        }
//        return $this;
//    }
//    /*** End - Translatable entity ***/
}