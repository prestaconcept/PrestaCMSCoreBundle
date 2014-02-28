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

use Presta\CMSCoreBundle\Factory\MenuFactory;

/**
 * Base fixtures methods to easily create menu
 */
abstract class BaseMenuFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }

    /**
     * @return MenuFactory
     */
    public function getFactory()
    {
        return $this->container->get('presta_cms.menu.factory');
    }
}
