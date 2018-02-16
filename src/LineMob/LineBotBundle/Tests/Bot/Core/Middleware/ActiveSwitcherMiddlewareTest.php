<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Tests\Bot\Core\Middleware;

use LineMob\Core\Command\FallbackCommand;
use LineMob\Core\Input;
use LineMob\Core\Storage\CommandData;
use LineMob\LineBotBundle\Bot\Core\Middleware\ActiveSwitcherMiddleware;
use Linemob\LinebotBundle\Tests\LineBotTestUtils;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ActiveSwitcherMiddlewareTest extends KernelTestCase
{
    /**
     * @var ActiveSwitcherMiddleware
     */
    private $middleware;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->middleware = $kernel->getContainer()->get('linemob.middleware.active_switcher');
        $kernel->getContainer()->get('linemob.context.bot')->setRunningBot($kernel->getContainer()->get('linemob.mock_bot.bot'));
    }

    /**
     * @test
     */
    public function it_should_ignore_when_no_storage()
    {
        $mockCommand = new FallbackCommand();
        $mockCommand->storage = null;

        $originalCommand = clone $mockCommand;
        $this->middleware->execute($mockCommand, function() {});

        $this->assertEquals($originalCommand, $mockCommand);
    }

    /**
     * @test
     */
    public function it_should_ignore_when_no_active_command()
    {
        $mockCommand = new FallbackCommand();
        $mockCommand->storage = new CommandData();
        $mockCommand->storage->setLineActiveCmd(null);

        $originalCommand = clone $mockCommand;
        $this->middleware->execute($mockCommand, function() {});

        $this->assertEquals($originalCommand, $mockCommand);
    }

    /**
     * @test
     */
    public function it_should_ignore_when_expire_active_command()
    {
        $mockCommand = new FallbackCommand();
        $mockCommand->storage = new CommandData();
        $mockCommand->storage->setLineActiveCmd('active');
        $mockCommand->storage->setLineActiveCmdExpiredAt((new \DateTime())->modify('-1 second'));

        $originalCommand = clone $mockCommand;
        $this->middleware->execute($mockCommand, function() {});

        $this->assertEquals($originalCommand, $mockCommand);
    }

    /**
     * @test
     */
    public function it_should_command_mutate_new_command()
    {
        $mockCommand = new FallbackCommand();
        $mockCommand->input = new Input([
            'text' => 'mock:text',
            'userId' => 'mock:userId',
            'replyToken' => 'mock:replyToken'
        ]);
        $mockCommand->storage = new CommandData();
        $mockCommand->storage->setLineActiveCmd('active');
        $mockCommand->storage->setLineActiveCmdExpiredAt((new \DateTime())->modify('+20 minutes'));

        $originalCommand = clone $mockCommand;
        $this->middleware->execute($mockCommand, function() {});

        $this->assertNotEquals($originalCommand, $mockCommand);
    }
}
