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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien nbastien@prestaconcept.net
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('presta_cms_core');

        //TODO : check hox to use the requiresAtLeastOneElement !
        $rootNode
            ->children()
                ->arrayNode('themes')
                        ->prototype('array')
                            //->requiresAtLeastOneElement()                  
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('description')->end()
                                ->scalarNode('layout')->end()
                                ->arrayNode('page_template')
                                    ->prototype('array')
                                        //->requiresAtLeastOneElement()
                                        ->children()
                                            ->scalarNode('name')->end()
                                            ->scalarNode('path')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
