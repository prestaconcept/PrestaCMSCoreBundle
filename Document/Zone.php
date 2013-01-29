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

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Cmf\Bundle\BlockBundle\Document\ContainerBlock;

/**
 * Zone : Block container
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class Zone extends ContainerBlock
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
    protected $canAddBlock = true;

    /**
     * @var boolean
     */
    protected $canSortBlock;

    /**
     * @var ArrayCollection
     */
    protected $_blocks;


    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->canAddBlock  = false;
        $this->canSortBlock = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'presta_cms.zone';
    }

    /**
     * Initialise form configuration
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $configuration += array(
            'rows' => 1,
            'cols' => 12,
            'can_add_block' => false,
            'can_sort_block' => false
        );
        $this->setRows($configuration['rows']);
        $this->setCols($configuration['cols']);
        $this->setCanAddBlock($configuration['can_add_block']);
        $this->setCanSortBlock($configuration['can_sort_block']);
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
     * @param boolean $canAddBlock
     */
    public function setCanAddBlock($canAddBlock)
    {
        $this->canAddBlock = $canAddBlock;
    }

    /**
     * @return boolean
     */
    public function getCanAddBlock()
    {
        return $this->canAddBlock;
    }

    /**
     * @param boolean $canSortBlock
     */
    public function setCanSortBlock($canSortBlock)
    {
        $this->canSortBlock = $canSortBlock;
    }

    /**
     * @return boolean
     */
    public function getCanSortBlock()
    {
        return $this->canSortBlock;
    }



    public function getBlocks()
    {
        return $this->getChildren();
    }

    public function addBlock($block)
    {
        $this->addChild($block);
    }


}