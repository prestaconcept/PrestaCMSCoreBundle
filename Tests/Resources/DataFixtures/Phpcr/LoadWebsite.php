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
use Presta\CMSCoreBundle\DataFixtures\PHPCR\BaseWebsiteFixture;
use PHPCR\Util\NodeHelper;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Website;
use Symfony\Component\Yaml\Parser;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadWebsite extends BaseWebsiteFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        // Get the base path name to use from the configuration
        $session = $manager->getPhpcrSession();
        $basePath = DIRECTORY_SEPARATOR . Website::WEBSITE_PREFIX;

        // Create the path in the repository
        NodeHelper::createPath($session, $basePath);

        $this->getFactory()->create(
            array(
                'path' => $basePath . DIRECTORY_SEPARATOR . 'sandbox',
                'name' => 'sandbox',
                'available_locales' => array('fr', 'en'),
                'default_locale' => 'fr',
                'theme' => 'test'
            )
        );

        $yaml = new Parser();
        $datas = $yaml->parse(file_get_contents(FIXTURES_DIR . 'websites.yml'));

        foreach ($datas['websites'] as $configuration) {
            $this->getFactory()->create($configuration);
        }

        $manager->flush();
    }
}
