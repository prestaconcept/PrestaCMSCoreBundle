<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Twig;

use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\Theme;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\Website;
use Presta\CMSCoreBundle\Model\WebsiteManager;

/**
 * Add necessary twig variables for front and admin
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class GlobalVariables
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var Website
     */
    protected $website;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Theme
     */
    protected $theme;

    public function __construct(WebsiteManager $websiteManager, ThemeManager $themeManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        $this->website  = $websiteManager->getCurrentWebsite();
        if ($this->website instanceof Website) {
            $this->theme    = $themeManager->getTheme($this->website->getTheme(), $this->website);
            $this->page     = $pageManager->getCurrentPage();
        }
    }

    /**
     * @return Theme
     */
    protected function getTheme()
    {
        return $this->theme;
    }

    /**
     * Return theme layout
     *
     * @return null|string
     */
    public function getLayout()
    {
        if (($this->getTheme() instanceof Theme) === false) {
            return null;
        }

        return $this->getTheme()->getTemplate();
    }

    /**
     * @return Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return WebsiteManager
     */
    public function getWebsiteManager()
    {
        return $this->websiteManager;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
