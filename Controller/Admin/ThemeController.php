<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller\Admin;

use Presta\CMSCoreBundle\Controller\Admin\BaseController as AdminController;

/**
 * Theme administration controller
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeController extends AdminController
{
    /**
     * Return Website manager
     *
     * @return Presta\CMSCoreBundle\Model\WebsiteManager
     */
    public function getWebsiteManager()
    {
        return $this->get('presta_cms.website_manager');
    }

    /**
     * Return Theme manager
     *
     * @return Presta\CMSCoreBundle\Model\ThemeManager
     */
    public function getThemeManager()
    {
        return $this->get('presta_cms.theme_manager');
    }

    /**
     * Theme listing
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:list.html.twig', array('themes' => $this->getThemeManager()->getAvailableThemes()));
    }

    /**
     * Theme administration
     *
     * @return Response
     */
    public function editAction($name, $websiteId = null, $locale = null)
    {
        $website = null;
        if ($websiteId != null) {
            $websiteId = '/website/' . $websiteId; //le slash ne passant pas au routing on rajoute le basePath
            $website = $this->getWebsiteManager()->getWebsite(array('path' => $websiteId, 'locale' => $locale));
        }
        $viewParams = array(
            'websiteId' => $websiteId,
            'locale'    => $locale,
            'website'   => $website,
            'theme'     => $this->getThemeManager()->getTheme($name, $website)
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Theme:edit.html.twig', $viewParams);
    }
}
