<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller;

use Doctrine\ORM\NoResultException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 */
class PageController extends Controller
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
     * Render a CMS page
     * Action that is mapped in the controller_by_class map
     *
     * @param $page
     * @throws NotFoundHttpException
     */
    public function renderAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $website = $this->getWebsiteManager()->getWebsite('/website/prestaconcept', 'en');

        $theme = $this->getThemeManager()->getTheme($website->getTheme(), $website);

        $viewParams = array(
			'base_template' => $theme->getTemplate(),
			'website' => $website,
			'theme' => $theme,
			'page'  => $contentDocument
		);
        $pageType = $this->getPageManager()->getType($contentDocument->getType());
        if ($pageType != null) {
			$viewParams = array_merge($viewParams, $pageType->getData($contentDocument));
		}

        return $this->render('PrestaCMSCoreBundle:Page:index.html.twig', $viewParams);
    }

//    /**
//     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
//     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
//     */
//    public function catchAllAction(Request $request)
//    {
//        $website = $this->getWebsiteManager()->getWebsiteForRequest($this->getRequest());
//		$pathInfo = $request->getPathInfo();
//
//		//Relative path control
//		if (strpos($pathInfo, $website->getRelativePath()) !== 0) {
//			return $this->redirect($website->getUrl($request->getBaseUrl()));
//		}
//		//Load theme data
//        $theme = $this->getThemeManager()->getTheme($website->getTheme(), $website);
//
//		$pathInfo = (string)substr($request->getPathInfo(), strlen($website->getRelativePath()));
//
//		try {
//			$page = $this->getPageManager()->getPageByUrl($website, $pathInfo);
//		} catch (NoResultException $e) {
//			throw $this->createNotFoundException('Page not found');
//		}
//
//		$pageType = $this->getPageManager()->getType($page->getType());
//
//		$viewParams = array(
//			'base_template' => $theme->getTemplate(),
//			'website' => $website,
//			'theme' => $theme,
//			'page'  => $page
//		);
//		if ($pageType != null) {
//			$viewParams = array_merge($viewParams, $pageType->getData($page));
//		}
//        return $this->render('PrestaCMSCoreBundle:Page:index.html.twig', $viewParams);
//    }
}