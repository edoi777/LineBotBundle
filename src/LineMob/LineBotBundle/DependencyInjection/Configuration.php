<?php

namespace LineMob\LineBotBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('line_mob_line_bot');

        $rootNode
            ->children()
                ->arrayNode('bots')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('commands')->prototype('scalar')->end()->end()
                            ->arrayNode('middlewares')->prototype('scalar')->end()->end()
                            ->scalarNode('line_channel_access_token')->isRequired()->end()
                            ->scalarNode('line_channel_secret')->isRequired()->end()
                            ->scalarNode('registry')->defaultValue('linemob.registry')->end()
                            ->scalarNode('http_client_class')->defaultValue('LineMob\Core\HttpClient\GuzzleHttpClient')->end()
                            ->booleanNode('use_sender_mocky')->defaultValue(false)->end()
                            ->booleanNode('log')->defaultValue(false)->end()
                            ->scalarNode('logger')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
