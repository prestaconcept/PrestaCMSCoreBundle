<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
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
        //TODO: behat should be run under prestaCms Full stack
        //waiting for fixtures loading system from @nbastien
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
     * @return Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }

    /**
     * @return Doctrine\ODM\PHPCR\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->kernel->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }
}
