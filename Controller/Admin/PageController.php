<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Controller\Admin;

use PrestaSonata\AdminBundle\Controller\Admin\Controller as AdminController;

/**
 * Page administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageController extends AdminController
{
    /**
     * Page administration main screen 
     */
    public function indexAction()
    {
        //get website
        
        //getcurrent page orhomepage
        
        //get page list
        $pages = array(
            'main' => array('Page 1', 'Page 2')
        );
        
        return $this->render('PrestaCMSCoreBundle:Admin/Page:index.html.twig', array(
            'pages' => $pages,
            'seo_form' => $this->_getPageSEOForm(),
            'settings_form' => $this->_getSettingsForm()
        ));
    }
    
    protected function _getPageSEOForm()
    {
        $form = $this->createFormBuilder()
            ->add('url')
            ->add('title')
            ->add('keywords')
            ->add('description', 'text')
            ->getForm();
        $formView = $form->createView(); 
        return $formView;
    }
    
    protected function _getSettingsForm()
    {
        $form = $this->createFormBuilder()
            ->add('breadcrumb-title')
            ->add('active', 'choice', array(
                'choices'   => array('m' => 'Yes', 'f' => 'No'),
                'required'  => false)
            )
            ->getForm();
        $formView = $form->createView(); 
        
        return $formView;
    }
}