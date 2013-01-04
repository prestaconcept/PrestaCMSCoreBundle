<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
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
 * Page Form
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Presta\CMSCoreBundle\Document\Page',
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
        //SEO
            ->add('url', null, array('label' => 'cms_page.form.seo.label.url'))
            ->add('title', null, array('label' => 'cms_page.form.seo.label.title'))
            ->add('metaKeywords', null, array('label' => 'cms_page.form.seo.label.meta_keywords'))
            ->add('metaDescription', 'textarea', array('label' => 'cms_page.form.seo.label.meta_description'))
        //Settings
            ->add('name', null, array('label' => 'cms_page.form.settings.label.name'))
            ->add('isActive', 'choice', array(
                'choices'   => array(true => 'Yes', false => 'No'),
                'required'  => true,
                'label'     => 'cms_page.form.settings.label.is_active'
            ))
            ->add('settings', 'sonata_type_immutable_array', array(
                'keys' => array(
                    //here add specific type settings based on PageType
                    //var_dump($builder->getForm()->getData());die;
                    //array('content', 'textarea', array('attr' => array('class'=> 'ckeditor'))),
                ),
                'label' => 'form.label_settings'
            ))
        ;
        
    }
}