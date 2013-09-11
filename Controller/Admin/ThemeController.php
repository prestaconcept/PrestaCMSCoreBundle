<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller\Admin;

use Presta\CMSCoreBundle\Controller\Admin\BaseController as AdminController;

/**
 * Theme administration controller
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeController extends AdminController
{
    /**
     * @return Presta\CMSCoreBundle\Model\WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->get('presta_cms.manager.website');
    }

    /**
     * @return Presta\CMSCoreBundle\Model\ThemeManager
     */
    protected function getThemeManager()
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
