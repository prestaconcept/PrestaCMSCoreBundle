<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Base Block Service for models integration
 *
 * Handle model loading, prePersist, preUpdate and form construction
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseModelBlockService extends BaseBlockService
{
    /**
     * @var Pool
     */
    protected $adminPool;

    /**
     * @param Pool $pool
     */
    public function setAdminPool(Pool $adminPool)
    {
        $this->adminPool = $adminPool;
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
     * Return form settings for linked models and content
     */
    protected function getAdditionalFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        $formSettings = array();
        foreach ($this->getModelFields() as $fieldName => $adminCode) {
            $formSettings[$fieldName] = array($this->getModelBuilder($formMapper, $fieldName, $adminCode), null, array());
        }
        foreach ($this->getContentModelFields() as $fieldName => $adminCode) {
            $formSettings[$fieldName] = array($this->getContentBrowserField($formMapper, $block, $fieldName, $adminCode), null, array());
        }

        return $formSettings + parent::getAdditionalFormSettings($formMapper, $block);
    }

    /**
     * @param  string $adminCode
     * @return Admin
     */
    public function getModelAdmin($adminCode)
    {
        return $this->adminPool->getAdminByAdminCode($adminCode);
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
                'model_manager'            => $modelAdmin->getModelManager(),
                'label'                     => $this->trans('form.label_' . $fieldName)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        foreach ($this->getModelFields() as $fieldName => $adminCode) {
            $model = $block->getSetting($fieldName, null);
            if ($model) {
                $modelAdmin = $this->getModelAdmin($adminCode);
                $modelClass = $modelAdmin->getClass();

                // if model is not already load, do it
                if (!$model instanceof $modelClass) {
                    $model = $modelAdmin->getModelManager()->find($modelAdmin->getClass(), $model, $block->getLocale());
                }
            }
            $block->setSetting($fieldName, $model);
        }
        foreach ($this->getContentModelFields() as $fieldName => $adminCode) {
            $model = $block->getSetting($fieldName, null);
            if ($model) {
                $modelAdmin = $this->getModelAdmin($adminCode);
                $modelClass = $modelAdmin->getClass();

                // if model is not already load, do it
                if (!$model instanceof $modelClass) {
                    $model = $modelAdmin->getModelManager()->getDocumentManager()->findTranslation($modelAdmin->getClass(), $model, $block->getLocale());
                }
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
            $block->setSetting(
                $fieldName,
                is_object($block->getSetting($fieldName)) ? (string)$block->getSetting($fieldName)->getId() : ''
            );
        }
        parent::flattenBlock($block);

        return $block;
    }

    /**
     * Return form field configuration for CMS Page browser
     *
     * @param  FormMapper     $formMapper
     * @param  BlockInterface $block
     * @param  string         $filedName
     * @param  string         $adminCode
     * @return FormBuilder
     */
    public function getContentBrowserField(FormMapper $formMapper, BlockInterface $block, $filedName, $adminCode)
    {
        $modelAdmin = $this->getModelAdmin($adminCode);

        return $formMapper->create(
            $filedName,
            'doctrine_phpcr_odm_tree',
            array(
                'required' => false,
                'choice_list' => array(),
                'model_manager' => $modelAdmin->getModelManager(),
                'root_node' => $block->getContentRoot(),
                'class' => $modelAdmin->getClass(),
                'label' => $this->trans('form.label_' . $filedName)
            )
        );
    }
}
