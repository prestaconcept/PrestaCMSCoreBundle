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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
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

        $rootNode
            ->children()
                ->scalarNode('default_website')->end()
                ->scalarNode('default_locale')->end()
                ->arrayNode('websites')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->end()
                            ->arrayNode('hosts')
                                ->useAttributeAsKey('env')
                                ->prototype('array')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('locale')->end()
                                            ->scalarNode('host')->end()
                                            ->scalarNode('scheme')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('themes')
                    ->prototype('array')
                        //->requiresAtLeastOneElement()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('description')->end()
                            ->arrayNode('navigations')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('template')->end()
                            ->scalarNode('screenshot')->end()
                            ->scalarNode('admin_style')->defaultValue('')->end()
                            ->arrayNode('block_styles')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('cols')->defaultValue(12)->end()
                            ->arrayNode('zones')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('rows')->defaultValue(1)->end()
                                        ->scalarNode('cols')->defaultValue(1)->end()
                                        ->scalarNode('name')->defaultValue('')->end()
                                        ->scalarNode('editable')->defaultValue(false)->end()
                                        ->scalarNode('sortable')->defaultValue(false)->end()
                                        ->arrayNode('blocks')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('name')->defaultValue('')->end()
                                                    ->scalarNode('type')->defaultValue('presta_cms.block')->end()
                                                    ->scalarNode('editable')->defaultValue(false)->end()
                                                    ->scalarNode('deletable')->defaultValue(false)->end()
                                                    ->scalarNode('position')->defaultValue(10)->end()
                                                    ->arrayNode('settings')
                                                        ->prototype('scalar')->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('page_template')
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('path')->end()
                                        ->arrayNode('zones')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('rows')->defaultValue(1)->end()
                                                    ->scalarNode('cols')->defaultValue(1)->end()
                                                    ->scalarNode('name')->defaultValue('')->end()
                                                    ->scalarNode('editable')->defaultValue(false)->end()
                                                    ->scalarNode('sortable')->defaultValue(false)->end()
                                                    ->arrayNode('blocks')
                                                        ->prototype('array')
                                                            ->children()
                                                                ->scalarNode('name')->defaultValue('')->end()
                                                                ->scalarNode('type')
                                                                    ->defaultValue('presta_cms.block')
                                                                ->end()
                                                                ->scalarNode('editable')->defaultValue(false)->end()
                                                                ->scalarNode('deletable')->defaultValue(false)->end()
                                                                ->scalarNode('position')->defaultValue(10)->end()
                                                                ->arrayNode('settings')
                                                                    ->prototype('scalar')->end()
                                                                ->end()
                                                            ->end()
                                                        ->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('blocks')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('accepted')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('excluded')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
