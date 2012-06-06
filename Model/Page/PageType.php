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
 * Base page type
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageType
{
    /**
     * Return PageType corresponding to $type
     * 
     * @param string $type 
     * @return PageInterface
     */
    public static function getType($type)
    {
        //todo
        return new PageTypeCMS();
    }
}