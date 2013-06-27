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
use Presta\CMSCoreBundle\Document\Page;

use Presta\CMSCoreBundle\Model\Page\PageTypeCMSPage;
use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\RouteManager;
use Presta\CMSCoreBundle\Model\ThemeManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;

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
     * @return ThemeManager
     */
    public function getThemeManager()
    {
        return $this->get('presta_cms.theme_manager');
    }

    /**
     * Return Page manager
     *
     * @return PageManager
     */
    public function getPageManager()
    {
        return $this->get('presta_cms.page_manager');
    }

    /**
     * Return Route manager
     *
     * @return RouteManager
     */
    public function getRouteManager()
    {
        return $this->get('presta_cms.route_manager');
    }

    /**
     * Return Menu manager
     *
     * @return MenuManager
     */
    public function getMenuManager()
    {
        return $this->get('presta_cms.menu_manager');
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
            $theme = $this->getThemeManager()->getTheme($website->getTheme());
            $viewParams['websiteId'] = $website->getId();
            $viewParams['locale']    = $website->getLocale();
            $viewParams['theme']     = $theme;
        }

        if ($menuItemId != null) {
            $pageManager    = $this->getPageManager();
            $routeManager   = $this->getRouteManager();

            $page = $pageManager->getPageForMenu($menuItemId, $locale);

            //Initialize routing data
            $routeManager->setBaseUrl($this->getWebsiteManager()->getBaseUrlForLocale($locale));
            $correspondingRoute = $routeManager->getRouteForPage($page, $locale);

            //base route case
            if ($correspondingRoute->getPrefix() == $correspondingRoute->getId()) {
                $page->setUrlRelative('');
                $page->setPathComplete('');
                $page->setUrlComplete('');
            } else {
                $page->setUrlRelative($correspondingRoute->getName());

                if ($page->isUrlCompleteMode()) {
                    $pageParentRoute = $routeManager->getRouteForPage($page->getParent(), $locale);
                    $page->setPathComplete(str_replace(
                        $pageParentRoute->getPrefix(),
                        '',
                        $pageParentRoute->getId() . '/'
                    ));
                } else {
                    $page->setPathComplete(str_replace(
                        $correspondingRoute->getPrefix(),
                        '',
                        $correspondingRoute->getParent()->getId() . '/'
                    ));
                }
                $page->setUrlComplete(str_replace(
                    $correspondingRoute->getPrefix(),
                    '',
                    $correspondingRoute->getId()
                ));
            }

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
            $viewParams['page'] = $page;
            $viewParams['pageFrontUrl'] = $this->getFrontUrlPreviewForPage($page, $locale);
            $viewParams['pageEditTabs'] = $pageManager->getPageType($page->getType())->getEditTabs();
        }

        return $this->render('PrestaCMSCoreBundle:Admin/Page:index.html.twig', $viewParams);
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
    public function renderEditTabAction($tab, $page)
    {
        $pageType   = $this->getPageManager()->getPageType($page->getType());
        $viewParams = $pageType->getEditTabData($tab, $page);

        return $this->render($pageType->getEditTabTemplate($tab), $viewParams);
    }

    /**
     * Render page routing information like redirect
     * 
     * @param  Page   $page
     * @return Response
     */
    public function renderRoutingAction(Page $page)
    {
        // Get all redirect route name
        $redirectRouteNames = array();
        foreach($this->getRouteManager()->getRedirectRouteForPage($page) as $redirectRoute) {
            $redirectRouteNames[] = $redirectRoute->getName();
        }

        $viewParams = array(
            'redirectRouteNames' => $redirectRouteNames,
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Page:routing.html.twig', $viewParams);
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

    /**
     * Return page front url
     * 
     * @param  Page     $page
     * @param  string   $locale
     * @param  boolean  $absolute
     * @return string
     */
    public function getFrontUrlForPage(Page $page, $locale = null, $absolute = false)
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
     * @param  Page     $page
     * @param  string   $locale
     * @return string
     */
    public function getFrontUrlPreviewForPage(Page $page, $locale)
    {
        $url = $this->getFrontUrlForPage($page, $locale, true);

        return $url . '?token=' . $this->getPageManager()->getToken($page);
    }

    public function addAction(Request $request)
    {
        $website = $this->getWebsiteManager()->getCurrentWebsite();
        $locale  = $website->getLocale();
        $root    = $request->get('root');

        $page = new Page();
//        $page->setName($pageConfiguration['name']);
//        $page->setParent($root);

        $pageTemplates = array_keys($this->getThemeManager()->getPageTemplates($website->getTheme()));
        $pageTemplateChoices = array_combine($pageTemplates, $pageTemplates);

        $formBuilder = $this->createFormBuilder($page);


        if ($root == null) {
            $formBuilder->add(
                'root',
                'choice',
                array(
                    'mapped' => false,
                    'label' => $this->trans('cms_page.form.page.label.root'),
                    'choices' => $this->getMenuManager()->getNavigationRootsForWebsite($website),
                    'required' => true
                )
            );
        }


        $formBuilder
            ->add('name', null, array('label' => $this->trans('cms_page.form.page.label.name'), 'required' => true))
            ->add('menuTitle', null, array('label' => $this->trans('cms_page.form.menu.label.title'), 'mapped' => false, 'required' => true))
            ->add(
                'template',
                'choice',
                array(
                    'label' => $this->trans('cms_page.form.page.label.template'),
                    'choices' => $pageTemplateChoices,
                    'required' => true
                )
            )

//            ->add(
//                'menuParent',
//                'doctrine_phpcr_odm_tree',
//                array(
//                    'label' => 'cms_page.form.menu.label.parent',
//                    'required' => false,
//                    'choice_list' => array(),
//                    'model_manager' => $this->get('sonata.admin.manager.doctrine_phpcr'),
//                    'root_node'=> '/website/prestaconcept/menu/main',
//                    'class' => 'Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuNode'
//                )
//            )
            ;

        $form = $formBuilder->getForm();

        if ($this->get('request')->getMethod() == 'POST') {
            $menuTitle = $form->get('menuTitle')->getData();
            $page->setTitle($menuTitle);
            $page->setLocale($locale);
            $page->setIsActive(true);
            $page->setType(PageTypeCMSPage::SERVICE_ID);
            $page->setLastCacheModifiedDate(new \DateTime());

            $form->bind($this->get('request'));

            $viewParams = array(
                'locale'  => $locale,
                '_locale' => $request->getLocale(),
                'website' => $website->getId()
            );



            //load page parent
            if ($root == null) {
                $root = $form->get('root')->getData();
            }
            $parentMenu = $this->getPageManager()->getDocumentManager()->find(null, $root);

            if ($parentMenu->getContent() == null) {
                $page->setParent($this->getPageManager()->getDocumentManager()->find(null, $website->getPageRoot()));
            } else {
                $page->setParent($parentMenu->getContent());
            }

            if ($form->isValid() || true) {
                //NBN : Here form validation does not work due to cmf constraints
                //(?) bug in symfony cascade validation ?

                //Create page
                $this->getPageManager()->create($page);

                //Create associated menu entry
                $menuNode = $this->getMenuManager()->create($parentMenu, $page->getName(), $menuTitle, $page);

                //id for redirection
                $viewParams['id'] = $menuNode->getId();

                $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
            } else {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');

                var_dump(
                    $form->getErrorsAsString()
//                    $form->getViewData()
                );die;
            }

            if ($this->isXmlHttpRequest()) {
                return $this->renderJson(
                    array(
                        'result'    => 'ok',
                        'action'    => 'refresh',
                        'location'  => $this->generateUrl('presta_cms_page_edit', $viewParams),
                    )
                );
            }

        }
        $viewParams = array(
            'form' => $form->createView()
        );

        return $this->render('PrestaCMSCoreBundle:Admin/Page:add.html.twig', $viewParams);
    }
}
