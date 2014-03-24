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
use Presta\CMSCoreBundle\Form\Page\PageDescriptionType;
use Presta\CMSCoreBundle\Form\Page\SeoType;
use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\MenuManager;
use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\Website;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->get('security.context');
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
            'pageId'        => $request->get('id', null),
            '_locale'       => $request->get('_locale'),
            'page'          => null
        );

        $website = $this->getWebsiteManager()->getCurrentWebsite();

        if ($website != null) {
            $theme = $this->getThemeManager()->getTheme($website->getTheme());
            $viewParams['theme']   = $theme;
            $viewParams['website'] = $website;
        }

        return $viewParams;
    }

    /**
     * Return Page initialized for edition
     *
     * @param string $id
     *
     * @return Page
     */
    protected function getPage($id)
    {
        $websiteManager = $this->getWebsiteManager();

        $locale = $websiteManager->getCurrentWebsite()->getLocale();
        $page   = $this->getPageManager()->getPageById($id, $locale);

        if ($page == null) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->getPageManager()->setCurrentPage($page);

        //Initialize routing data
        $routeManager = $this->getRouteManager();
        $routeManager->setBaseUrl($websiteManager->getBaseUrlForLocale($locale));
        $routeManager->initializePageRouting($page);

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
        $id         = $request->get('id', null);
        $viewParams = $this->getEditViewParams();

        if ($id != null) {
            $page       = $this->getPage($id);
            $website    = $this->getWebsiteManager()->getCurrentWebsite();
            $templates  = $this->getThemeManager()->getTheme($website->getTheme())->getPageTemplates();

            $viewParams['page'] = $page;
            $viewParams['pageFrontUrl'] = $this->getFrontUrlPreviewForPage($page);
            $viewParams['pageEditTabs'] = $this->getPageManager()->getPageType($page->getType())->getEditTabs($page);
            $viewParams['formSeo']      = $this->createForm(new SeoType(), $page)->createView();
            $viewParams['formCache']    = $this->createForm(new CacheType(), $page)->createView();
            $viewParams['formSettings'] = $this->createForm(new SettingsType($templates), $page)->createView();
        }

        return $this->renderResponse('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
    }

    /**
     * Edit SEO Action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editSEOAction(Request $request)
    {
        return $this->handleFormTab($request, new SeoType());
    }

    /**
     * Edit Cache Action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editCacheAction(Request $request)
    {
        return $this->handleFormTab($request, new CacheType());
    }

    /**
     * Edit Settings Action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editSettingsAction(Request $request)
    {
        $website   = $this->getWebsiteManager()->getCurrentWebsite();
        $templates = $this->getThemeManager()->getTheme($website->getTheme())->getPageTemplates();

        return $this->handleFormTab($request, new SettingsType($templates));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editDescriptionAction(Request $request)
    {
        /** @var Pool $pool */
        $pool = $this->get('sonata.admin.pool');

        return $this->handleFormTab($request, new PageDescriptionType($pool));
    }

    /**
     * @param Request      $request
     * @param AbstractType $type
     *
     * @return Response
     */
    protected function handleFormTab(Request $request, AbstractType $type)
    {
        $page       = $this->getPage($request->get('id', null));
        $viewParams = array();

        if (null !== $page) {
            $form = $this->createForm($type, $page);
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
     * @param string $tab
     * @param Page   $page
     *
     * @return Response
     */
    public function renderEditTabAction($tab, Page $page)
    {
        $pageType   = $this->getPageManager()->getPageType($page->getType());
        /** @var Pool $pool */
        $pool       = $this->get('sonata.admin.pool');
        $viewParams = $pageType->getEditTabData($tab, $page, $pool);

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
        $website = $this->getWebsiteManager()->getCurrentWebsite();

        $root   = $website->getPageRoot();
        //$selected   = $request->query->get('selected') ?: $root;
        $locale = $website->getLocale();

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
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function clearCacheAction()
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_CACHE_CLEAR')) {
            throw new AccessDeniedException();
        }

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
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function deleteAction()
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_PAGE_DELETE')) {
            throw new AccessDeniedException();
        }

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
     * @param Request $request
     *
     * @throws AccessDeniedException
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ADMIN_CMS_PAGE_ADD')) {
            throw new AccessDeniedException();
        }
        /** @var Website $website */
        $website    = $this->getWebsiteManager()->getCurrentWebsite();
        $templates  = $this->getThemeManager()->getTheme($website->getTheme())->getPageTemplates();
        $parentId   = $request->get('parentId', null);

        $form = $this->createForm(new CreateType($parentId, $templates));
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $urlParams = $this->create(
                    $website,
                    $form->get('parentId')->getData(),
                    $form->get('title')->getData(),
                    $form->get('template')->getData()
                );
                $urlParams['_locale'] = $request->getLocale();

                $this->addFlash('sonata_flash_success', 'flash_edit_success');

                if ($this->isXmlHttpRequest()) {
                    $redirectUrl = $this->generateUrl('presta_cms_page_edit', $urlParams);

                    return $this->renderJson(
                        array('result' => 'ok', 'action' => 'refresh', 'location' => $redirectUrl)
                    );
                }
            } else {
                $this->addFlash('sonata_flash_error', 'flash_edit_error');
            }
        }

        return $this->renderResponse(
            'PrestaCMSCoreBundle:Admin/Page:add.html.twig',
            array('form' => $form->createView(), 'parentId' => $parentId)
        );
    }

    /**
     * Handle page, menu and routes creation
     *
     * @param Website $website
     * @param string  $parentId
     * @param string  $title
     * @param string  $template
     *
     * @return array
     */
    protected function create(Website $website, $parentId, $title, $template)
    {
        //Create Page
        $page = $this->get('presta_cms.page.factory')->create(
            $this->get('presta_cms.page.factory')->getConfiguration($website, $parentId, $title, $template)
        );

        //Create Routes
        foreach ($website->getAvailableLocales() as $locale) {
            $this->get('presta_cms.route.factory')->create(
                $this->get('presta_cms.route.factory')->getConfiguration($website, $page, $locale)
            );
        }

        $this->get('presta_cms.page.factory')->flush();

        return array('website' => $website->getId(), 'locale'  => $website->getLocale(), 'id' => $page->getId());
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
