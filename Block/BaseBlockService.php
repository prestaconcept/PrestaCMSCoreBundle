<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\BlockBundle\Block\BaseBlockService as SonataBaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;


/**
 * Base Block Service
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 */
abstract class BaseBlockService extends SonataBaseBlockService
{    
    /**
     * {@inheritdoc}
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(
            'type'       => $block->getType(),
            'block_id'   => $block->getId()
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array();
    }
}