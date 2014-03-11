<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Presta\CMSCoreBundle\Model\MenuManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Admin definition for the Site class
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
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
     * @var MenuManager
     */
    protected $menuManager;

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
     * @param MenuManager $menuManager
     */
    public function setMenuManager(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
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
                'availableLocales',
                'array',
                array(
                    'template' => 'PrestaCMSCoreBundle:CRUD:list_array_locale.html.twig',
                )
            )
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
            ->add('theme', null, array());
    }

    /**
     * Configure form per locale
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject    = $this->getSubject();

        $formMapper
            ->with($this->trans('website.form.fieldset.general'))
                ->add(
                    'theme',
                    'choice',
                    array('attr' => array('class' => 'sonata-medium locale'),
                        'choices' => $this->themeManager->getAvailableThemes())
                )
                ->add('availableLocales', 'choice', array(
                    'choices'   => array_combine($this->availableLocales, $this->availableLocales),
                    'expanded'  => true,
                    'multiple'  => true,
                    'read_only' => true,
                ))
            ->end();

        if ($subject->getId() != null) {
            $formMapper
                ->with($this->trans('website.form.fieldset.menu'))
                    ->add('mainMenuChildren', 'doctrine_phpcr_odm_tree_manager', array(
                        'root'              => $subject->getMainMenuRootPath(),
                        'label'             => 'website.form.menu.label.main_menu',
                    ))
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getObject($id)
    {
        $object = parent::getObject($id);

        $object->setMainMenuChildren($this->menuManager->getWebsiteMainMenu($object)->getChildren());

        return $object;
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
