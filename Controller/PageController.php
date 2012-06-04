<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function catchAllAction()
    {
        $website = $this->getWebsiteManager()->getWebsiteForRequest($this->getRequest());        
        $theme = $this->getThemeManager()->getTheme($website->getTheme(), $website);
        
        return $this->render('PrestaCMSCoreBundle:Page:index.html.twig', array(
            'base_template' => $theme->getTemplate(),
            'website' => $website,
            'theme' => $theme
        ));
    }
}