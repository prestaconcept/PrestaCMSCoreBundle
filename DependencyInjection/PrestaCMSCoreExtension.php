<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('persistence-phpcr.xml');
        $loader->load('admin.xml');
        $loader->load('block.xml');
        $loader->load('manager.xml');
        $loader->load('listener.xml');
        $loader->load('services.xml');
        $loader->load('controller.xml');

        //Prepare for dynamic persistence layer
        $container->setParameter(
            'presta_cms.persistence.phpcr.manager_name',
            null
        );

        //Init website configuration
        $websiteManager = $container->getDefinition('presta_cms.manager.website');
        if (isset($config['default_website']) && isset($config['default_locale'])) {
            $websiteManager->addMethodCall('setDefaultWebsiteId', array($config['default_website']));
            $websiteManager->addMethodCall('setDefaultLocale', array($config['default_locale']));
        }

        if (isset($config['websites']) && is_array($config['websites'])) {
            foreach ($config['websites'] as $websiteConfiguration) {
                $websiteManager->addMethodCall('registerWebsite', array($websiteConfiguration));
            }
        }

        //Init block configuration
        $blockManager = $container->getDefinition('presta_cms.manager.block');
        if (isset($config['blocks']) && is_array($config['blocks'])) {
            foreach ($config['blocks'] as $type => $blockConfiguration) {
                $blockManager->addMethodCall('addConfiguration', array($blockConfiguration, $type));
            }
        }

        //Initialisation of ThemeManager definition with all theme defined by configuration
        $themeManager = $container->getDefinition('presta_cms.manager.theme');
        foreach ($config['themes'] as $themeConfiguration) {
            $themeManager->addMethodCall('addThemeConfiguration', array($themeConfiguration));
        }

        // Set cache parameter
        $presta_cms_core_cache_enabled = isset($config['cache']['enabled']) ? $config['cache']['enabled'] : false;
        $container->setParameter('presta_cms_core.cache.enabled', $presta_cms_core_cache_enabled);
    }
}
