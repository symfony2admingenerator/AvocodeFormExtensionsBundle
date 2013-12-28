<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Loads FormExtensions configuration
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class AvocodeFormExtensionsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('avocode.form.upload_manager', $config['upload_manager']);
        $container->setParameter('avocode.form.image_manipulator', $config['image_manipulator']);
        $container->setParameter('avocode.form.twig', $config['twig']);

        $this->loadCollectionUploadListener($config['collection_upload'], $container);
        $this->loadBootstrapCollectionTypes($container);
        $this->loadDoubleListTypes($container);
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
                ->addTag('form.type', array('alias' => 'afe_collection_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }

    private function loadDoubleListTypes(ContainerBuilder $container)
    {
        $serviceId = 'avocode.form.extensions.type.double_list';

        $doubleListTypes = array(
            'entity', 'document', 'model'
        );

        foreach ($doubleListTypes as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addTag('form.type', array('alias' => 'afe_double_list_'.$type))
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
                ->addTag('form.type', array('alias' => 'afe_select2_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }

    /**
     * Add the collection upload listener if required
     *
     * @param array $config
     * @param ContainerBuilder $container
     * @throws \LogicException
     */
    private function loadCollectionUploadListener(array $config, ContainerBuilder $container)
    {
        if ($config['async_listener_enabled']) {
            if (!(array_key_exists('async_route_name', $config) && $routeName = $config['async_route_name'])) {
                throw new \LogicException('async_route_name must be defined when async_listener_enabled is true');
            }

            $collectionUploadListenerDefinition = new Definition('%avocode.form.collection_upload_listener.class%');
            $collectionUploadListenerDefinition->setArguments(array(
                    new Reference($config['file_storage']),
                    $routeName,
                    new Reference('property_accessor')
            ));
            $collectionUploadListenerDefinition->addTag('kernel.event_subscriber');
            $container->setDefinition('avocode.form.collection_upload_listener', $collectionUploadListenerDefinition);

            $container->getDefinition('avocode.form.extensions.type.collection_upload')->addMethodCall('setFileStorage', array(new Reference($config['file_storage'])));
        }
    }
}
