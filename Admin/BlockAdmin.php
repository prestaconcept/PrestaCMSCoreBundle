<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Presta\CMSCoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

use Presta\SonataAdminBundle\Admin\PHPCR\BaseAdmin;

/**
 * Admin definition for the Site class
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockAdmin extends BaseAdmin
{
    /**
     * The translation domain to be used to translate messages
     *
     * @var string
     */
    protected $translationDomain = 'PrestaCMSCoreBunde';

    /**
     * @param \Sonata\BlockBundle\Block\BlockServiceManagerInterface $blockManager
     */
    public function setBlockManager(BlockServiceManagerInterface $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $block = $this->getSubject();        
        $service = $this->blockManager->get($block);

        if ($block->getId() > 0) {
            $service->buildEditForm($formMapper, $block);
        } else {
            $service->buildCreateForm($formMapper, $block);
        }        
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, $block)
    {
        return $this->blockManager->validate($errorElement, $block);
        //Sonata code todo remove ? !
        if ($this->inValidate) {
            return;
        }

        // As block can be nested, we only need to validate the main block, no the children
        $this->inValidate = true;
        $this->blockManager->validate($errorElement, $block);
        $this->inValidate = false;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $block = $this->getSubject();
        $service = $this->blockManager->get($block);
        $service->preUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $block = $this->getSubject();
        $service = $this->blockManager->get($block);
        $service->prePersist($object);
    }


}