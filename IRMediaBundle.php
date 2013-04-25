<?php

namespace IR\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use IR\MediaBundle\DependencyInjection\Compiler\AddProviderCompilerPass;

class IRMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddProviderCompilerPass());
    }    
}
