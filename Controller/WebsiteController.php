<?php
/**
 * This file is part of the PrestaCMSCoreBundle project.
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class WebsiteController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function switchWebsiteAction(Request $request)
    {
        $this->getWebsiteManager()->setCurrentWebsiteForAdmin($request->get('website'), $request->get('locale'));

        return $this->redirect($request->headers->get('referer'));
    }
}
