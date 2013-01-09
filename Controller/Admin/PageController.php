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

use Presta\SonataAdminBundle\Controller\Admin\BaseController as AdminController;
use Presta\CMSCoreBundle\Form\PageType;

use Application\Presta\CMSCoreBundle\Entity\Page;

/**
 * Page administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageController extends AdminController
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
     * Return Page manager
     * 
     * @return Presta\CMSCoreBundle\Model\PageManager
     */
    public function getPageManager()
    {
        return $this->get('presta_cms.page_manager');
    }
    
    /**
     * Page administration main screen 
     */
    public function indexAction($websiteId, $locale)
    {
        $viewParams = array(
            'menuItemId' => null,  'websiteId' => $websiteId, 'locale' => $locale, 'navigations' => array(), 'page' => null, '_locale' => $this->getRequest()->get('_locale')
        );

        if ($websiteId != null) {
            $websiteId = '/website/' . $websiteId; //le slash ne passant pas au routing on rajoute le basePath
            $viewParams['websiteId'] = $websiteId;
            $website = $this->getWebsiteManager()->getWebsite($websiteId, $locale);
            $theme = $this->getThemeManager()->getTheme($website->getTheme());

            $navigations = array();
            foreach ($theme->getNavigations() as $navigation) {
                $navigations[$navigation] = $navigation;
            }
//            $navigations['single_pages'] = 'single_pages';
            $viewParams['theme'] = $theme;
            $viewParams['navigations'] = $navigations;


//            if ($id) {
//                $page = $this->getPageManager()->getPageById($website, $id);
//
//                $viewParams['pageEditTabs'] = $this->getPageManager()->getType($page->getType())->getEditTabs();
//
//                $form = $this->createForm(new PageType(), $page);
//                if ($this->get('request')->getMethod() == 'POST') {
//                    $form->bindRequest($this->get('request'));
//
//                    if ($form->isValid()) {
//                        $this->getPageManager()->update($page);
//                        $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
//                    } else {
//                        $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
//                    }
//                }
//
//                $viewParams['page'] = $page;
//                $viewParams['form'] = $form->createView();
//            }
        }
        
        return $this->render('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
    }

    public function editAction()
    {
        $menuItemId = $this->getRequest()->get('id');
        $locale = $this->getRequest()->get('locale');

        //R?cup?ration de la page

        $page = $this->getPageManager()->getPageForMenu($menuItemId, $locale);


        $website = $page->getParent()->getParent(); //todo better!
        $websiteId = $website->getId();
        $this->getWebsiteManager()->setCurrentWebsite($website);

        $theme = $this->getThemeManager()->getTheme($website->getTheme());

        $navigations = array();
        foreach ($theme->getNavigations() as $navigation) {
            $navigations[$navigation] = $navigation;
        }
//            $navigations['single_pages'] = 'single_pages';

        $viewParams = array(
            'menuItemId' => $menuItemId,  'websiteId' => $websiteId,
            'locale' => $locale, 'navigations' => array(),
            'page' => $page, '_locale' => $this->getRequest()->get('_locale'),
            'translation_domain' => 'PrestaCMSCoreBundle'
        );
        $viewParams['theme'] = $theme;
        $viewParams['navigations'] = $navigations;

        $viewParams['pageEditTabs'] = $this->getPageManager()->getType($page->getType())->getEditTabs();

        $form = $this->createForm(new PageType(), $page);
        if ($this->get('request')->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->getPageManager()->update($page);
                $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
            } else {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
            }
        }

        $viewParams['form'] = $form->createView();

        return $this->render('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
    }

    
    /**
     * Return a specific page edit tab
     *
     * Action rendered in main edit template
     *
     * @param  string $type
     * @param  string $tab
     * @param  Page $page
     * @return Response
     */
    public function renderEditTabAction($type, $tab, $page)
    {
        $pageType   = $this->getPageManager()->getType($type);
        $viewParams = $pageType->getEditTabData($tab, $page);
        return $this->render($pageType->getEditTabTemplate($tab), $viewParams);
    }

//	/**
//	 * Display page selector for tyniMCE link
//	 *
//	 * @param $website_id
//	 * @param $locale
//	 * @return Response
//	 */
//	public function selectorAction($website_id, $locale)
//	{
//		$viewParams = array('website_id' => $website_id, 'locale' => $locale);
//
//		//todo refactor chargement des pages
//		$website = $this->getWebsiteManager()->getWebsite($website_id, $locale);
//		if ($website != null) {
//			$theme = $this->getThemeManager()->getTheme($website->getTheme());
//
//			$navigations = array();
//			foreach ($theme->getNavigations() as $navigation) {
//				$navigations[$navigation] = $this->getPageManager()->getNavigationTree($website, $navigation);
//			}
//			$navigations['single_pages'] = $this->getPageManager()->getSinglePagesTree($website);
//			$viewParams['theme'] = $theme;
//			$viewParams['navigations'] = $navigations;
//		}
//		return $this->render('PrestaCMSCoreBundle:Admin/Page:selector.html.twig', $viewParams);
//	}
}