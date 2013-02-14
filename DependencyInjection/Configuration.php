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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @package    PrestaCMS
 * @subpackage CoreBundle
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
                            ->scalarNode('cols')->defaultValue(12)->end()
                            ->arrayNode('zones')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('rows')->defaultValue(1)->end()
                                        ->scalarNode('cols')->defaultValue(1)->end()
                                        ->scalarNode('name')->defaultValue('')->end()
                                        ->scalarNode('can_add_block')->defaultValue(false)->end()
                                        ->scalarNode('can_sort_block')->defaultValue(false)->end()
                                        ->arrayNode('blocks')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('name')->defaultValue('')->end()
                                                    ->scalarNode('type')->defaultValue('presta_cms.block')->end()
                                                    ->scalarNode('is_editable')->defaultValue(false)->end()
                                                    ->scalarNode('is_deletable')->defaultValue(false)->end()
                                                    ->scalarNode('position')->defaultValue(10)->end()
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
                                                    ->scalarNode('can_add_block')->defaultValue(false)->end()
                                                    ->scalarNode('can_sort_block')->defaultValue(false)->end()
                                                    ->arrayNode('blocks')
                                                        ->requiresAtLeastOneElement()
                                                        ->prototype('array')
                                                            ->children()
                                                                ->scalarNode('name')->defaultValue('')->end()
                                                                ->scalarNode('type')->defaultValue('presta_cms.block')->end()
                                                                ->scalarNode('is_editable')->defaultValue(false)->end()
                                                                ->scalarNode('is_deletable')->defaultValue(false)->end()
                                                                ->scalarNode('position')->defaultValue(10)->end()
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
        ->end();

        return $treeBuilder;
    }
}
