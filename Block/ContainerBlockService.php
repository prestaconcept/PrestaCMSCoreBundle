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

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Presta\CMSCoreBundle\Block\BaseBlockService;

/**
 * Container block
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ContainerBlockService extends BaseBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_container.html.twig';

    /**
     * Returns available layouts
     *
     * @return array
     */
    protected function getLayouts()
    {
        return array(
            '2-cols',
            '3-cols',
            '4-cols',
            '23-13-cols',
            '13-23-cols'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array_merge(
            array(
                array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
                array('layout', 'choice', array('required' => true, 'choices' => $this->getLayouts(), 'label' => $this->trans('form.label_layout')))
            ),
            parent::getFormSettings($formMapper, $block)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title' => null,
            'layout' => '2-cols',
            'class' => ''
        );
    }
}
