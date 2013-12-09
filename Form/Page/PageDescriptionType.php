<?php
/**
 * This file is part of PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class PageDescriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pageDescription';
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
        $builder
            ->add(
                'descriptionTitle',
                null,
                array(
                    'label'    => 'cms_page.form.description.label.title',
                    'required' => true,
                )
            )
            ->add(
                'descriptionContent',
                'textarea',
                array(
                    'label'    => 'cms_page.form.description.label.content',
                    'required' => false,
                )
            )
            ->add(
                'descriptionEnabled',
                'checkbox',
                array(
                    'label'    => 'cms_page.form.description.label.enabled',
                    'required' => false,
                )
            )
        ;
    }
}
