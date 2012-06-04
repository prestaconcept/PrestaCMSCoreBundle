<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Controller\Admin;

use PrestaSonata\AdminBundle\Controller\Admin\Controller as AdminController;

/**
 * Theme administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class ThemeController extends AdminController
{    
    /**
     * Return Website manager
     * 
     * @return PrestaCMS\CoreBundle\Model\WebsiteManager 
     */
    public function getWebsiteManager()
    {
        return $this->get('presta_cms.website_manager');
    }
    
    /**
     * Return Theme manager
     * 
     * @return PrestaCMS\CoreBundle\Model\ThemeManager 
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
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:list.html.twig', array(
            'themes' => $this->getThemeManager()->getAvailableThemes()
        ));
    }
    
    /**
     * Theme administration
     * 
     * @return Response 
     */
    public function editAction($name, $website_id, $locale)
    {
        $website = $this->getWebsiteManager()->getWebsite($website_id, $locale);
        $theme = $this->getThemeManager()->getTheme($name, $website);
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:edit.html.twig', array(
            'website' => $website,
            'theme' => $theme
        ));
    }
}