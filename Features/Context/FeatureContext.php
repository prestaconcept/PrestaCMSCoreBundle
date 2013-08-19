<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author David Epely depely@prestaconcept.net>
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

    //
    // Place your definition and hook methods here:
    //
    //    /**
    //     * @Given /^I have done something with "([^"]*)"$/
    //     */
    //    public function iHaveDoneSomethingWith($argument)
    //    {
    //        $container = $this->kernel->getContainer();
    //        $container->get('some_service')->doSomethingWith($argument);
    //    }

    /**
     * @Given /^I visit the admin dashboard$/
     */
    public function iVisitTheAdminDashboard()
    {
        $this->visit('/admin/en');
    }

    /**
     * @Given /^I am logged in as "([^"]*)" with the password "([^"]*)"$/
     */
    public function iAmLoggedInAsWithThePassword($arg1, $arg2)
    {
        $this->visit('/admin/en/login');

        $this->fillField('username', $arg1);
        $this->fillField('password', $arg2);
        $this->pressButton('_submit');
    }

    /**
     * @Given /^there are websites:$/
     */
    public function thereAreWebsites(TableNode $table)
    {
        $dm = $this->getDocumentManager();
        $lines = $table->getRows();

        //remove first heading line
        array_shift($lines);
        try {
            foreach ($lines as $row) {
                $locales = array_map('trim', explode(',', $row[1]));
                $website = new Website();
                $website->setName($row[0]);
                $website->setLocale(reset($locales));
                $website->setAvailableLocales($locales);
                $dm->persist($website);
            }

            $dm->flush();
        } catch (\Exception $e) {
            //websites already exists
        }
    }

    /**
     * @Then /^I should see (\d+) websites$/
     */
    public function iShouldSeeWebsites($arg1)
    {
        $this->assertNumElements($arg1, 'table > tbody tr');
    }

    protected function logLastResponse()
    {
        file_put_contents('behat.out.html', $this->getSession()->getPage()->getContent());
    }

    protected function getDoctrine()
    {
        return $this->kernel->getContainer()->get('doctrine');
    }

    protected function getDocumentManager()
    {
        return $this->kernel->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }
}
