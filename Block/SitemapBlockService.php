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

/**
 * Sitemap block can make a simple menu or complete sitemap
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class SitemapBlockService extends BaseModelBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_sitemap.html.twig';

    /**
     * {@inheritdoc}
     */
    protected function getContentModelFields()
    {
        return array('root_node' => 'presta_cms.admin.page');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title'     => $this->trans('block.default.title'),
            'root_node' => null,
            'depth'     => 1
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array(
            array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
            array('depth', 'number', array('label' => $this->trans('form.label_depth')))
        );
    }
}
