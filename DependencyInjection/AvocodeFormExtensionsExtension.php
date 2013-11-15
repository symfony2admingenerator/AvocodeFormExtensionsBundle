<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
}
