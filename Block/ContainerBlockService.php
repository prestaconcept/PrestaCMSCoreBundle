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

use Presta\CMSCoreBundle\Block\BaseModelBlockService;

/**
 * Container block
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ContainerBlockService extends BaseModelBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_container.html.twig';

    protected function getLayouts()
    {
        return array(
            '2-cols'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
                array('layout', 'choice', array('choices' => $this->getLayouts(), 'label' => $this->trans('form.label_layout'))),
                array('class', 'text', array('required' => false, 'label' => $this->trans('form.label_css_class'))),
            )
        ));

//        $formMapper->add('children', 'sonata_type_collection', array(), array(
//            'edit'   => 'inline',
//            'inline' => 'table',
//            'sortable' => 'position'
//        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title'  => null,
            'layout' => '2-cols',
            'class'  => ''
        );
    }

}