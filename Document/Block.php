<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Document;

use Symfony\Cmf\Bundle\BlockBundle\Document\BaseBlock as CmfBaseBlock;

/**
 * BaseBlock Model
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 * */
class Block extends CmfBaseBlock
{
    /**
     * @var boolean
     */
    protected $isEditable;

    /**
     * @var boolean
     */
    protected $isDeletable;

    /**
     * @var boolean $isActive
     */
    protected $isActive = true;

    /**
     * @var bool
     */
    protected $isAdminMode = false;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $settings;

    /**
     * @var string
     */
    protected $locale;

    /**
     * Returns the type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getHtmlId()
    {
        return str_replace(array('.', '_', '/'), '', $this->getId());
    }

    /**
     * Returns the path of the website content root
     *
     * used by form builder when linking to a content
     *
     * @return
     */
    public function getContentRoot()
    {
        $path = $this->getId();
        if (strpos($path, '/page/') === false) {
            if (strpos($path, '/theme/') !== false) {
                return substr($path, 0, strpos($path, '/theme/')) . '/page';
            }
            return null;
        }

        return substr($path, 0, strpos($path, '/page/') + 5);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
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
     * @param  boolean                                      $isEditable
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
     * @param  boolean                                      $isDeletable
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
            //If translation is not created yet, PHPCR-ODM return null
            return array();
        }

        return $this->settings;
    }

    /**
     * Set is_active
     *
     * @param  boolean        $isActive
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
    public function isActive()
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
}
