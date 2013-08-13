<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
//namespace Presta\CMSCoreBundle\Tests\Resources\app;

use Symfony\Component\Filesystem\Filesystem;
use \Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * AppKernel for PrestaCMSCoreBundle Functional tests
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->requireBundleSets(array(
            'default','phpcr_odm', 'sonata_admin'
        ));

        $this->addBundles(array(
            new \Sonata\SeoBundle\SonataSeoBundle(),

            // CMF bundles
            new \Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            new \Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            new \Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle(),
            new \Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
//            new \Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),
            new \Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),

            new \Presta\CMSCoreBundle\PrestaCMSCoreBundle(),
        ));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
    }
}
