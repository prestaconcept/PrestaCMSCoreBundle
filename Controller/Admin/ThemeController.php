<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
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
     * @return type 
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
     * @return type 
     */
    public function editAction($name, $website_id, $locale)
    {
        $website = $this->getDoctrine()->getEntityManager()->getRepository('Application\PrestaCMS\CoreBundle\Entity\Website')->find(1);
        $website->setLocale('fr');
        $theme = $this->getThemeManager()->getTheme($name, $website);
        
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:edit.html.twig', array(
            'theme' => $theme
        ));
    }
    
    /**
     * Ajax block edition
     * 
     * @param type $websiteId
     * @param type $locale
     * @param type $blockId 
     */
    public function editBlockAction($websiteId, $locale, $blockId)
    {
        return $this->forward('PrestaCMSCoreBundle:Admin/ThemeBlock:edit', array(
            'id'  => $blockId,
            '_sonata_admin' => 'presta_cms.theme.admin.bloc'
        ));
//        $block = $blockId;
//        return $this->_render('PrestaCMSCoreBundle:Admin/Theme:edit_block.html.twig', array(
//            'block' => $block
//        ));
    }
}