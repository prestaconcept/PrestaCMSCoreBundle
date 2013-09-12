<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Symfony\Cmf\Bundle\BlockBundle\Model\AbstractBlock as AbstractBlockModel;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Block extends AbstractBlockModel
{
    /**
     * @var boolean
     */
    protected $editable = true;

    /**
     * @var boolean
     */
    protected $deletable = true;

    /**
     * @var bool
     */
    protected $adminMode = false;

    /**
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
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
     * @param boolean $deletable
     */
    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;
    }

    /**
     * @return boolean
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    /**
     * @param boolean $editable
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * Set admin mode
     */
    public function setAdminMode()
    {
        $this->adminMode = true;
    }

    /**
     * @return boolean
     */
    public function isAdminMode()
    {
        return $this->adminMode;
    }
}
