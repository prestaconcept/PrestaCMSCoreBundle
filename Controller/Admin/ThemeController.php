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

/**
 * Theme administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class ThemeController extends AdminController
{    
    /**
     *
     * @return type 
     */
    public function listAction()
    {
        return $this->render('PrestaCMSCoreBundle:Admin/Theme:list.html.twig', array());
    }
}