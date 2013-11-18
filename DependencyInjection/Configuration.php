<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
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
                ->scalarNode('upload_manager')->defaultNull()->end()
                ->scalarNode('image_manipulator')->defaultNull()->end()
                ->arrayNode('twig')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('use_form_resources')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('collection_upload')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('async_listener_enabled')->defaultFalse()->end()
                        ->scalarNode('async_route_name')->end() // TODO: add dynamic validation: if async_listener_enabled: true, this parameter should not be empty
                        ->scalarNode('file_storage')->defaultValue('avocode.form.file_storage.local')->end() // TODO: add dynamic validation
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
