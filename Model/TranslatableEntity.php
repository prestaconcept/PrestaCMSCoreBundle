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
use Gedmo\Translatable\Translatable;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * Base for translatable entity
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class TranslatableEntity implements Translatable
{    
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
}