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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPCR\Util\NodeHelper;
use Doctrine\Common\DataFixtures\Purger\PHPCRPurger;
use Symfony\Component\Yaml\Parser;

use Presta\CMSCoreBundle\Document\Website;

/**
 * Base test case for all functional tests
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BaseFunctionalTestCase extends WebTestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected $documentManager;

    protected function loadFixtures()
    {
        self::$kernel = self::createKernel();
        self::$kernel->init();
        self::$kernel->boot();

        $this->container = self::$kernel->getContainer();
        $session = self::$kernel->getContainer()->get('doctrine_phpcr.session');
        $this->documentManager = self::$kernel->getContainer()->get('doctrine_phpcr.odm.document_manager');

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
        $this->loadFixtures();
    }

    /**
     * Create a website
     *
     * @param array $configuration
     * @return \Presta\CMSCoreBundle\Document\Website
     */
    protected function createWebsite(array $configuration)
    {
        $website = new Website();
        $website->setPath($configuration['path']);
        $website->setAvailableLocales($configuration['available_locales']);
        $website->setDefaultLocale($configuration['default_locale']);
        $website->setTheme($configuration['theme']);
        $website->setName($configuration['name']);

        $this->documentManager->persist($website);

        foreach ($configuration['available_locales'] as $locale) {
            $this->documentManager->bindTranslation($website, $locale);
        }

        return $website;
    }


}

