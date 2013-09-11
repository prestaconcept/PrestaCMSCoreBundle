<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;

use Presta\CMSCoreBundle\Model\Page as PageModel;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Page extends PageModel implements
    MenuNodeReferrersInterface,
    RouteReferrersInterface,
    TranslatableInterface
{
}
