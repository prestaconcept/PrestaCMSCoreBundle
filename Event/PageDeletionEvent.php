<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event configuration for page deletion
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageDeletionEvent extends PageOperationEvent
{
    const EVENT_NAME = 'presta_cms.page.deletion';
}
