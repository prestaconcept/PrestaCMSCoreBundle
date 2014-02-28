<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Presta\CMSCoreBundle\Factory\ThemeFactory;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseWebsiteFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @return ThemeFactory
     */
    public function getFactory()
    {
        return $this->container->get('presta_cms.website.factory');
    }
}
