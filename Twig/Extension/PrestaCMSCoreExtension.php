<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Twig\Extension;

use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Symfony\Cmf\Bundle\CoreBundle\Templating\Helper\CmfHelper;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PrestaCMSCoreExtension extends \Twig_Extension
{
    /**
     * @var CmfHelper
     */
    protected $cmfHelper;

    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    public function __construct(CmfHelper $cmfHelper, RouteManager $routeManager, WebsiteManager $websiteManager)
    {
        $this->cmfHelper      = $cmfHelper;
        $this->routeManager   = $routeManager;
        $this->websiteManager = $websiteManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'presta_cms_core';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('wysiwyg', array($this, 'parseWysiwygContent')),
        );
    }

    /**
     * Parse Wysiwyg content to replace internal links
     *
     * @param  string $content
     * @return string
     */
    public function parseWysiwygContent($content)
    {
        preg_match_all("/##internal#(.*)#/i", $content, $matches, PREG_SET_ORDER);

        $locale = $this->websiteManager->getCurrentWebsite()->getLocale();

        foreach ($matches as $match) {
            $page = $this->cmfHelper->find($match[1]);
            if ($page == null) {
                continue;
            }
            $route = $this->routeManager->getRouteForPage($page, $locale);
            if ($route == null) {
                continue;
            }
            $content = str_replace($match[0], $route->getPath(), $content);
        }

        return $content;
    }
}
