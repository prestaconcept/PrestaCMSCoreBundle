<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional;

use Doctrine\ODM\PHPCR\DocumentManager;
use Presta\CMSCoreBundle\Factory\ModelFactoryInterface;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase as CmfBaseFunctionalTestCase;
use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;
use Symfony\Component\DependencyInjection\Container;

require __DIR__ . '/../Resources/app/AppKernel.php';

if (!defined('FIXTURES_DIR')) {
    define('FIXTURES_DIR', realpath(__DIR__.'/../Resources/DataFixtures/data/') . '/');
}

/**
 * Base test case for all functional tests
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BaseFunctionalTestCase extends CmfBaseFunctionalTestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * {@inherit}
     */
    protected static function createKernel(array $options = array())
    {
        return new \AppKernel('test', true);
    }

    /**
     * {@inherit}
     */
    protected function setUp()
    {
        $this->init();
        $this->loadFixtures();

        //Initialize current website
        $this->container->get('presta_cms.manager.website')->loadWebsiteById('/website/sandbox', 'en', 'dev');
    }

    /**
     * Init kernel and container
     */
    protected function init()
    {
        self::$kernel = self::createKernel();
        self::$kernel->boot();

        $this->container = self::$kernel->getContainer();
        $this->documentManager = self::$kernel->getContainer()->get('doctrine_phpcr.odm.document_manager');
    }

    /**
     * @return ModelFactoryInterface
     */
    protected function getWebsiteFactory()
    {
        return $this->container->get('presta_cms.website.factory');
    }

    /**
     * Load test fixtures
     */
    protected function loadFixtures()
    {
        $this->db('PHPCR')->loadFixtures(
            array(
                '\Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr\LoadWebsite',
                '\Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr\LoadPage',
                '\Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr\LoadRoute',
                '\Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr\LoadMenu',
                '\Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr\LoadTheme',
            )
        );

        $this->documentManager->flush();
    }
}
