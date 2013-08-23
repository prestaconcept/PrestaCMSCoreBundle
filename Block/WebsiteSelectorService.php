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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Blok Website Selector
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteSelectorService extends BaseBlockService
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param WebsiteManager  $websiteManager
     */
    public function __construct($name, EngineInterface $templating, WebsiteManager $websiteManager)
    {
        parent::__construct($name, $templating);

        $this->websiteManager = $websiteManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Website Selector';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'with_fieldset' => true,
                'website_id'    => null,
                'locale'        => null,
                'url'           => null
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse(
            'PrestaCMSCoreBundle:Block:block_website_selector.html.twig',
            array(
                'websites'  => $this->websiteManager->getAvailableWebsites(),
                'hasMultipleWebsite' => $this->websiteManager->hasMultipleWebsite(),
                'settings'  => $blockContext->getSettings()
            ),
            $response
        );
    }
}
