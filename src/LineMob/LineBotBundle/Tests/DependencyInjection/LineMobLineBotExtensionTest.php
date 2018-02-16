<?php

declare(strict_types = 1);

namespace Linemob\LinebotBundle\Tests\DependencyInjection;

use LineMob\Core\Mocky\Sender;
use LineMob\Core\Sender\LineSender;
use LineMob\LineBotBundle\DependencyInjection\LineMobLineBotExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Reference;

final class LineMobLineBotExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_should_have_bot_service_depend_on_config(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo'
                ],
                'my_bot2' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo'
                ],
            ],
        ]);

        $this->assertContainerBuilderHasService('linemob.my_bot.bot');
        $this->assertContainerBuilderHasService('linemob.my_bot2.bot');
    }

    /**
     * @test
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function it_should_throw_when_class_not_exist_in_command_config(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'commands' => [
                        'MyClass'
                    ]
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_should_registry_add_must_be_call(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'commands' => [
                        'LineMob\LineBotBundle\Bot\Core\Command\ExitActiveCommand'
                    ]
                ],
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall('linemob.my_bot.registry', 'add' , [
            'LineMob\LineBotBundle\Bot\Core\Command\ExitActiveCommand',
            new Reference('linemob.my_bot.command_handler'),
            true
        ]);
    }

    /**
     * @test
     */
    public function it_should_middleware_configured_inject_in_command_bus(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'middlewares' => [
                        'foo.middleware',
                        'bar.middleware'
                    ]
                ],
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('linemob.my_bot.commandbus', 0, [
            new Reference('foo.middleware'),
            new Reference('bar.middleware')
        ]);
    }

    /**
     * @test
     */
    public function it_should_use_registry_service_follow_config(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'registry' => 'linemob.registry'
                ],
            ],
        ]);

        $this->assertEquals(get_class($this->container->get('linemob.registry')), get_class($this->container->get('linemob.my_bot.registry')));
    }

    /**
     * @test
     */
    public function it_should_use_sender_mocky_when_use_sender_mock_is_true(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'use_sender_mocky' => true
                ],
            ],
        ]);

        $this->assertContainerBuilderHasService('linemob.my_bot.sender');
        $this->assertTrue($this->container->get('linemob.my_bot.sender') instanceof Sender);
    }

    /**
     * @test
     */
    public function it_should_use_line_sender_when_use_sender_mock_is_false(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'use_sender_mocky' => false
                ],
            ],
        ]);

        $this->assertContainerBuilderHasService('linemob.my_bot.sender');
        $this->assertTrue($this->container->get('linemob.my_bot.sender') instanceof LineSender);
    }

    /**
     * @test
     */
    public function it_should_has_log_service_injected_in_bot_when_log_is_true(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'log' => true,
                    'logger' => 'test.logger'
                ],
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('linemob.my_bot.bot', 3, new Reference('test.logger'));
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     */
    public function it_should_has_no_log_service_injected_in_bot_when_log_is_false(): void
    {
        $this->load([
            'bots' => [
                'my_bot' => [
                    'line_channel_access_token' => 'foo',
                    'line_channel_secret' => 'foo',
                    'log' => false,
                    'logger' => 'test.logger'
                ],
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument('linemob.my_bot.bot', 3, null);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions(): array
    {
        return [
            new LineMobLineBotExtension(),
        ];
    }
}
