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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $menuItemId = $this->getRequest()->get('id', null);
        $locale = $this->getRequest()->get('locale');

        $viewParams = array(
            'websiteId'  => null,
            'menuItemId' => $menuItemId,
            'locale'  => $locale,
            '_locale' => $this->getRequest()->get('_locale'),
            'page'  => null
        );

        $website = $this->getWebsiteManager()->getCurrentWebsite();

        if ($website != null) {
            $viewParams['websiteId'] = $website->getId();
            $viewParams['locale'] = $website->getLocale();
            $theme = $this->getThemeManager()->getTheme($website->getTheme());
            $viewParams['theme'] = $theme;
        }

        if ($menuItemId != null) {
            $page = $this->getPageManager()->getPageForMenu($menuItemId, $locale);
            $viewParams['page'] = $page;
            $viewParams['pageEditTabs'] = $this->getPageManager()->getType($page->getType())->getEditTabs();

            $form = $this->createForm(new PageType(), $page);
            if ($this->get('request')->getMethod() == 'POST') {
                $form->bind($this->get('request'));

                if ($form->isValid()) {
                    $this->getPageManager()->update($page);
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
        $root = $request->query->get('root');
        $selected = $request->query->get('selected') ?: $root;
        $locale = $request->query->get('locale');

        //$selected is set to null cause it trigger the "select_node.jstree" event and reload the page
        $selected = null;

        return $this->forward('sonata.admin.doctrine_phpcr.tree_controller:treeAction', array(), array('root' => $root, 'selected' => $selected, '_locale' => $locale));
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
