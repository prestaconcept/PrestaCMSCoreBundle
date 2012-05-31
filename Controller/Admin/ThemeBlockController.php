<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Controller\Admin;

use PrestaSonata\AdminBundle\Controller\Admin\Controller as AdminController;
use Sonata\AdminBundle\Controller\CRUDController;
/**
 * Theme administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class ThemeBlockController extends CRUDController
{    
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
     * Render a block
     * 
     * @param  integer $id
     * @return type 
     */
    public function renderAction($id)
    {
        $block = $this->admin->getObject($id);
        
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:render_block.html.twig', array(
            'block' => $block
        ));
    }
    
}