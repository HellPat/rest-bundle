<?php

declare(strict_types=1);

namespace Violines\RestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('violines_rest');

        $treeBuilder->getRootNode()
            ->children()
                // serialize
                ->arrayNode('serialize')
                ->addDefaultsIfNotSet()
                    ->children()
                        // formats
                        ->arrayNode('formats')
                        ->addDefaultsIfNotSet()
                            ->children()
                                // json
                                ->arrayNode('json')
                                    ->scalarPrototype()->end()
                                    ->defaultValue(['application/json'])
                                ->end()
                                // xml
                                ->arrayNode('xml')
                                    ->scalarPrototype()->end()
                                    ->defaultValue(['application/xml'])
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('format_default')->defaultValue('application/json')
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
