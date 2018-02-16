<?php

namespace LineMob\LineBotBundle\DependencyInjection;

use League\Tactician\CommandBus;
use LineMob\Core\CommandHandler;
use LineMob\Core\Mocky\Sender;
use LineMob\Core\Receiver;
use LineMob\Core\RegistryInterface;
use LineMob\Core\Sender\LineSender;
use LineMob\LineBotBundle\Bot\Bot;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class LineMobLineBotExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->registerBots($config, $container);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles['TacticianBundle'])) {
            throw new ServiceNotFoundException('TacticianBundle must be registered in kernel.');
        }
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    private function registerBots($config, ContainerBuilder $container)
    {
        foreach ($config['bots'] as $name => $botConfig) {
            $httpClient = new Definition($botConfig['http_client_class']);
            $httpClient->setArgument(0, $botConfig['line_channel_access_token']);
            $httpClient->setPublic(false);
            $container->setDefinition(sprintf('linemob.%s.http_client', $name), $httpClient);


            if (true === $botConfig['use_sender_mocky']) {
                $sender = new Definition(Sender::class);
            } else {
                $sender = new Definition(LineSender::class);
                $sender->setArgument(0, new Reference(sprintf('linemob.%s.http_client', $name)));
                $sender->setArgument(1, ['channelSecret' => $botConfig['line_channel_secret']]);
                $sender->setPublic(false);
            }

            $container->setDefinition(sprintf('linemob.%s.sender', $name), $sender);


            $commandHandler = new Definition(CommandHandler::class);
            $commandHandler->setArgument(0, new Reference(sprintf('linemob.%s.sender', $name)));
            $commandHandler->setArgument(1, new Reference('linemob.message_factory'));
            $commandHandler->setPublic(false);
            foreach ($botConfig['commands'] as $key => $command) {
                $commandHandler->addTag('tactician.handler', [
                    'command' => $command
                ]);
            }
            $container->setDefinition(sprintf('linemob.%s.command_handler', $name), $commandHandler);


            $registryService = $container->get($botConfig['registry']);
            if (!$registryService instanceof RegistryInterface) {
                throw new \InvalidArgumentException(sprintf('"%s" must be implemented in "%s"', RegistryInterface::class, get_class($registryService)));
            }

            $registryDefinition = $container->getDefinition($botConfig['registry']);
            $registry = new Definition($registryDefinition->getClass(), $registryDefinition->getArguments());
            foreach ($botConfig['commands'] as $key => $command) {
                if (!class_exists($command)) {
                    throw new ServiceNotFoundException(sprintf(
                        'A command class `%s` not found', $command
                    ));
                }

                $registry->addMethodCall('add', array($command, new Reference(sprintf('linemob.%s.command_handler', $name)), (0 === $key)));
            }
            $container->setDefinition(sprintf('linemob.%s.registry', $name), $registry);


            $middlewares = array_map(
                function ($middlewareServiceId) {
                    return new Reference($middlewareServiceId);
                },
                $botConfig['middlewares']
            );
            $commandBus = new Definition(CommandBus::class);
            $commandBus->setArgument(0 , $middlewares);
            $commandBus->setPublic(false);
            $container->setDefinition(sprintf('linemob.%s.commandbus', $name), $commandBus);


            $receiver = new Definition(Receiver::class);
            $receiver->setArgument(0, new Reference(sprintf('linemob.%s.sender', $name)));
            $receiver->setArgument(1, new Reference(sprintf('linemob.%s.registry', $name)));
            $receiver->setArgument(2, new Reference(sprintf('linemob.%s.commandbus', $name)));
            $receiver->setArgument(3, new Reference(sprintf('linemob.%s.command_handler', $name)));
            $receiver->setPublic(false);
            $container->setDefinition(sprintf('linemob.%s.receiver', $name), $receiver);


            $bot = new Definition(Bot::class);
            $bot->setArgument(0, $name);
            $bot->setArgument(1, new Reference(sprintf('linemob.%s.receiver', $name)));
            $bot->setArgument(2, new Reference('linemob.context.bot'));
            if (true === $botConfig['log']) {
                if (!isset($botConfig['logger'])) {
                    throw new InvalidConfigurationException('logger must be config');
                }

                $bot->setArgument(3, new Reference($botConfig['logger']));
            }
            $bot->setPublic(true);
            $container->setDefinition(sprintf('linemob.%s.bot', $name), $bot);
        }
    }
}
