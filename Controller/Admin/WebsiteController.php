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

use Presta\CMSCoreBundle\Model\PageManager;
use Presta\CMSCoreBundle\Model\Website;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Website administration controller, handle with Sonata
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteController extends CRUDController
{
    /**
     * @return RedirectResponse
     *
     * @throws NotFoundHttpException
     */
    public function clearCacheAction()
    {
        $websiteId = $this->getRequest()->get('id', null);
        $website   = $this->admin->getObject($websiteId);

        if (!$website instanceof Website) {
            throw new NotFoundHttpException(sprintf("unable to find the website with id : %s", $websiteId));
        }

        try {
            $this->getPageManager()->clearCacheForWebsite($website);
            $this->addFlash('sonata_flash_success', 'flash_edit_success');
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_edit_error');
        }

        return new RedirectResponse(
            $this->admin->generateUrl('list', $this->admin->getFilterParameters())
        );
    }

    /**
     * @return PageManager
     */
    protected function getPageManager()
    {
        return $this->get('presta_cms.manager.page');
    }
}
