<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Presta\CMSCoreBundle\Model\Theme;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PrestaCMSCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        //Init website configuration
        $websiteManager = $container->getDefinition('presta_cms.website_manager');
        if (isset($config['default_website']) && isset($config['default_locale'])) {
            $websiteManager->addMethodCall('setDefaultWebsiteId', array($config['default_website']));
            $websiteManager->addMethodCall('setDefaultLocale', array($config['default_locale']));
        }

        if (isset($config['websites']) && is_array($config['websites'])) {
            foreach ($config['websites'] as $websiteConfiguration) {
                $websiteManager->addMethodCall('registerWebsite', array($websiteConfiguration));
            }
        }

        //Initialisation of ThemeManager definition with all theme defined by configuration
        $themeManager = $container->getDefinition('presta_cms.theme_manager');
        foreach ($config['themes'] as $themeConfiguration) {
            $themeManager->addMethodCall('addThemeConfiguration', array($themeConfiguration));
        }
    }
}
