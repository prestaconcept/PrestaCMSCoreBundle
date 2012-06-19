<?php

/**
 * This file is part of the Presta Bundle project.
 *
 * @author Alain Flaus aflaus@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestaCMS\CoreBundle\Exception\Website;

/**
 * Thrown when a user try to access to a disabled or unexistant website
 */
class WebsiteNotFoundException extends WebsiteException
{
    /**
     * @var string
     */
    protected $message = 'Website not found';
}