<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class AjaxBlockService extends BaseModelBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_ajax.html.twig';

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'route' => null,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array(
            array(
                'route',
                'text',
                array(
                    'required' => false,
                    'label'    => $this->trans('form.label_route_label'),
                )
            )
        );
    }
}
