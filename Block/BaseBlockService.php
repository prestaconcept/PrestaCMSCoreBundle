<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\BlockBundle\Block\BaseBlockService as SonataBaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;

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
}