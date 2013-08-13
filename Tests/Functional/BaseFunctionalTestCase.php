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


use Symfony\Cmf\Component\Testing\Functional\BaseTestCase as CmfBaseFunctionalTestCase;
use PHPCR\Util\NodeHelper;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Document\Website;

require __DIR__ . '/../Resources/app/AppKernel.php';

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
        $datas = $yaml->parse(file_get_contents(__DIR__ . '/fixtures/websites.yml'));

        foreach ($datas['websites'] as $configuration) {
            $website = $this->createWebsite($configuration);
        }

        // Commit $document and $block to the database
        $this->documentManager->flush();
    }

    protected function setUp()
    {
        $this->init();
        $this->loadFixtures();
    }

    /**
     * Create a website
     *
     * @param  array                                  $configuration
     * @return \Presta\CMSCoreBundle\Document\Website
     */
    protected function createWebsite(array $configuration)
    {
        $website = new Website();
        $website->setPath($configuration['path']);
        $website->setName($configuration['name']);
        $website->setAvailableLocales($configuration['available_locales']);
        $website->setDefaultLocale($configuration['default_locale']);
        $website->setTheme($configuration['theme']);

        $this->documentManager->persist($website);

        foreach ($configuration['available_locales'] as $locale) {
            $this->documentManager->bindTranslation($website, $locale);
        }

        return $website;
    }
}
