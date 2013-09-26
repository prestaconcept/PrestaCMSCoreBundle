<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ODM\PHPCR\DocumentManager;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * @author David Epely <depely@prestaconcept.net>
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->useContext('theme', new ThemeContext);
        $this->useContext('website', new WebsiteContext);
        $this->useContext('page', new PageContext);
    }

    /**
     * @Given /^application is initialized$/
     */
    public function applicationIsInitialized()
    {
        //fixtures are loaded by functional tests
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I visit the admin dashboard$/
     */
    public function iVisitTheAdminDashboard()
    {
        $this->visit('/admin');
    }

    /**
     * @Given /^I am logged in as "([^"]*)" with the password "([^"]*)"$/
     */
    public function iAmLoggedInAsWithThePassword($arg1, $arg2)
    {
        $this->getSession()->setBasicAuth($arg1, $arg2);
    }

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->kernel->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }

    /**
     * @When /^I follow dashboard "([^"]*)" link "([^"]*)"$/
     */
    public function iFollowDashboardLink($arg1, $arg2)
    {
        $session = $this->getMainContext()->getSession();
        $element = $session->getPage()->find(
            'css',
            ".sonata-ba-list tr:contains($arg1) a:contains($arg2)"
        );

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not find "%s" Website or "%s" link', $arg1, $arg2));
        }

        $element->click();
    }
}
