<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;


final class RegisterReplyMessagePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('line_mob_keyword.registry.reply_message')) {
            return;
        }

        $registry = $container->getDefinition('line_mob_keyword.registry.reply_message');

        $types = [];
        foreach ($container->findTaggedServiceIds('line_mob_keyword.reply_message') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged line_mob_keyword.reply_message `' . $id . '` needs to have `type` `label` attributes.');
            }
            $definition = $container->getDefinition($id);

            /**
             * it's identifier.
             * @see \LineMob\KeywordBundle\Form\Type\AbstractReplyMessageType
             */
            $definition->replaceArgument(2, $attributes[0]['type']);

            $types[$attributes[0]['type']] = $attributes[0]['label'];
            $registry->addMethodCall('add', [$attributes[0]['type'], 'default', $definition->getClass()]);
        }

        $container->setParameter('line_mob_keyword.reply_messages', $types);
    }
}
