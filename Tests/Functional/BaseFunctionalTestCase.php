<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Functional;

use Presta\CMSCoreBundle\Factory\ModelFactoryInterface;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase as CmfBaseFunctionalTestCase;
use PHPCR\Util\NodeHelper;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Symfony\Component\Yaml\Parser;
use Presta\CMSCoreBundle\Tests\Resources\app\AppKernel;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

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
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected $documentManager;

    /**
     * {@inherit}
     */
    protected static function createKernel(array $options = array())
    {
        return new \AppKernel('test', true);
    }

    protected function setUp()
    {
        $this->init();
        $this->loadFixtures();
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
        $session = self::$kernel->getContainer()->get('doctrine_phpcr.session');

        $purger = new PHPCRPurger($this->documentManager);
        $purger->purge();

        $basePath = '/website';

        // Create the path in the repository
        NodeHelper::createPath($session, $basePath);

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(FIXTURES_DIR . 'websites.yml'));

        foreach ($datas['websites'] as $configuration) {
            $website = $this->getWebsiteFactory()->create($configuration);
        }

        // Commit $document and $block to the database
        $this->documentManager->flush();
    }
}
