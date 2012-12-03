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
 * Theme administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BlockController extends CRUDController
{
    /**
     * Render a block
     * 
     * @param  integer $id
     * @return Response 
     */
    public function renderAction()
    {
        $id = $this->getRequest()->get('id');

        return $this->render('PrestaCMSCoreBundle:Admin/Block:render_block.html.twig', array(
            'block' => $this->admin->getObject($id)
        ));
    }    
}