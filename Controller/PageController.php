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
	 * Return Page manager
	 *
	 * @return PrestaCMS\CoreBundle\Model\PageManager
	 */
	public function getPageManager()
	{
		return $this->get('presta_cms.page_manager');
	}

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function catchAllAction(Request $request)
    {
        $website = $this->getWebsiteManager()->getWebsiteForRequest($this->getRequest());
		$pathInfo = $request->getPathInfo();

		//Relative path control
		if (strpos($pathInfo, $website->getRelativePath()) !== 0) {
			return $this->redirect($website->getUrl());
		}
		//Load theme data
        $theme = $this->getThemeManager()->getTheme($website->getTheme(), $website);

		$pathInfo = (string)substr($request->getPathInfo(), strlen($website->getRelativePath()));

		$page = $this->getPageManager()->getPageByUrl($website, $pathInfo);
		$pageType = $this->getPageManager()->getType($page->getType());

		$viewParams = array_merge(
			array(
				'base_template' => $theme->getTemplate(),
				'website' => $website,
				'theme' => $theme,
				'page'  => $page
			),
			$pageType->getData($page)
		);
        return $this->render('PrestaCMSCoreBundle:Page:index.html.twig', $viewParams);
    }
}