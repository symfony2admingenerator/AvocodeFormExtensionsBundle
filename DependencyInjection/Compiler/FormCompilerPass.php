<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FormCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');
        
        $resources = array_merge($resources, array(
            'AvocodeFormExtensionsBundle:Form:widget.html.twig', 
            'AvocodeFormExtensionsBundle:Form:javascript.html.twig', 
            'AvocodeFormExtensionsBundle:Form:stylesheet.html.twig'
        ));

        $container->setParameter('twig.form.resources', $resources);
    }
}
