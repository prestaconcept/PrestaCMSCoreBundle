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

use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use PrestaCMS\CoreBundle\Model\ThemeManager;


/**
 * Admin definition for the Site class
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class WebsiteAdmin extends Admin
{
    protected
        $_availableLocales,
        $_themeManager
    ;
    

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
     * Setter for _themeManager
     *
     * @author Alain Flaus <aflaus@prestaconcept.net
     */
    public function setThemeManager(ThemeManager $themeManager)
    {
        $this->_themeManager = $themeManager;
    }


    /**
     * Add culture param to edit url
     *
     * @author Alain Flaus <aflaus@prestaconcept.net
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        if (!isset($parameters['locale']) && $this->hasSubject()) {
            $parameters['locale'] = $this->getSubject()->getLocale();
        }

        return $this->routeGenerator->generateUrl($this, $name, $parameters, $absolute);
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
                
                ->add('theme', 'choice', array('choices' => $this->_themeManager->getAvailableThemeCodes()))
                ->add('defaultLocale', 'choice', array('choices' => $this->_availableLocales))
                ->add('availableLocales', 'choice', array(
                    'choices' => $this->_availableLocales,
                    'expanded'=> true, 
                    'multiple'=> true
                ))
            ->end()
        ;

        if (count($this->getSubject()->getAvailableLocales()) == 0) {
            return;
        }
    }


    /**
     * Allow to select locale to edit in side menu
     *
     * @author  Alain Flaus <aflaus@prestaconcept.net>
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, Admin $childAdmin = null)
    {
        if (!in_array($action, array('edit')) || is_null($this->getSubject()->getId())) {
            return;
        }

        $id = $this->getSubject()->getId();

        foreach ($this->getSubject()->getAvailableLocales() as $locale) {
            $menu->addChild(
                $locale,
                array('uri' => $this->generateUrl('edit', array('id' => $id, 'locale' => $locale)))
            );
        }
    }


    /**
     * Refresh object to load locale get in param
     *
     * @author Alain Flaus <aflaus@prestaconcept.net
     */
    public function getObject($id)
    {
        $subject = parent::getObject($id);

        //Set locale and get translated data
        $subject->setLocale($this->getRequest()->get('locale'));
        if ($subject->getTranslations()->count()) {
            $this->getModelManager()->getEntityManager($this->getClass())->refresh($subject);
        }        
        
        return $subject;
    }    
}