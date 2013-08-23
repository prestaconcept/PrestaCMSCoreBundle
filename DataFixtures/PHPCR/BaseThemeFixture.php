<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DataFixtures\PHPCR;

use Presta\CMSCoreBundle\Factory\ThemeFactory;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class BaseThemeFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 60;
    }

    /**
     * @return ThemeFactory
     */
    public function getFactory()
    {
        return $this->container->get('presta_cms.theme.factory');
    }
}
