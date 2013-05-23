<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Alain Flaus <aflaus@prestaconcept.net>
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

/**
 * CMS data collector for the symfony web profiling
 */
class CMSDataCollector extends DataCollector
{
    /**
     * @var Presta\CMSCoreBundle\Model\WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var Presta\CMSCoreBundle\Model\ThemeManager
     */
    protected $themeManager;

    /**
     * @var Presta\CMSCoreBundle\Model\PageManager
     */
    protected $pageManager;

    /**
     * Constructor
     *
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

        $this->data['currentWebsite'] = array(
            'name'      => $currentWebsite->getName(),
            'path'      => $currentWebsite->getPath(),
            'locale'    => $currentWebsite->getLocale(),
        );
    }

    /**
     * Add theme datas to collect
     */
    protected function collectThemeData()
    {
        $currentTheme = $this->themeManager->getCurrentTheme();

        $this->data['currentTheme'] = array(
            'name'  => $currentTheme->getName(),
        );
    }

    /**
     * Add page datas to collect
     */
    protected function collectPageData()
    {
        $currentPage = $this->pageManager->getCurrentPage();

        $this->data['currentPage'] = array(
            'name'  => $currentPage->getName(),
            'url'   => $currentPage->getUrl(),
            'type'  => $currentPage->getType(),
        );
    }

    /**
     * Get website collected data
     *
     * @return array
     */
    public function getCurrentWebsite()
    {
        return $this->data['currentWebsite'];
    }

    /**
     * Get theme collected data
     *
     * @return array
     */
    public function getCurrentTheme()
    {
        return $this->data['currentTheme'];
    }

    /**
     * Get page collected data
     *
     * @return array
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
}