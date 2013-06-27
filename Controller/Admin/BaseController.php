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

    /**
     * @param mixed   $data
     * @param integer $status
     * @param array   $headers
     *
     * @return Response with json encoded data
     */
    public function renderJson($data, $status = 200, $headers = array())
    {
        // fake content-type so browser does not show the download popup when this
        // response is rendered through an iframe (used by the jquery.form.js plugin)
        //  => don't know yet if it is the best solution
        if ($this->get('request')->get('_xml_http_request')
            && strpos($this->get('request')->headers->get('Content-Type'), 'multipart/form-data') === 0) {
            $headers['Content-Type'] = 'text/plain';
        } else {
            $headers['Content-Type'] = 'application/json';
        }

        return new Response(json_encode($data), $status, $headers);
    }

    /**
     * Translate a message
     *
     * @param  $message
     * @return string
     */
    public function trans($message, array $parameters = array(), $domain = 'PrestaCMSCoreBundle', $locale = null)
    {
        /** @var Translator $translator */
        $translator = $this->get('translator');

        return $translator->trans($message, $parameters, $domain, $locale);
    }
}
