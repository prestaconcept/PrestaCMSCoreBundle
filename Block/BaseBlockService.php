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
     * @var \Symfony\Component\Translation\Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $template;

    /**
     * @param \Symfony\Component\Translation\Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\Translation\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
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
     * Return block template
     */
    public function getTemplate()
    {
        //todo handle preview add configurable
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
     * Returns block settings for template
     *
     * @param  \Sonata\BlockBundle\Model\BlockInterface $block
     * @return array
     */
    public function getSettings(BlockInterface $block)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        //handle orm models loading!
        return $settings;
    }

    /**
     * Returns form settings elements
     *
     * @param  \Sonata\AdminBundle\Form\FormMapper      $formMapper
     * @param  \Sonata\BlockBundle\Model\BlockInterface $block
     * @return array
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
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
                        'keys'  => $this->getFormSettings($formMapper, $block),
                        'label' => $this->trans('form.label_settings')
                    )
                )
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        return $this->renderResponse(
            $this->getTemplate(),
            array(
                'block'     => $block,
                'settings'  => $this->getSettings($block)
            ),
            $response
        );
    }
}
