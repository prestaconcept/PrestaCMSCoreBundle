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
    /**
     * @var array
     */
    protected $_availableLocales;


    /**
     * @var PrestaCMS\CoreBundle\Model\ThemeManager
     */
    protected $_themeManager;
    

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
     * @param   ThemeManager $themeManager 
     * @return  void
     */
    public function setThemeManager(ThemeManager $themeManager)
    {
        $this->_themeManager = $themeManager;
    }


    /**
     * Add locale param to edit url
     * 
     * @param   $name 
     * @param   array $parameters 
     * @param   bool $absolute
     * @return  string
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
            ->add('name', null, array('label' => 'admin.name'))
            ->add('host', null, array('label' => 'admin.host'))
            ->add('isDefault', 'boolean', array('label' => 'admin.isDefault'))
            ->add('isActive', 'boolean', array('label' => 'admin.isActive'))    
            ->add('defaultLocale', null, array('label' => 'admin.defaultLocale'))
        ;
    }
    

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array('label' => 'admin.name'))
            ->add('host', null, array('label' => 'admin.host'))
            ->add('relativePath', null, array('label' => 'admin.relativePath'))
            ->add('isDefault', 'boolean', array('label' => 'admin.isDefault'))
            ->add('isActive', 'boolean', array('label' => 'admin.isActive'))        
            ->add('defaultLocale', 'locale', array('label' => 'admin.defaultLocale', 'template' => 'PrestaSonataAdminBundle:CRUD:list_locale.html.twig'))
            ->add('availableLocales', 'array', array('label' => 'admin.availableLocales', 'template' => 'PrestaSonataAdminBundle:CRUD:list_array_locale.html.twig'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //SF forms update hasn't been changed in sonata yet !
       // $datagridMapper
       //     ->add('name')
       //     ->add('host')
       // ;
    }


    /**
     * Configure form per locale
     * 
     * @param   Sonata\AdminBundle\Form\FormMapper $formMapper 
     * @return  void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with($this->trans('form_site.label_general'))
                ->add('name', null, array('help' => 'Vix te omnium sententiae.', 'label' => 'admin.name'))
                ->add('host', null, array('label' => 'admin.host'))
                ->add('relativePath', 'text', array('help' => 'Vero verear sapientem has at.', 'label' => 'admin.relativePath', 'required' => false))
                ->add('isDefault', 'checkbox', array('label' => 'admin.isDefault', 'required' => false))
                ->add('isActive', 'checkbox', array('label' => 'admin.isActive', 'required' => false))
                
                ->add('theme', 'choice', array('label' => 'admin.theme', 'choices' => $this->_themeManager->getAvailableThemeCodesForSelect()))
                ->add('defaultLocale', 'choice', array('label' => 'admin.defaultLocale', 'choices' => $this->_availableLocales))
                ->add('availableLocales', 'choice', array(
                    'label'     => 'admin.availableLocales',
                    'choices'   => $this->_availableLocales,
                    'expanded'  => true, 
                    'multiple'  => true
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
     * @param   MenuItemInterface $menu 
     * @param   $action 
     * @param   Sonata\AdminBundle\Admin\Admin $childAdmin
     * @return  void
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, Admin $childAdmin = null)
    {
        if (!in_array($action, array('edit')) || is_null($this->getSubject()->getId())) {
            return;
        }

        $id = $this->getSubject()->getId();

        foreach ($this->getSubject()->getAvailableLocales() as $locale) {
            $menu->addChild(
                $this->trans($locale),
                array('uri' => $this->generateUrl('edit', array('id' => $id, 'locale' => $locale)))
            );

            // select current edited locale item in menu 
            if ($this->getSubject()->getLocale() == $locale) {
                $menu->setCurrentUri($this->generateUrl('edit', array('id' => $id, 'locale' => $locale)));
            }
        }
    }


    /**
     * Refresh object to load locale get in param
     * 
     * @param   $id
     * @return  $subject
     */
    public function getObject($id)
    {
        $subject = parent::getObject($id);

        // Get local in param then get current website default
        if (!is_null($this->getRequest()->get('locale'))) {
            $subject->setLocale($this->getRequest()->get('locale'));
        } else {
            $subject->setLocale($subject->getDefaultLocale());
        }
        
        // Reload website data for selected locale
        if ($subject->getTranslations()->count()) {
            $this->getModelManager()->getEntityManager($this->getClass())->refresh($subject);
        }

        return $subject;
    }    
}