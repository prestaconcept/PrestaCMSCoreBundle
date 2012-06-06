<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Model;

/**
 * Page type interface
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
interface PageInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * Returns the default settings link to the service
     *
     * @return array
     */
    public function getDefaultSettings();
    
    /**
     * Returns type specific tabs for page edtion
     * 
     * @return array 
     */
    public function getEditTabs();
}