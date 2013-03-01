<?php
/*
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Admin;

use Presta\CMSCoreBundle\Admin\BaseAdmin;
use Presta\CMSCoreBundle\Model\WebsiteManager;

/**
* Admin definition for the Site class
*/
class PageAdmin extends BaseAdmin
{
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    /**
     * @param WebsiteManager $websiteManager
     */
    public function setWebsiteManager($websiteManager)
    {
        $this->websiteManager = $websiteManager;
    }

    /**
     * @return WebsiteManager
     */
    public function getWebsiteManager()
    {
        return $this->websiteManager;
    }
}
