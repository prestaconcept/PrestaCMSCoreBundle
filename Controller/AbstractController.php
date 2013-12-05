<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Sonata\SeoBundle\Seo\SeoPageInterface;

/**
 * This base controller handle the loading of all the data needed by PrestaCMS layout
 *
 * You should extend it if you want to add a custom controller to your PrestaCMS application
 * A good example can be found in PrestaCMSUserBundle
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractController extends Controller
{
    /**
     * @return WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->get('presta_cms.manager.website');
    }

    /**
     * @return ThemeManager
     */
    protected function getThemeManager()
    {
        return $this->get('presta_cms.manager.theme');
    }

    /**
     * @return SeoPageInterface
     */
    protected function getSeoManager()
    {
        return $this->get('sonata.seo.page');
    }

    /**
     * Returns all the necessary view params needed to render the theme layout
     *
     * @return array
     */
    protected function getBaseViewParams()
    {
        $website    = $this->getWebsiteManager()->getCurrentWebsite();
        $theme      = $this->getThemeManager()->getTheme($website->getTheme(), $website);

        return array(
            'base_template'     => $theme->getTemplate(),
            'website'           => $website,
            'websiteManager'    => $this->getWebsiteManager(),
            'theme'             => $theme
        );
    }
}
