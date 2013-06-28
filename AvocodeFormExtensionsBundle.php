<?php

namespace Avocode\FormExtensionsBundle;

use Avocode\FormExtensionsBundle\DependencyInjection\Compiler\FormCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Form extensions for Symfony2 Admingenerator project
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class AvocodeFormExtensionsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormCompilerPass());
    }
}