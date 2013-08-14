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
        return $this->get('presta_cms.manager.website');
    }

    /**
     * Return Theme manager
     *
     * @return Presta\CMSCoreBundle\Model\ThemeManager
     */
    public function getThemeManager()
    {
        return $this->get('presta_cms.manager.theme');
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
    public function editAction($name)
    {
        $website = $this->getWebsiteManager()->getCurrentWebsite();
        $viewParams = array(
            'website' => $website,
            'websiteId' => ($website) ? $website->getId() : null,
            'locale'  => ($website) ? $website->getLocale() : null,
            'theme'   => $this->getThemeManager()->getTheme($name, $website)
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Theme:edit.html.twig', $viewParams);
    }
}
