<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Exception\Page;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageTypeNotFoundException extends \InvalidArgumentException
{
    public function __construct($pageTypeId)
    {
        $this->message = 'Page type not found : ' . $pageTypeId
            . PHP_EOL . 'Maybe you should install the corresponding bundle';
    }
}
