<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Base Block Service for models integration
 *
 * Handle model loading, prePersist, preUpdate and form construction
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseModelBlockService extends BaseBlockService implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     *
     * @api
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Return model fields to map with a sonata_type_model_list
     *
     * @return array
     */
    protected function getModelFields()
    {
        return array();
    }

    /**
     * Return page model fields to map to a doctrine_phpcr_odm_tree
     *
     * @return array
     */
    protected function getContentModelFields()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        $formSettings = array();
        foreach ($this->getModelFields() as $fieldName => $adminCode) {
            $formSettings[] = array($this->getModelBuilder($formMapper, $fieldName, $adminCode), null, array());
        }
        foreach ($this->getPageModelFields() as $fieldName => $adminCode) {
            $formSettings[] = array($this->getModelBuilder($formMapper, $fieldName, $adminCode), null, array());
        }

        return $formSettings;
    }

    /**
     * @return mixed
     */
    public function getModelAdmin($adminCode)
    {
        return $this->container->get($adminCode);
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return \Symfony\Component\Form\FormBuilder
     */
    protected function getModelBuilder(FormMapper $formMapper, $fieldName, $adminCode)
    {
        $modelAdmin = $this->getModelAdmin($adminCode);

        // simulate an association ...
        $fieldDescription = $modelAdmin->getModelManager()->getNewFieldDescriptionInstance($modelAdmin->getClass(), $fieldName);
        $fieldDescription->setAssociationAdmin($modelAdmin);
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(
            array(
                'fieldName' => $fieldName,
                'type'      => \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE
            )
        );

        return $formMapper->create(
            $fieldName,
            'sonata_type_model_list',
            array(
                'sonata_field_description' => $fieldDescription,
                'class'                    => $modelAdmin->getClass(),
                'model_manager'            => $modelAdmin->getModelManager()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $modelFields = array_merge($this->getModelFields(), $this->getContentModelFields());
        foreach ($modelFields as $fieldName => $adminCode) {
            $model = $block->getSetting($fieldName, null);
            if ($model) {
                $modelAdmin = $this->getModelAdmin($adminCode);
                $model = $modelAdmin->getModelManager()->findOneBy($modelAdmin->getClass(), array('id' => $model));
            }
            $block->setSetting($fieldName, $model);
        }
    }

    /**
     * Replace model settings by there ids so it can be stored
     *
     * @param  BlockInterface $block
     * @return BlockInterface
     */
    protected function flattenBlock(BlockInterface $block)
    {
        $modelFields = array_merge($this->getModelFields(), $this->getContentModelFields());
        foreach ($modelFields as $fieldName => $adminCode) {
            $block->setSetting($fieldName, is_object($block->getSetting($fieldName)) ? $block->getSetting($fieldName)->getId() : null);
        }

        return $block;
    }
    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {
        $this->flattenBlock($block);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {
        $this->flattenBlock($block);
    }

    /**
     * Return form field configuration for CMS Page browser
     *
     * @param $filedName
     * @param $root
     * @param $label
     */
    public function getContentBrowserField($filedName, $root, $class)
    {
        return array(
            $filedName,
            'doctrine_phpcr_odm_tree',
            array(
                'choice_list' => array(),
                'model_manager' => $this->modelManager,
                'root_node' => $root,
                'class' => 'Presta\CMSCoreBundle\Document\Page',
                'label' => $this->trans('form.label_' . $filedName)
            )
        );
    }
}
