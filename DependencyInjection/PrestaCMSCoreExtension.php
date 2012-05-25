<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * (c) Nicolas Bastien nbastien@prestaconcept.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use PrestaCMS\CoreBundle\Model\Theme;
use PrestaCMS\CoreBundle\Model\Template;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
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
        
        //Initialisation of ThemeManager with all theme defined by configuration
        $themeManager = $container->get('presta_cms.theme_manager');
        foreach ($config['themes'] as $themeConfiguration) {
            $themeManager->addTheme($this->_buildTheme($themeConfiguration));
        }
    }
    
    /**
     * Build Theme model from configuration
     * 
     * @param  array $configuration
     * @return \PrestaCMS\CoreBundle\Model\Theme 
     */
    protected function _buildTheme(array $configuration)
    {
        $theme = new Theme($configuration['name']);
        $theme->setDescription($configuration['description']);
        $theme->setLayout($configuration['layout']);
        foreach ($configuration['page_template'] as $templateConfiguration) {
            $template = new Template($templateConfiguration['name'], $templateConfiguration['path']);
            $theme->addPageTemplate($template);
        }        
        return $theme;
    }
}
