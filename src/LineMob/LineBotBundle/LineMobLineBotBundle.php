<?php

namespace LineMob\LineBotBundle;

use LineMob\LineBotBundle\DependencyInjection\Complier\MessagePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LineMobLineBotBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new MessagePass());
    }
}
