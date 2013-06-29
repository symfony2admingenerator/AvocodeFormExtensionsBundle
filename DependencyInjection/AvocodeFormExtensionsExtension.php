<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads FormExtensions configuration
 */
class AvocodeFormExtensionsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');
        // determine if GenemuFormBundle is registered
        if (isset($bundles['GenemuFormBundle']) || 
            isset($bundles['AdmingeneratorGeneratorBundle'])) {
            $config = array('use_genemu_form' => true);
            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'avocode_form_extensions':
                        $container->prependExtensionConfig($name, $config);
                        break;
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        
        if (!$config['use_genemu_form']) {
            $loader->load('form.xml');
        }
        
        $container->setParameter('avocode.form.thumbnail_generator', $config['thumbnail_generator']);
        
        $this->loadBootstrapCollectionTypes($container);
        $this->loadSelect2Types($container);
    }
    
    private function loadBootstrapCollectionTypes(ContainerBuilder $container)
    {
        $serviceId = 'avocode.form.extensions.type.bootstrap_collection';
        
        $bootstrapCollectionTypes = array('fieldset', 'table');
        
        foreach ($bootstrapCollectionTypes as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addTag('form.type', array('alias' => 'collection_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }
    
    private function loadSelect2Types(ContainerBuilder $container)
    {
        $serviceId = 'avocode.form.extensions.type.select2';
        
        $select2types = array(
            'choice', 'language', 'country', 'timezone', 
            'locale', 'entity', 'document', 'model', 'hidden'
        );
        
        foreach ($select2types as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addTag('form.type', array('alias' => 'select2_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }
}
