<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Tests\Bot\Core\Middleware;

use LineMob\Core\Command\FallbackCommand;
use LineMob\Core\Storage\CommandData;
use LineMob\Core\Template\StickerTemplate;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Bot\Core\Command\ExitActiveCommand;
use LineMob\LineBotBundle\Bot\Core\Middleware\ExitActiveCommandMiddleware;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FallbackMiddlewareTest extends KernelTestCase
{
    /**
     * @var ExitActiveCommandMiddleware
     */
    private $middleware;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->middleware = $kernel->getContainer()->get('linemob.middleware.fallback');
    }

    /**
     * @test
     */
    public function it_should_clear_active_command()
    {
        $command = new FallbackCommand();
        $command->storage = new CommandData();
        $command->storage->setLineActiveCmd('active');
        $command->storage->setLineActiveCmdExpiredAt(new \DateTime());
        $command->storage->setLineCommandData(['data' => 'foo']);
        $this->middleware->execute($command, function() {});

        $this->assertEquals(null, $command->storage->getLineActiveCmd());
        $this->assertEquals(null, $command->storage->getLineActiveCmdExpiredAt());
        $this->assertEquals([], $command->storage->getLineCommandData());
    }

    /**
     * @test
     */
    public function it_should_create_text_template_when_no_message_build()
    {
        $command = new FallbackCommand();
        $command->storage = new CommandData();
        $command->message = null;
        $this->middleware->execute($command, function() {});

        $this->assertInstanceOf(TextTemplate::class, $command->message);
    }

    /**
     * @test
     */
    public function it_should_not_create_text_template_when_message_has_built()
    {
        $command = new FallbackCommand();
        $command->storage = new CommandData();
        $command->message = new StickerTemplate();
        $this->middleware->execute($command, function() {});

        $this->assertInstanceOf(StickerTemplate::class, $command->message);
    }
}
