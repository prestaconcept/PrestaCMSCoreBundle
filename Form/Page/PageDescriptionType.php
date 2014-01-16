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

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\Pool;
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
     * @var FieldDescriptionInterface
     */
    protected $pool;

    /**
     * @param Pool $pool
     */
    public function __construct($pool)
    {
        $this->pool = $pool;
    }

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
            );

        $builder = $this->addMediaField($builder);

        $builder
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
            );
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return FormBuilderInterface
     */
    protected function addMediaField(FormBuilderInterface $builder)
    {
        $fieldName  = 'descriptionMedia';
        /** @var AdminInterface $mediaAdmin */
        $mediaAdmin = $this->pool->getAdminByAdminCode('sonata.media.admin.media');
        $modelBuilder = $builder
            ->create(
                $fieldName,
                'sonata_type_model_list',
                array(
                    'sonata_field_description' => $this->getMediaFieldDescription($fieldName, $mediaAdmin),
                    'class'                    => $mediaAdmin->getClass(),
                    'model_manager'            => $mediaAdmin->getModelManager(),
                    'label'                    => 'cms_page.form.description.label.media',
                    'required'                 => false,
                    'btn_delete'               => false,
                )
            );

        $builder
            ->add($modelBuilder);

        return $builder;
    }

    /**
     * @param string         $fieldName
     * @param AdminInterface $mediaAdmin
     *
     * @return FieldDescriptionInterface
     */
    protected function getMediaFieldDescription($fieldName, $mediaAdmin)
    {
        // simulate an association ...
        $fieldDescription = $mediaAdmin->getModelManager()->getNewFieldDescriptionInstance(
            $mediaAdmin->getClass(),
            $fieldName
        );
        $fieldDescription->setAssociationAdmin($mediaAdmin);
        $fieldDescription->setAdmin($this->pool->getAdminByAdminCode('presta_cms.admin.page'));
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(
            array(
                'fieldName' => $fieldName,
                'type'      => \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE
            )
        );

        return $fieldDescription;
    }
}
