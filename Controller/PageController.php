<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller;

use Presta\CMSCoreBundle\Model\Page;
use Presta\CMSCoreBundle\Model\PageManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 * @author Alain Flaus <aflaus@prestaconcept.net>
 */
class PageController extends AbstractController
{
    /**
     * @return PageManager
     */
    protected function getPageManager()
    {
        return $this->get('presta_cms.manager.page');
    }

    /**
     * @param Page $contentDocument
     */
    protected function initSEO(Page $contentDocument)
    {
        $this->getSeoManager()
            ->setTitle($contentDocument->getTitle())
            ->addMeta('name', 'keywords', $contentDocument->getMetaKeywords())
            ->addMeta('name', 'description', $contentDocument->getMetaDescription());
    }

    /**
     * @param  Page $contentDocument
     *
     * @return array
     */
    protected function getViewParams(Page $contentDocument)
    {
        $viewParams = $this->getBaseViewParams();
        $viewParams['page'] = $contentDocument;

        $pageManager = $this->getPageManager();
        $pageManager->setCurrentPage($contentDocument);
        $pageType = $pageManager->getPageType($contentDocument->getType());
        if ($pageType != null) {
            $viewParams = array_merge($viewParams, $pageType->getData($contentDocument));
        }

        return $viewParams;
    }

    /**
     * @param  Page $contentDocument
     *
     * @return bool
     */
    protected function isCacheEnabled(Page $contentDocument)
    {
        $previewToken   = $this->getRequest()->get('token', null);
        $isPreviewMode  = $this->getPageManager()->isValidToken($contentDocument, $previewToken);

        return ($isPreviewMode == false && $this->container->getParameter('presta_cms_core.cache.enabled'));
    }

    /**
     * @param  Page $contentDocument
     *
     * @return Response
     */
    protected function getResponse(Page $contentDocument)
    {
        $response = new Response();
        if ($contentDocument->getCachePrivate()) {
            $response->setPrivate();
        } else {
            $response->setPublic();
        }
        if ($contentDocument->getCacheMaxAge() > 0) {
            $response->setMaxAge($contentDocument->getCacheMaxAge());
        }
        if ($contentDocument->getCacheSharedMaxAge() > 0) {
            $response->setSharedMaxAge($contentDocument->getCacheSharedMaxAge());
        }
        if ($contentDocument->getCacheMustRevalidate()) {
            $response->headers->addCacheControlDirective('must-revalidate', true);
        }
        $response->setLastModified($contentDocument->getLastCacheModifiedDate());

        return $response;
    }

    /**
     * Render a CMS page
     * Action that is mapped in the controller_by_class map
     *
     * @param  Page                  $contentDocument
     * @throws NotFoundHttpException
     */
    public function renderAction(Page $contentDocument)
    {
        $website = $this->getWebsiteManager()->getCurrentWebsite();
        if (!$contentDocument || ($contentDocument->getLocale() != $website->getLocale())) {
            throw new NotFoundHttpException('Content not found');
        }

        $response = $this->getResponse($contentDocument);
        if ($this->isCacheEnabled($contentDocument) && $response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->initSEO($contentDocument);
        $viewParams = $this->getViewParams($contentDocument);

        return $this->render($viewParams['template'], $viewParams, $response);
    }
}
