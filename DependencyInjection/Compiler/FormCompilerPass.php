<?php

namespace Avocode\FormExtensionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Processes twig configuration
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FormCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');
        
        $resources = array_merge($resources, array(
            'AvocodeFormExtensionsBundle:Form:form_widgets.html.twig', 
            'AvocodeFormExtensionsBundle:Form:form_javascripts.html.twig', 
            'AvocodeFormExtensionsBundle:Form:form_stylesheets.html.twig'
        ));

        $container->setParameter('twig.form.resources', $resources);
    }
}
