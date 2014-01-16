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

use Doctrine\Bundle\DoctrineBundle\Registry;
use Presta\CMSCoreBundle\Block\BaseBlockService;
use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\PageManager;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Admin;

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
     * @var Registry
     */
    protected $registry;

    /**
     * @param PageManager $pageManager
     */
    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * @param Registry $registry
     */
    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        $page          = $this->pageManager->getCurrentPage();
        $repository    = $this->registry->getRepository('ApplicationSonataMediaBundle:Media');
        $childrenPages = array();
        foreach ($page->getChildren() as $child) {
            /** @var Page $child */
            if ($child->getDescriptionEnabled()) {
                if ($child->getDescriptionMediaId() !== null) {
                    $media = $repository->find($child->getDescriptionMediaId());
                    $child->setDescriptionMedia($media);
                }
                $childrenPages[] = $child;
            }
        }

        return array(
            'title'         => $this->trans('block.default.title'),
            'content'       => $this->trans('block.default.content'),
            'page'          => $page,
            'childrenPages' => $childrenPages,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array(
            array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
            array(
                'content',
                'textarea',
                array('attr' => array('class' => 'wysiwyg'), 'label' => $this->trans('form.label_content'))
            ),
        );
    }
}
