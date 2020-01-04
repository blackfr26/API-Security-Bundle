<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\DependencyInjection;
 
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
 
/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_security');
        
        $rootNode
            ->children()
                ->booleanNode('allow_localhost')->defaultTrue()->end()
                ->scalarNode('api_key')->defaultFalse()->end()
                ->arrayNode('authorized_ips')
                    ->beforeNormalization()->ifString()->then(function ($v) { return array($v); })->end()
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('auth_header')->defaultValue('X-AUTH-TOKEN')->end()
            ->end()
        ;
 
        return $treeBuilder;
    }
}