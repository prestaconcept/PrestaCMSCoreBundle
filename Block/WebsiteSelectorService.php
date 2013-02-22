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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\BlockBundle\Model\BlockInterface;

use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Block\BaseBlockService;

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
    public function getDefaultSettings()
    {
        return array('with_fieldset' => true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        return $this->renderResponse('PrestaCMSCoreBundle:Block:block_website_selector.html.twig', array(
            'websites'  => $this->websiteManager->getAvailableWebsites(),
            'hasMultipleWebsite' => $this->websiteManager->hasMultipleWebsite(),
            'settings'  => $settings
        ), $response);
    }
}
