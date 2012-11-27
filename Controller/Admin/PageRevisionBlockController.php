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

use Sonata\AdminBundle\Controller\CRUDController;

/**
 * Page revision block administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageRevisionBlockController extends CRUDController
{
    /**
     * Render a block
     * 
     * @param  integer $id
     * @return Response 
     */
    public function renderAction($id)
    {
		$block = $this->admin->getObject($id);
		$block->setAdminMode();
        return $this->render('PrestaCMSCoreBundle:Admin/PageRevision:render_block.html.twig', array(
            'block' => $block
        ));
    }    
}