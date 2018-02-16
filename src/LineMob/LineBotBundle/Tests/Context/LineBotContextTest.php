<?php

namespace Linemob\LinebotBundle\Tests\Bot\Core\Image;

use LineMob\Core\Receiver;
use LineMob\LineBotBundle\Bot\Bot;
use LineMob\LineBotBundle\Context\LineBotContext;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


final class LineBotContextTest extends KernelTestCase
{
    /**
     * @var LineBotContext
     */
    private $context;

    /**
     * @var Bot
     */
    private $bot;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->context = $kernel->getContainer()->get('linemob.context.bot');
        $this->bot = $kernel->getContainer()->get('linemob.mock_bot.bot');
    }

    /**
     * @test
     */
    public function its_return_same_as_bot()
    {
        $this->context->setRunningBot($this->bot);
        $this->assertEquals($this->context->getRunningBot(), $this->bot);
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function it_must_throw_when_set_bot_for_exists_bot_in_context()
    {
        $this->context->setRunningBot($this->bot);
        $this->context->setRunningBot($this->bot);
    }
}
