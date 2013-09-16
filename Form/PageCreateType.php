<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageCreateType extends AbstractType
{
    /**
     * @var string
     */
    protected $rootId;

    /**
     * @var array
     */
    protected $menus;

    /**
     * @var array
     */
    protected $templates;

    public function __construct($rootId, $menus, $templates)
    {
        $this->rootId    = $rootId;
        $this->menus     = $menus;
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'page_create';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Presta\CMSCoreBundle\Doctrine\Phpcr\Page',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translation_domain'] = 'PrestaCMSCoreBundle';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Root id is set if we are adding a child to an existing page
        if ($this->rootId == null) {
            $builder->add(
                'root',
                'choice',
                array(
                    'mapped'   => false,
                    'label'    => 'cms_page.form.page.label.root',
                    'choices'  => $this->menus,
                    'required' => true
                )
            );
        } else {
            $builder->add('root', 'hidden', array('mapped' => false, 'data' => $this->rootId));
        }

        $builder
            ->add(
                'title',
                null,
                array('label' => 'cms_page.form.menu.label.title', 'mapped' => false, 'required' => false)
            )
            ->add(
                'template',
                'choice',
                array(
                    'label'    => 'cms_page.form.page.label.template',
                    'choices'  => $this->templates,
                    'required' => true
                )
            );
    }
}
