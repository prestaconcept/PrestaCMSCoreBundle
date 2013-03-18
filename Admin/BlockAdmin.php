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
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

use Presta\CMSCoreBundle\Admin\BaseAdmin;
use Presta\CMSCoreBundle\Document\Theme;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;

/**
 * Admin definition for the Block class
 */
class BlockAdmin extends BaseAdmin
{
    /**
     * Return Website manager
     *
     * @return WebsiteManager
     */
    public function getWebsiteManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('presta_cms.website_manager');
    }

    /**
     * Return Theme manager
     *
     * @return ThemeManager
     */
    public function getThemeManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('presta_cms.theme_manager');
    }

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
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        if ($name == 'create' || $name == 'edit') {
            $parameters = $parameters + array('website' => $this->getRequest()->get('website'));
        }

        return parent::generateUrl($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text')
            ->add('type', 'text')
            ->add('settings', 'array')
            ->add('isActive', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $block = $this->getSubject();
        $service = $this->blockManager->get($block);

        $website = $this->getWebsiteManager()->getCurrentWebsite();
        $currentTheme = $this->getThemeManager()->getTheme($website->getTheme());

        if ($currentTheme instanceof Theme) {
            $service->setBlockStyles($currentTheme->getBlockStyles());
        }

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
     * Load Block
     *
     * @param   $id
     * @return  $subject
     */
    public function getObject($id)
    {
        $block = parent::getObject($id);
        $block->setAdminMode();

        $service = $this->blockManager->get($block);
        $service->load($block);

        return $block;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $service = $this->blockManager->get($object);
        $service->preUpdate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $service = $this->blockManager->get($object);
        $service->prePersist($object);
    }
}
