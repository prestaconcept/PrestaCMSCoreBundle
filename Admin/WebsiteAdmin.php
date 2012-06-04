<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
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
     * @var array 
     */
    protected $_availableLocales;
    
    /**
     * Set available locales : called via DI
     * 
     * @param array $availableLocales 
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->_availableLocales = $availableLocales;
    }
    
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
            ->add('relativePath')
            ->add('isDefault', 'boolean')
            ->add('isActive', 'boolean')            
            ->add('defaultLocale', 'locale', array('template' => 'PrestaSonataAdminBundle:CRUD:list_locale.html.twig'))
            ->add('availableLocales', 'array', array('template' => 'PrestaSonataAdminBundle:CRUD:list_array_locale.html.twig'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //SF forms update hasn't been changed in sonata yet !
//        $datagridMapper
//            ->add('name')
//            ->add('host')
//        ;
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
                ->add('relativePath', 'text', array('required' => false))
                ->add('isDefault', 'checkbox', array('required' => false))
                ->add('isActive', 'checkbox', array('required' => false))
                
                ->add('defaultLocale', 'choice', array('choices' => $this->_availableLocales))
                ->add('availableLocales', 'choice', array(
                    'choices' => $this->_availableLocales,
                    'expanded'=> true, 
                    'multiple'=> true
                ))
            ->end();
        if (count($this->getSubject()->getAvailableLocales()) == 0) {
            return;
        }
        foreach ($this->getSubject()->getAvailableLocales() as $locale) {
            $formMapper->with($this->trans('form_site.label_locale_settings') . ' : ' . $locale)
                    //Todo voir comment gérer les trad !
                    //avec un __set __get ça me sort Notice: Indirect modification of overloaded property
//                  ->add('translation_host_fr', 'text')
//                  ->add('translation-relative_path-fr', 'text')
//                ->add('title', null, array('required' => false))
//                ->add('metaDescription', 'textarea', array('required' => false))
//                ->add('metaKeywords', 'textarea', array('required' => false))
            ->end();
        }
    }
    
}