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
use Presta\CMSCoreBundle\Form\Page\CacheType;
use Presta\CMSCoreBundle\Form\Page\SettingsType;
use Presta\CMSCoreBundle\Form\Page\CreateType;
use Presta\CMSCoreBundle\Form\Page\SeoType;
use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\MenuManager;
use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\Website;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Page administration controller
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageController extends AdminController
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
     * @return PageManager
     */
    protected function getPageManager()
    {
        return $this->get('presta_cms.manager.page');
    }

    /**
     * @return RouteManager
     */
    protected function getRouteManager()
    {
        return $this->get('presta_cms.manager.route');
    }

    /**
     * @return MenuManager
     */
    protected function getMenuManager()
    {
        return $this->get('presta_cms.manager.menu');
    }

    /**
     * Return default view params for edition page
     *
     * @return array
     */
    protected function getEditViewParams()
    {
        $request = $this->getRequest();
        $viewParams = array(
            'websiteId'     => null,
            'menuItemId'    => $request->get('id', null),
            'locale'        => $request->get('locale', null),
            '_locale'       => $request->get('_locale'),
            'page'          => null
        );

        $website = $this->getWebsiteManager()->getCurrentWebsite();

        if ($website != null) {
            $theme = $this->getThemeManager()->getTheme($website->getTheme());
            $viewParams['websiteId'] = $website->getId();
            $viewParams['locale']    = $website->getLocale();
            $viewParams['theme']     = $theme;
        }

        return $viewParams;
    }

    /**
     * Add view parameters for page edition
     *
     * @param  array    $viewParams
     * @param  Page     $page
     *
     * @return array
     */
    protected function addPageEditionViewParams(array $viewParams, Page $page)
    {
        $viewParams['page'] = $page;
        $viewParams['pageFrontUrl'] = $this->getFrontUrlPreviewForPage($page);
        $viewParams['pageEditTabs'] = $this->getPageManager()->getPageType($page->getType())->getEditTabs();

        return $viewParams;
    }

    /**
     * Return Page initialized for edition
     *
     * @param  integer $menuNodeId
     * @return Page
     */
    protected function getPage($menuNodeId)
    {
        if ($menuNodeId == null) {
            return null;
        }

        $locale         = $this->getRequest()->get('locale', null);
        $pageManager    = $this->getPageManager();
        $routeManager   = $this->getRouteManager();
        $menuManager    = $this->getMenuManager();

        $page = $pageManager->getPageForMenu($menuNodeId, $locale);

        //Initialize routing data
        $routeManager->setBaseUrl($this->getWebsiteManager()->getBaseUrlForLocale($locale));
        $routeManager->initializePageRouting($page);

        // initialize the menu data
        $menuManager->initializePageMenu($page, $menuNodeId);

        return $page;
    }

    /**
     * Page Edition
     *
     * @param  Request  $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $page       = $this->getPage($request->get('id', null));
        $viewParams = $this->getEditViewParams();

        if ($page != null) {
            $formSeo = $this->createForm(new SeoType(), $page);
            $formCache = $this->createForm(new CacheType(), $page);
            $formSettings = $this->createForm(new SettingsType(), $page);

            $viewParams = $this->addPageEditionViewParams($viewParams, $page);
            $viewParams['formSeo'] = $formSeo->createView();
            $viewParams['formCache'] = $formCache->createView();
            $viewParams['formSettings'] = $formSettings->createView();
        }

        return $this->renderResponse('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
    }

    /**
     * Edit SEO Action
     *
     * @param  Request  $request
     * @return Response
     */
    public function editSEOAction(Request $request)
    {
        $page       = $this->getPage($request->get('id', null));
        $viewParams = array();

        if ($page != null) {
            $form = $this->createForm(new SeoType(), $page);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getPageManager()->update($page);
                $viewParams['success'] = 'flash_edit_success';
            } else {
                $viewParams['error'] = 'flash_edit_error';
            }
        } else {
            $viewParams['error'] = 'flash_edit_error';
        }

        return $this->renderJson($viewParams);
    }

    /**
     * Edit Cache Action
     *
     * @param  Request  $request
     * @return Response
     */
    public function editCacheAction(Request $request)
    {
        $page       = $this->getPage($request->get('id', null));
        $viewParams = array();

        if ($page != null) {
            $form = $this->createForm(new CacheType(), $page);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getPageManager()->update($page);
                $viewParams['success'] = 'flash_edit_success';
            } else {
                $viewParams['error'] = 'flash_edit_error';
            }
        } else {
            $viewParams['error'] = 'flash_edit_error';
        }

        return $this->renderJson($viewParams);
    }

    /**
     * Edit Settings Action
     *
     * @param  Request  $request
     * @return Response
     */
    public function editSettingsAction(Request $request)
    {
        $page       = $this->getPage($request->get('id', null));
        $viewParams = array();

        if ($page != null) {
            $form = $this->createForm(new SettingsType(), $page);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getPageManager()->update($page);
                $viewParams['success'] = 'flash_edit_success';
            } else {
                $viewParams['error'] = 'flash_edit_error';
            }
        } else {
            $viewParams['error'] = 'flash_edit_error';
        }

        return $this->renderJson($viewParams);
    }

    /**
     * Return a specific page edit tab
     *
     * Action rendered in main edit template
     *
     * @param  string   $tab
     * @param  Page     $page
     * @return Response
     */
    public function renderEditTabAction($tab, Page $page)
    {
        $pageType   = $this->getPageManager()->getPageType($page->getType());
        $viewParams = $pageType->getEditTabData($tab, $page);

        return $this->renderResponse($pageType->getEditTabTemplate($tab), $viewParams);
    }

    /**
     * Render page routing information like redirect
     *
     * @param  Page     $page
     * @return Response
     */
    public function renderRoutingAction(Page $page)
    {
        // Get all redirect route name
        $redirectRouteNames = array();
        foreach ($this->getRouteManager()->getRedirectRouteForPage($page) as $redirectRoute) {
            $redirectRouteNames[] = $redirectRoute->getName();
        }

        $viewParams = array(
            'redirectRouteNames' => $redirectRouteNames,
        );

        return $this->renderResponse('PrestaCMSCoreBundle:Admin/Page:routing.html.twig', $viewParams);
    }

    /**
     * Allow us to render the tre in the website locale
     *
     * @param  Request  $request
     * @return Response
     */
    public function renderPageTreeAction(Request $request)
    {
        $root       = $request->query->get('root');
        //$selected   = $request->query->get('selected') ?: $root;
        $locale     = $request->query->get('locale');

        //$selected is set to null cause it trigger the "select_node.jstree" event and reload the page
        $selected   = null;

        return $this->forward(
            'sonata.admin.doctrine_phpcr.tree_controller:treeAction',
            array('root' => $root, 'selected' => $selected, '_locale' => $locale)
        );
    }

    /**
     * Clear page cache
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function clearCacheAction()
    {
        $pageId = $this->getRequest()->get('id', null);
        $page   = $this->getPageManager()->getPageById($pageId);

        if ($page == null) {
            throw new NotFoundHttpException(sprintf("Unable to find the page with id %s", $pageId));
        }

        try {
            $this->getPageManager()->clearCache($page);
            $this->addFlash('sonata_flash_success', 'flash_edit_success');
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_edit_error');
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Delete a page
     *
     * @return Response
     */
    public function deleteAction()
    {
        $page = $this->getPageManager()->getPageById($this->getRequest()->get('id', null));
        if ($page == null) {
            throw new NotFoundHttpException();
        }

        if ($this->getRequest()->getMethod() == 'DELETE') {
            try {
                $this->getPageManager()->delete($page);
                $this->addFlash('sonata_flash_success', 'flash_delete_success');
            } catch (ModelManagerException $e) {
                $this->addFlash('sonata_flash_error', 'flash_delete_error');
            }

            return $this->redirect($this->generateUrl('presta_cms_page_edit'));
        }

        return $this->renderResponse('PrestaCMSCoreBundle:Admin/Page:delete.html.twig', array('page' => $page));
    }

    /**
     * Return page front url
     *
     * @param  Page    $page
     * @param  string  $locale
     * @param  boolean $absolute
     * @return string
     */
    protected function getFrontUrlForPage(Page $page, $locale = null, $absolute = false)
    {
        if (is_null($locale)) {
            $locale = $this->getWebsiteManager()->getCurrentWebsite()->getLocale();
        }

        if ($absolute) {
            $baseUrl = $this->getWebsiteManager()->getBaseUrlForLocale($locale);
        }

        $route      = $this->getRouteManager()->getRouteForPage($page, $locale);
        $pageUrl    = str_replace(
            $route->getPrefix(),
            '',
            $route->getId()
        );

        return $baseUrl . $pageUrl;
    }

    /**
     * Return page front preview url (page front url with token)
     *
     * @param  Page   $page
     * @return string
     */
    protected function getFrontUrlPreviewForPage(Page $page)
    {
        $url = $this->getFrontUrlForPage($page, $page->getLocale(), true);

        return $url . '?token=' . $this->getPageManager()->getToken($page);
    }

    /**
     * tte a new page
     *
     * @param  Request  $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $rootId     = $request->get('rootId', null);
        $website    = $this->getWebsiteManager()->getCurrentWebsite();
        $menus      = $this->getMenuManager()->getWebsiteMenus($website);
        $templates  = $this->getThemeManager()->getTheme($website->getTheme())->getPageTemplates();

        $form = $this->createForm(new CreateType($rootId, $menus, $templates));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $urlParams = $this->create(
                $website,
                $form->get('root')->getData(),
                $form->get('title')->getData(),
                $form->get('template')->getData()
            );
            $urlParams['_locale'] = $request->getLocale();

            $this->addFlash('sonata_flash_success', 'flash_edit_success');

            if ($this->isXmlHttpRequest()) {
                $redirectUrl = $this->generateUrl('presta_cms_page_edit', $urlParams);

                return $this->renderJson(array('result' => 'ok', 'action' => 'refresh', 'location' => $redirectUrl));
            }
        } else {
            $this->addFlash('sonata_flash_error', 'flash_edit_error');
        }

        return $this->renderResponse(
            'PrestaCMSCoreBundle:Admin/Page:add.html.twig',
            array('form' => $form->createView(), 'rootId' => $rootId)
        );
    }

    /**
     * Handle page, menu and routes creation
     *
     * @param Website   $website
     * @param string    $root
     * @param string    $title
     * @param string    $template
     *
     * @return array
     */
    protected function create(Website $website, $root, $title, $template)
    {
        //Create Page
        $page = $this->get('presta_cms.page.factory')->create(
            $this->get('presta_cms.page.factory')->getConfiguration($website, $root, $title, $template)
        );

        //Create Routes
        foreach ($website->getAvailableLocales() as $locale) {
            $this->get('presta_cms.route.factory')->create(
                $this->get('presta_cms.route.factory')->getConfiguration($website, $page, $locale)
            );
        }

        //Create MenuNode
        $menuNode = $this->get('presta_cms.menu.factory')->create(
            $this->get('presta_cms.menu.factory')->getConfiguration($page, $root)
        );

        $this->get('presta_cms.page.factory')->flush();

        return array('website' => $website->getId(), 'locale'  => $website->getLocale(), 'id' => $menuNode->getId());
    }

    /**
     * Returns internal pages for WYSIWYG integration
     *
     * @return Response
     */
    public function wysiwygPagesAction()
    {
        $pages = $this->getPageManager()->getPagesForWebsite($this->getWebsiteManager()->getCurrentWebsite());

        $jsCode = 'var WYSIWYG_INTERNAL_PAGES = [';
        foreach ($pages as $page) {
            $jsCode .= '["' . $page->getTitle() . '", "##internal#' . $page->getId() . '#"],';
        }
        $jsCode .= '];';

        return new Response($jsCode, 200, array('Content-Type' => 'application/javascript'));
    }
}
