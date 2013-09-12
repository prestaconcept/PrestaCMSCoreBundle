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

use Doctrine\Common\Collections\Collection;
use Sonata\BlockBundle\Model\BlockInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Zone extends AbstractParentModel
{
    /**
     * @var integer
     */
    protected $cols;

    /**
     * @var integer
     */
    protected $rows;

    /**
     * @var boolean
     */
    protected $editable = true;

    /**
     * @var boolean
     */
    protected $sortable = false;

    /**
     * Initialise form configuration
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $configuration += array(
            'rows'          => 1,
            'cols'          => 12,
            'editable'   => false,
            'sortable'   => false
        );
        $this->setRows($configuration['rows']);
        $this->setCols($configuration['cols']);
        $this->setEditable($configuration['editable']);
        $this->setSortable($configuration['sortable']);
    }

    /**
     * Return id for HTML element
     *
     * @return string
     */
    public function getHtmlId()
    {
        return str_replace(array('.', '_', '/'), '', $this->getId());
    }

    /**
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }

    /**
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
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
     * @param boolean $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @return Collection
     */
    public function getBlocks()
    {
        return $this->getChildren();
    }

    /**
     * @param BlockInterface $block
     */
    public function addBlock(BlockInterface $block)
    {
        $this->addChild($block);
    }

    /**
     * @param Collection $blocks
     */
    public function setBlocks(Collection $blocks)
    {
        $this->setChildren($blocks);
    }
}
