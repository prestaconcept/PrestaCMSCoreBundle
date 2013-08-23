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
}
