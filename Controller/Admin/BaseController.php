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

use Symfony\Bundle\FrameworkBundle\Controller\Controller as sfController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base controller for administration
 *
 * @package    Presta
 * @subpackage SonataAdminBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseController extends sfController
{
    /**
     * The related Admin class
     *
     * @var \Sonata\AdminBundle\Admin\AdminInterface
     */
    protected $admin;

    /**
     *
     * @return boolean true if the request is done by an ajax like query
     */
    public function isXmlHttpRequest()
    {
        return $this->get('request')->isXmlHttpRequest() || $this->get('request')->get('_xml_http_request');
    }

    /**
     * return the base template name
     *
     * @return string the template name
     */
    public function getBaseTemplate()
    {
        if ($this->isXmlHttpRequest()) {
            return $this->admin->getTemplate('ajax');
        }

        return $this->admin->getTemplate('layout');
    }

    /**
     * @param string                                          $view
     * @param array                                           $parameters
     * @param null|\Symfony\Component\HttpFoundation\Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $this->admin = $this->get('sonata.admin.pool');

        $parameters['base_template'] = isset($parameters['base_template']) ? $parameters['base_template'] : $this->getBaseTemplate();
        $parameters['admin_pool']    = $this->get('sonata.admin.pool');

        return parent::render($view, $parameters, $response);
    }
}