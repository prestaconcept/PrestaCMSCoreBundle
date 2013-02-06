<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Sonata\BlockBundle\Block\BaseBlockService as SonataBaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * Base Block Service
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseBlockService extends SonataBaseBlockService
{

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    protected $translator;

    /**
     * @param \Symfony\Component\Translation\Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\Translation\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param $id
     * @param  array  $parameters
     * @return string
     */
    protected function trans($id, array $parameters = array())
    {
        return $this->translator->trans($id, $parameters, 'PrestaCMSCoreBundle');
    }

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
