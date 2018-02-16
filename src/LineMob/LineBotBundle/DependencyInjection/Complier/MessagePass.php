<?php

namespace LineMob\LineBotBundle\DependencyInjection\Complier;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MessagePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('linebot.message');

        foreach ($taggedServices as $id => $tags) {
            $definition = $container->findDefinition($id);
            foreach ($tags as $attributes) {
                $messageDefinition = new Definition($attributes['class']);
                $messageServiceId = sprintf('linemob.message.%s', $this->serializeClassNameToServiceId($attributes['class']));
                $container->setDefinition($messageServiceId, $messageDefinition);
                $definition->addMethodCall('add', array(new Reference($messageServiceId)));
            }
        }
    }

    /**
     * @param string $className
     *
     * @return string
     */
    private function serializeClassNameToServiceId(string $className)
    {
        $className = explode('\\', $className);
        // https://stackoverflow.com/a/19533226
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', end($className)));
    }
}
