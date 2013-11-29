<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\Website;
use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\Theme;

/**
 * CMS data collector for the symfony web profiling
 *
 * @author Alain Flaus <aflaus@prestaconcept.net>
 */
class CMSDataCollector extends DataCollector
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var ThemeManager
     */
    protected $themeManager;

    /**
     * @var PageManager
     */
    protected $pageManager;

    /**
     * @var bool
     */
    protected $cacheEnabled;

    /**
     * @param WebsiteManager    $websiteManager
     * @param ThemeManager      $themeManager
     * @param PageManager       $pageManager
     */
    public function __construct(WebsiteManager $websiteManager, ThemeManager $themeManager, PageManager $pageManager)
    {
        $this->websiteManager   = $websiteManager;
        $this->themeManager     = $themeManager;
        $this->pageManager      = $pageManager;
    }

    /**
     * Collect data
     *
     * @param  Request      $request
     * @param  Response     $response
     * @param  \Exception   $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'currentWebsite'    => null,
            'currentTheme'      => null,
            'currentPage'       => null
        );

        $this->collectWebsiteData();
        $this->collectThemeData();
        $this->collectPageData();
    }

    /**
     * Add website datas to collect
     */
    protected function collectWebsiteData()
    {
        $currentWebsite = $this->websiteManager->getCurrentWebsite();

        if (!is_null($currentWebsite)) {
            $this->data['currentWebsite'] = array(
                'name'      => $currentWebsite->getName(),
                'path'      => $currentWebsite->getPath(),
                'locale'    => $currentWebsite->getLocale(),
            );
        }
    }

    /**
     * Add theme datas to collect
     */
    protected function collectThemeData()
    {
        $currentTheme = $this->themeManager->getCurrentTheme();

        if (!is_null($currentTheme)) {
            $this->data['currentTheme'] = array(
                'name'  => $currentTheme->getName(),
            );
        }
    }

    /**
     * Add page datas to collect
     */
    protected function collectPageData()
    {
        $currentPage = $this->pageManager->getCurrentPage();

        if (!is_null($currentPage)) {
            $this->data['currentPage'] = array(
                'name'  => $currentPage->getName(),
                'type'  => $currentPage->getType(),
            );
            $this->data['cache'] = array(
                'enabled' => $this->cacheEnabled,
                'private' => $currentPage->getCachePrivate(),
                'max_age' => $currentPage->getCacheMaxAge(),
                'shared_max_age'    => $currentPage->getCacheSharedMaxAge(),
                'must_revalidate'   => $currentPage->getCacheMustRevalidate(),
                'last_modified'     => $currentPage->getLastCacheModifiedDate()
            );
        }
    }

    /**
     * @return Website
     */
    public function getCurrentWebsite()
    {
        return $this->data['currentWebsite'];
    }

    /**
     * @return Theme
     */
    public function getCurrentTheme()
    {
        return $this->data['currentTheme'];
    }

    /**
     * @return Page
     */
    public function getCurrentPage()
    {
        return $this->data['currentPage'];
    }

    /**
     * Return collector name
     *
     * @return string
     */
    public function getName()
    {
        return 'presta_cms_data_collector';
    }

    /**
     * @param boolean $cacheEnabled
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;
    }

    /**
     * @return boolean
     */
    public function getCache()
    {
        return $this->data['cache'];
    }
}
