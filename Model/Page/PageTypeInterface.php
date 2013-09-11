<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model\Page;

use Presta\CMSCoreBundle\Model\Page;

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
     * @return array
     */
    public function getEditTabs();

    /**
     * Return all data needed to render $tab
     *
     * @param  string $tab
     * @param  Page   $page
     * @return array
     */
    public function getEditTabData($tab, Page $page);

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
