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
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Theme administration controller
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ThemeController extends AdminController
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
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->get('security.context');
    }

    /**
     * Theme listing
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->renderResponse(
            'PrestaCMSCoreBundle:Admin/Theme:list.html.twig',
            array('themes' => $this->getThemeManager()->getAvailableThemes())
        );
    }

    /**
     * Theme administration
     *
     * @param string $name
     *
     * @throws AccessDeniedException
     *
     * @return Response
     */
    public function editAction($name)
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_THEME_EDIT')) {
            throw new AccessDeniedException();
        }

        $website    = $this->getWebsiteManager()->getCurrentWebsite();
        $viewParams = array(
            'website'   => $website,
            'websiteId' => ($website) ? $website->getId() : null,
            'locale'    => ($website) ? $website->getLocale() : null,
            'theme'     => $this->getThemeManager()->getTheme($name, $website)
        );

        return $this->renderResponse('PrestaCMSCoreBundle:Admin/Theme:edit.html.twig', $viewParams);
    }
}
