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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class ZoneController extends BaseController
{
    /**
     * @param  Request $request
     *
     * @return Response
     */
    public function sortBlocksAction(Request $request)
    {
        $zoneManager = $this->getZoneManager();
        $blockIds    = $request->get('blockIds');
        $zoneId      = $request->get('id');
        $zone        = ($zoneId !== null) ? $zoneManager->getModelManager()->find(null, $zoneId) : null;

        $viewParams = array();
        if ($zone !== null && count($blockIds)) {
            $zoneManager->updateZoneBlocks($zone, $blockIds);
        } else {
            $viewParams['error'] = 'flash_edit_error';
        }

        return $this->renderJson($viewParams);
    }

    /**
     * @return ZoneManager
     */
    protected function getZoneManager()
    {
        return $this->get('presta_cms.manager.zone');
    }
}
