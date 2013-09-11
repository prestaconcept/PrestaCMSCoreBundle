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

use Behat\Behat\Context\BehatContext;

/**
 * Description of ThemeContext
 *
 * @author David Epely <depely@prestaconcept.net>
 */
class ThemeContext extends BehatContext
{
    /**
     * @Then /^I should see (\d+) themes$/
     */
    public function iShouldSeeThemes($arg1)
    {
        $this->getMainContext()->assertNumElements($arg1, '.sonata-ba-content table tbody tr');
    }

    /**
     * @Then /^I should see the creative theme configuration$/
     */
    public function iShouldSeeTheCreativeThemeConfiguration()
    {
        $this->getMainContext()->assertPageContainsText('General informations');
    }

    /**
     * @Given /^I should see (\d+) locales$/
     */
    public function iShouldSeeLocales($arg1)
    {
        $this->getMainContext()->assertNumElements($arg1, 'ul#website-locale li');
    }
}
