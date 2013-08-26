<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model\Page;

use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Base page type for CMS
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeCMSPage implements PageTypeInterface
{
    const TAB_CONTENT = 'content';
    const SERVICE_ID  = 'presta_cms.page_type.cms_page';

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var ThemeManager
     */
    protected $themeManager;

    public function __construct(WebsiteManager $websiteManager, ThemeManager $themeManager)
    {
        $this->websiteManager = $websiteManager;
        $this->themeManager   = $themeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::SERVICE_ID;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabs()
    {
        return array (
            'cms_page' => 'content'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabData($tab, $page)
    {
        switch ($tab) {
            case self::TAB_CONTENT:
                $draft = $page;
                $website = $this->websiteManager->getCurrentWebsite();

                return array(
                    'page' 	   => $draft,
                    'locale'   => $page->getLocale(),
                    'website'  => $website,
                    'websiteId' => ($website) ? $website->getId() : null
                );
                break;
            default:
                break;
        }

        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getEditTabTemplate($tab)
    {
        return 'PrestaCMSCoreBundle:Admin/Page/CMSPage:tab_content.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getData($page)
    {
        return array(
            'template' => $this->themeManager->getCurrentTheme()->getPageTemplate($page->getTemplate())->getPath()
        );
    }
}
