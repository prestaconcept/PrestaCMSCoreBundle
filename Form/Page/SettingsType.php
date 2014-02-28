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
 * @author Matthieu Crinquand <mcrinquand@prestaconcept.net>
 */
class SettingsType extends AbstractType
{
    /**
     * @var array
     */
    protected $templates;

    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pageSettings';
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
            ->add('template', 'choice', array(
                'label'     => 'cms_page.form.settings.label.template',
                'choices'   => $this->templates
            ));
    }
}
