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

use Presta\CMSCoreBundle\Document\Page;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event configuration for page operation, based class for all page events
 *
 * @package    Presta
 * @subpackage CMSCoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class PageOperationEvent extends Event
{
    const EVENT_NAME = 'presta_cms.page.operation';

    /**
     * @var Page
     */
    protected $page;

    /**
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @return \Presta\CMSCoreBundle\Document\Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
