<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestaCMS\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * Admin definition for the Site class
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class WebsiteAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('host')
            ->add('isDefault')
            ->add('isActive')            
            ->add('defaultLocale')
            ->add('title')
            ->add('metaDescription')
            ->add('metaKeywords')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('host')
            ->add('isDefault')
            ->add('isActive')
            
            ->add('defaultLocale')
//            ->add('locale')
//            ->add('enabledFrom')
//            ->add('enabledTo')
//            ->add('create_snapshots', 'string', array('template' => 'SonataPageBundle:SiteAdmin:list_create_snapshots.html.twig'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('host')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with($this->trans('form_site.label_general'))
                ->add('name')
                ->add('host')
                ->add('isDefault', 'checkbox', array('required' => false))
                ->add('isActive', 'checkbox', array('required' => false))
                
                ->add('defaultLocale', 'text', array(
                    'required' => false
                ))
//                ->add('relativePath', null, array('required' => false))
//                ->add('enabledFrom')
//                ->add('enabledTo')
            ->end()
            ->with($this->trans('form_site.label_seo'))
                ->add('title', null, array('required' => false))
                ->add('metaDescription', 'textarea', array('required' => false))
                ->add('metaKeywords', 'textarea', array('required' => false))
            ->end()
        ;
    }
    
}