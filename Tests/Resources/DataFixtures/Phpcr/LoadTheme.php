<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\Persistence\ObjectManager;
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseThemeFixture;

use Symfony\Component\Yaml\Parser;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadTheme extends BaseThemeFixture
{
    /**
     * Création des menus de navigation
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $website = $manager->find(null, '/website/sandbox');

        $yaml = new Parser();
        $configuration = $yaml->parse(file_get_contents(FIXTURES_DIR . 'theme_test.yml'));

        $this->getFactory()->initializeForWebsite($website, $configuration);
    }
}
