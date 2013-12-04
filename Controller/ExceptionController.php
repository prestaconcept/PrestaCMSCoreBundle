<?php
/**
 * This file is part of the PrestaCMSCoreBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Presta\CMSCoreBundle\Controller;

use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as TwigExceptionController;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ExceptionController extends TwigExceptionController
{
    /**
     * @var HttpKernelInterface
     */
    protected $httpKernel;

    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @var PageManager
     */
    protected $pageManager;

    /**
     * @param HttpKernelInterface $httpKernel
     */
    public function setHttpKernel($httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    /**
     * @param WebsiteManager $websiteManager
     */
    public function setWebsiteManager($websiteManager)
    {
        $this->websiteManager = $websiteManager;
    }

    /**
     * @param PageManager $pageManager
     */
    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * Override Default exception to redirect to customise error page hande with PrestaCMS
     *
     * {@inheritdoc}
     */
    public function showAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null,
        $_format = 'html'
    ) {
        if ($this->debug) {
            return parent::showAction($request, $exception, $logger, $_format);
        }

        $code       = $exception->getStatusCode();
        $website    = $this->websiteManager->getCurrentWebsite();
        $page       = $this->pageManager->getPageById($website->getPageRoot() . '/' . $code);

        if ($page !== null) {
            //Forward to CMS page
            $subRequest = $request->duplicate(array(), null, array(
                '_controller'      => 'PrestaCMSCoreBundle:Page:render',
                'contentDocument'  => $page
            ));

            return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }

        return parent::showAction($request, $exception, $logger, $_format);
    }
}
