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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class WebsiteController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function switchWebsiteAction(Request $request)
    {
        $session = $this->get('session');
        $session->set('presta_cms.website', $request->get('website'));
        $session->set('presta_cms.locale', $request->get('locale'));

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}
