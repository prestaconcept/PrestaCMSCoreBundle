<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Unit;

if (!defined('FIXTURES_DIR')) {
    define('FIXTURES_DIR', realpath(__DIR__.'/../Resources/DataFixtures/data/') . '/');
}
/**
 * Base test case for all unit tests
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BaseUnitTestCase extends \PHPUnit_Framework_TestCase
{
}
