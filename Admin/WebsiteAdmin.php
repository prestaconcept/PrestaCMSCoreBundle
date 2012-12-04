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

use Knp\Menu\ItemInterface as MenuItemInterface;
use Knp\Menu\MenuItem;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Presta\CMSCoreBundle\Model\ThemeManager;

//use Presta\SonataAdminBundle\Admin\BaseAdmin;
use Presta\SonataAdminBundle\Admin\PHPCR\BaseAdmin;

/**
 * Admin definition for the Site class
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteAdmin extends BaseAdmin
{
    /**
     * @var array
     */
    protected $_availableLocales;


    /**
     * @var Presta\CMSCoreBundle\Model\ThemeManager
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
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text')
            ->add('host', 'text')
            ->add('theme', 'text')
            ->add('defaultLocale', 'locale', array('template' => 'PrestaSonataAdminBundle:CRUD:list_locale.html.twig'))
            ->add('availableLocales', 'array', array('template' => 'PrestaSonataAdminBundle:CRUD:list_array_locale.html.twig'))

            ->add('isActive', 'boolean')
            ->add('isDefault', 'boolean')

            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
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

//
//
//    /**
//     * Add locale param to edit url
//     *
//     * @param   $name
//     * @param   array $parameters
//     * @param   bool $absolute
//     * @return  string
//     */
//    public function generateUrl($name, array $parameters = array(), $absolute = false)
//    {
//        if (!isset($parameters['locale']) && $this->hasSubject()) {
//            $parameters['locale'] = $this->getSubject()->getLocale();
//        }
//
//        return $this->routeGenerator->generateUrl($this, $name, $parameters, $absolute);
//    }
//
//
//
//
//
//
//
//    /**
//     * {@inheritdoc}
//     */
//    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
//    {
//        //SF forms update hasn't been changed in sonata yet !
////        $datagridMapper
////            ->add('name')
////            ->add('host')
////        ;
//    }
//
//
    /**
     * Configure form per locale
     *
     * @param   Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return  void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $locale = $this->getTranslatableLocale();
        $formMapper
            ->with($this->trans('form_site.label_general'))
                ->add('name', 'text', array('attr' => array('class' => 'sonata-medium locale locale_' . $locale), 'help' => 'Vix te omnium sententiae.', 'label' => 'admin.name'))
                ->add('host', 'text', array('attr' => array('class' => 'sonata-medium locale locale_' . $locale), 'label' => 'admin.host'))
                ->add('isDefault', 'checkbox', array('attr' => array('class' => 'locale locale_' . $locale), 'label' => 'admin.isDefault', 'required' => false))
                ->add('isActive', 'checkbox', array('attr' => array('class' => 'locale locale_' . $locale), 'label' => 'admin.isActive', 'required' => false))

                ->add('theme', 'choice', array('attr' => array('class' => 'sonata-medium locale locale_' . $locale), 'label' => 'admin.theme', 'choices' => $this->_themeManager->getAvailableThemeCodesForSelect()))
                ->add('defaultLocale', 'choice', array('label' => 'admin.defaultLocale', 'choices' => $this->_availableLocales))
                ->add('availableLocales', 'choice', array(
                    'label'     => 'admin.availableLocales',
                    'choices'   => $this->_availableLocales,
                    'expanded'  => true,
                    'multiple'  => true
                ))
            ->end()
        ;

    }




    /**
     * Allow to select locale to edit in side menu
     *
     * @param   MenuItemInterface $menu
     * @param   $action
     * @param   Sonata\AdminBundle\Admin\Admin $childAdmin
     * @return  void
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {

        $object = $this->getSubject();
        if (!in_array($action, array('edit')) || is_null($this->getUrlsafeIdentifier($object))) {
            return;
        }


        foreach ($object->getAvailableLocales() as $locale) {
            $menuItem = $menu->addChild(
                $this->trans($locale),
                array('uri' => $this->generateObjectUrl('edit', $object, array('translatable_locale' => $locale)))
            );
			$menuItem->setAttribute('class', 'locale locale_' . $locale);

            // select current edited locale item in menu
            if ($object->getLocale() == $locale) {
                $menu->setCurrentUri($this->generateObjectUrl('edit', $object, array('translatable_locale' => $locale)));
            }
        }
    }



}