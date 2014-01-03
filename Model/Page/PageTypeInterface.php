<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model\Page;

use Presta\CMSCoreBundle\Model\Page;
use Sonata\AdminBundle\Admin\Pool;

/**
 * Page type interface
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
interface PageTypeInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * Returns the default settings link to the service
     *
     * @return array
     */
    public function getDefaultSettings();

    /**
     * Returns type specific tabs for page edition
     *
     * @param Page $page
     *
     * @return array
     */
    public function getEditTabs(Page $page);

    /**
     * Return all data needed to render $tab
     *
     * @param  string $tab
     * @param  Page   $page
     * @param  string $menuNodeId
     * @param  Pool   $pool
     *
     * @return array
     */
    public function getEditTabData($tab, Page $page, $menuNodeId, $pool);

    /**
     * Return $tab template
     *
     * @param  string $tab
     * @return string
     */
    public function getEditTabTemplate($tab);

    /**
     * Return type specific data for page rendering
     *
     * @param  Page  $page
     * @return array
     */
    public function getData(Page $page);
}
