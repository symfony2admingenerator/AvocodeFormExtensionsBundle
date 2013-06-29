<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('avocode_form_extensions');
        
        $rootNode
            ->children()
                ->booleanNode('use_genemu_form')->defaultFalse()->end()
                ->scalarNode('thumbnail_generator')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
