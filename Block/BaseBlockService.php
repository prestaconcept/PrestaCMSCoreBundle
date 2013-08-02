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

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BaseBlockService as SonataBaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * Base Block Service
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseBlockService extends SonataBaseBlockService
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $preview;

    /**
     * @var string
     */
    protected $settingsRoute;

    /**
     * @var array
     */
    protected $blockStyles;

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param array $blockStyles
     */
    public function setBlockStyles($blockStyles)
    {
        $this->blockStyles = $blockStyles;
    }

    /**
     * @return array
     */
    public function getBlockStyles()
    {
        return $this->blockStyles;
    }

    /**
     * Returns available title levels
     *
     * @return array
     */
    protected function getTitleLevels()
    {
        return array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(
            'type'       => $block->getType(),
            'block_id'   => $block->getId()
        );
    }

    /**
     * Returns preview image path
     *
     * @return string
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * Return route to module administration : for blocks displaying data administrate in a specific module
     *
     * @return string
     */
    public function getSettingsRoute()
    {
        return $this->settingsRoute;
    }

    /**
     * Return block template
     *
     * @param  boolean $isAdminMode
     * @return string
     */
    public function getTemplate($isAdminMode)
    {
        if ($isAdminMode && (!is_null($this->preview) || !is_null($this->settingsRoute))) {
            return 'PrestaCMSCoreBundle:Admin/Block:block_admin.html.twig';
        }

        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $settings = $this->getDefaultSettings() + array('block_style' => null, 'title_level' => 'h2');

        $resolver->setDefaults($settings);
    }

    /**
     * Returns block settings for template
     *
     * @param  BlockContextInterface $blockContext
     * @return array
     */
    public function getSettings(BlockContextInterface $blockContext)
    {
        $settings = array_merge(
            $this->getDefaultSettings(),
            $blockContext->getSettings(),
            $blockContext->getBlock()->getSettings()
        );

//        $settings += array(
//            'block_style' => null,
//            'title_level' => 'h2',
//        );

        return $settings;
    }

    /**
     * Return additional form settings
     * Allow to customize base blocks classes like the model one
     *
     * @param  FormMapper      $formMapper
     * @param  BlockInterface  $block
     * @return array
     */
    protected function getAdditionalFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        $additionalFormSettings = array();

        // Block Styles
        $blockStyleChoices = $this->getBlockStyles();
        //Add prefix for translations
        array_walk(
            $blockStyleChoices,
            function (&$item) {
                $item = 'block.style.' . $item;
            }
        );

        $blockStyleChoices = array_combine($this->getBlockStyles(), $blockStyleChoices);

        if (count($this->getBlockStyles()) > 0) {
            $additionalFormSettings['block_style'] = array(
                'block_style',
                'sonata_type_translatable_choice',
                array(
                    'required' => false,
                    'choices'  => $blockStyleChoices,
                    'catalogue'=> 'PrestaCMSCoreBundle',
                    'label'    => $this->trans('form.label_block_style')
                )
            );
        }

        // Title level
        $titleLevelChoices = $this->getTitleLevels();
        //Add prefix for translations
        array_walk(
            $titleLevelChoices,
            function (&$item) {
                $item = 'title.level.'.$item;
            }
        );

        $titleLevelChoices = array_combine($this->getTitleLevels(), $titleLevelChoices);

        if (count($this->getTitleLevels()) > 0) {
            $additionalFormSettings['title_level'] = array(
                'title_level',
                'sonata_type_translatable_choice',
                array(
                    'required' => true,
                    'choices'  => $titleLevelChoices,
                    'catalogue'=> 'PrestaCMSCoreBundle',
                    'label'    => $this->trans('form.label_title_level')
                )
            );
        }

        return $additionalFormSettings;
    }

    /**
     * Returns form settings elements
     *
     * @param  FormMapper      $formMapper
     * @param  BlockInterface  $block
     * @return array
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array();
    }

    /**
     * Return block specific data
     *
     * @param  BlockInterface $block
     * @return array
     */
    protected function getAdditionalViewParameters(BlockInterface $block)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * @param $id
     * @param  array  $parameters
     * @return string
     */
    protected function trans($id, array $parameters = array())
    {
        return $this->translator->trans($id, $parameters, 'PrestaCMSCoreBundle');
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->with($this->trans('block.title.' . $block->getType()))
                ->add(
                    'settings',
                    'sonata_type_immutable_array',
                    array(
                        'keys'  => array_merge($this->getFormSettings($formMapper, $block), $this->getAdditionalFormSettings($formMapper, $block)),
                        'label' => $this->trans('form.label_settings')
                    )
                )
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();
        $block->setSettings($this->getSettings($blockContext));
        $viewParams = array(
            'block_context'  => $blockContext,
            'block'     => $block,
            'settings'  => $block->getSettings()
        );

        $viewParams += $this->getAdditionalViewParameters($block);

        if ($block->isAdminMode()) {
            if (!is_null($this->preview)) {
                $viewParams['block_preview'] = $this->preview;
            }
            if (!is_null($this->settingsRoute)) {
                $viewParams['settings_route'] = $this->settingsRoute;
            }
        }

        return $this->renderResponse(
            $this->getTemplate($block->isAdminMode()),
            $viewParams,
            $response
        );
    }

    /**
     * Remove null values not allowed in PHPCR
     *
     * @param  BlockInterface $block
     * @return BlockInterface
     */
    protected function flattenBlock(BlockInterface $block)
    {
        $settings = $block->getSettings();

        foreach ($settings as $key => $value) {
            if ($value == null) {
                unset($settings[$key]);
                continue;
            }
        }
        $block->setSettings($settings);

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
}
