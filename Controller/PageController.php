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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $request     = $this->getRequest();
        $pageManager = $this->getPageManager();

        //Cache validation
        $response = new Response();
        $response->setPublic();
        $response->setLastModified($contentDocument->getLastCacheModifiedDate());
        $previewToken = $request->get('token', null);
        $isPreviewMode = ($previewToken != null && $pageManager->isValidToken($contentDocument, $previewToken));

        if ($response->isNotModified($request) && $isPreviewMode == false) {
            return $response;
        }

        $website = $this->getWebsiteManager()->getCurrentWebsite();
        $theme   = $this->getThemeManager()->getTheme($website->getTheme(), $website);

        //If document load doesn't have the same locale as the website
        //Try to redirect on the translated page
        if ($contentDocument->getLocale() != $website->getLocale()) {
            throw new NotFoundHttpException('Content not found for this locale');
        }
        //Check if the document is publish and load the good version
        //todo when jackaplone implements it

        $seoPage = $this->get('sonata.seo.page');

        $seoPage
            ->setTitle($contentDocument->getTitle())
            ->addMeta('name', 'keywords', $contentDocument->getMetaKeywords())
            ->addMeta('name', 'description', $contentDocument->getMetaDescription());

        $viewParams = array(
            'base_template' => $theme->getTemplate(),
            'website' => $website,
            'websiteManager' => $this->getWebsiteManager(),
            'theme' => $theme,
            'page'  => $contentDocument
        );

        $pageManager->setCurrentPage($contentDocument);
        $pageType = $pageManager->getType($contentDocument->getType());
        if ($pageType != null) {
            $viewParams = array_merge($viewParams, $pageType->getData($contentDocument));
        }
        //todo voir pour une meileur initialisation
        //on doit charger directement le tempalte pour que ce denier puisse surcharge
        //des partie du layout librement
        $template = $viewParams['template'];

        if ($isPreviewMode) {
            //In preview we need to remove the cache data form the response
            $response = null;
        }

        return $this->render($template, $viewParams, $response);
    }
}
