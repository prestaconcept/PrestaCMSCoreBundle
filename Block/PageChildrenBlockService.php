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

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;

use Presta\CMSCoreBundle\Block\BaseBlockService;
use Presta\CMSCoreBundle\Model\PageManager;

/**
 * Block Page children, display a list of page children with description and a link
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageChildrenBlockService extends BaseBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_page_children.html.twig';

    /**
     * @var PageManager
     */
    protected $pageManager;

    /**
     * @param PageManager $pageManager
     */
    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title'   => $this->trans('block.default.title'),
            'content' => $this->trans('block.default.content'),
            'page'    => $this->pageManager->getCurrentPage()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array(
            array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
            array('content', 'textarea', array('attr' => array(), 'label' => $this->trans('form.label_content'))),
        );
    }
}
