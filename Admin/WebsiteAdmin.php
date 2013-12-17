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
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Admin definition for the Site class
 */
class WebsiteAdmin extends BaseAdmin
{
    /**
     * @var array
     */
    protected $availableLocales;

    /**
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * @param array $availableLocales
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    /**
     * @param ThemeManager $themeManager
     */
    public function setThemeManager(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        //Should be create by Fixtures
        //Host handle by configuration
        $collection->remove('create');
        $collection->remove('delete');

        $collection->add(
            'clear_cache',
            $this->getRouterIdParameter() . '/clear_cache',
            array(),
            array(
                'id' => '.+',
            )
        );

        parent::configureRoutes($collection);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text')
            ->add('theme', 'text')
            ->add(
                'defaultLocale',
                'locale',
                array(
                    'template' => 'PrestaCMSCoreBundle:CRUD:list_locale.html.twig'
                )
            )
            ->add(
                'availableLocales',
                'array',
                array(
                    'template' => 'PrestaCMSCoreBundle:CRUD:list_array_locale.html.twig',
                )
            )

            ->add('isActive', 'boolean')
            ->add('isDefault', 'boolean')

            ->add(
                '_action',
                'actions',
                array(
                    'actions'  => array(
                        'show'   => array(),
                        'edit'   => array(),
                        'delete' => array(),
                        'clearCache' => array(
                            'template' => 'PrestaCMSCoreBundle:CRUD:list__action_clearCache.html.twig',
                        ),
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name', null, array())
            ->add('theme', null, array())
            ->add('default', 'boolean', array())
            ->add('active', 'boolean', array())
            ->add('defaultLocale', null, array());
    }

    /**
     * Configure form per locale
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $locale = $this->getObjectLocale();
        $formMapper
            ->with($this->trans('form_site.label_general'))
                ->add(
                    'theme',
                    'choice',
                    array('attr' => array('class' => 'sonata-medium locale locale_' . $locale),
                        'choices' => $this->themeManager->getAvailableThemes())
                )
                ->add(
                    'enabled',
                    'checkbox',
                    array(
                        'attr'      => array('class' => 'locale locale_' . $locale),
                        'required'  => false,
                        'read_only' => true,
                    )
                )
                ->add('availableLocales', 'choice', array(
                    'choices'   => array_combine($this->availableLocales, $this->availableLocales),
                    'expanded'  => true,
                    'multiple'  => true,
                    'read_only' => true,
                ))
            ->end();
    }

    /**
     * Allow to select locale to edit in side menu
     *
     * @param MenuItemInterface $menu
     * @param string            $action
     * @param AdminInterface    $childAdmin
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
                array('uri' => $this->generateObjectUrl('edit', $object, array('locale' => $locale)))
            );
            $menuItem->setAttribute('class', 'locale locale_' . $locale);

            // select current edited locale item in menu
            if ($object->getLocale() == $locale) {
                $menu->setCurrentUri($this->generateObjectUrl('edit', $object, array('locale' => $locale)));
            }
        }
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->configurationPool->getContainer()->get('security.context');
    }

    /**
     * @param string $name
     * @param object $object
     *
     * @return bool
     */
    public function isGranted($name, $object = null)
    {
        if ($name === 'EDIT') {
            return $this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_WEBSITE_EDIT');
        }

        if ($name === 'CLEAR_CACHE') {
            return $this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_CACHE_CLEAR');
        }

        return parent::isGranted($name, $object);
    }
}
