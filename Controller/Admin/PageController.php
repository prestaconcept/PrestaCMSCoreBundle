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
use Presta\CMSCoreBundle\Form\PageType;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Page Edition
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $request    = $this->getRequest();
        $menuItemId = $request->get('id', null);
        $locale     = $request->get('locale', null);

        $viewParams = array(
            'websiteId'     => null,
            'menuItemId'    => $menuItemId,
            'locale'        => $locale,
            '_locale'       => $request->get('_locale'),
            'page'          => null
        );

        $website = $this->getWebsiteManager()->getCurrentWebsite();

        if ($website != null) {
            $viewParams['websiteId'] = $website->getId();
            $viewParams['locale'] = $website->getLocale();
            $theme = $this->getThemeManager()->getTheme($website->getTheme());
            $viewParams['theme'] = $theme;
        }

        if ($menuItemId != null) {
            $pageManager = $this->getPageManager();
            $page = $pageManager->getPageForMenu($menuItemId, $locale);
            $viewParams['page'] = $page;
            $viewParams['pageFrontUrl'] = $request->getScheme() . '://' . $this->getWebsiteManager()->getHostForWebsite($website, $locale, $this->get('kernel')->getEnvironment()) . $pageManager->getPageUrl($page);
            $viewParams['pageFrontUrl'] .= '?token=' . $pageManager->getToken($page);
            $viewParams['pageEditTabs'] = $pageManager->getType($page->getType())->getEditTabs();

            $form = $this->createForm(new PageType(), $page);
            if ($this->get('request')->getMethod() == 'POST') {
                $form->bind($this->get('request'));

                if ($form->isValid()) {
                    $pageManager->update($page);
                    $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
                } else {
                    $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
                }
            }
            $viewParams['form'] = $form->createView();
        }

        return $this->render('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
    }

    /**
     * Return a specific page edit tab
     *
     * Action rendered in main edit template
     *
     * @param  string   $type
     * @param  string   $tab
     * @param  Page     $page
     * @return Response
     */
    public function renderEditTabAction($type, $tab, $page)
    {
        $pageType   = $this->getPageManager()->getType($type);
        $viewParams = $pageType->getEditTabData($tab, $page);

        return $this->render($pageType->getEditTabTemplate($tab), $viewParams);
    }

    /**
     * Allow us to render the tre in the website locale
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderPageTreeAction (Request $request)
    {
        $root       = $request->query->get('root');
        $selected   = $request->query->get('selected') ?: $root;
        $locale     = $request->query->get('locale');

        //$selected is set to null cause it trigger the "select_node.jstree" event and reload the page
        $selected   = null;

        return $this->forward('sonata.admin.doctrine_phpcr.tree_controller:treeAction', array('_locale' => $locale), array('root' => $root, 'selected' => $selected, '_locale' => $locale));
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

    /**
     * Clear page cache
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clearCacheAction()
    {
        $pageId = $this->getRequest()->get('id', null);
        $page   = $this->getPageManager()->getPageById($pageId);

        if ($page == null) {
            throw new NotFoundHttpException();
        }

        //To clear the front cache, we just need to update the LastCacheModifiedDate of the page
        //Front cache validation noticed that cache should be recomputed
        $page->setLastCacheModifiedDate(new \DateTime());
        $this->getPageManager()->update($page);

        $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');


        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Delete a page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction()
    {
        $pageId = $this->getRequest()->get('id', null);
        $page   = $this->getPageManager()->getPageById($pageId);

        if ($page == null) {
            throw new NotFoundHttpException();
        }

        if ($this->getRequest()->getMethod() == 'DELETE') {
            try {
                $this->getPageManager()->delete($page);
                $this->get('session')->setFlash('sonata_flash_success', 'flash_delete_success');
            } catch (ModelManagerException $e) {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_delete_error');
            }

            return $this->redirect($this->generateUrl('presta_cms_page_edit'));
        }

        $viewParams = array(
            'page' => $page
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Page:delete.html.twig', $viewParams);
    }
}
