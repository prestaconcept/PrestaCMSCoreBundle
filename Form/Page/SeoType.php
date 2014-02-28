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
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class SeoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pageSeo';
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
            ->add('urlRelative', null, array('label' => 'cms_page.form.seo.label.url_relative', 'required' => false))
            ->add('urlComplete', null, array('label' => 'cms_page.form.seo.label.url_complete', 'required' => false))
            ->add(
                'urlCompleteMode',
                null,
                array('label' => 'cms_page.form.seo.label.url_complete_mode', 'required' => false)
            )
            ->add('title', null, array('label' => 'cms_page.form.seo.label.title'))
            ->add('metaKeywords', null, array('label' => 'cms_page.form.seo.label.meta_keywords'))
            ->add('metaDescription', 'textarea', array('label' => 'cms_page.form.seo.label.meta_description'));
    }
}
