<?php

namespace LineMob\LineBotBundle\Context;

use LineMob\LineBotBundle\Bot\Bot;

class LineBotContext implements LineBotContextInterface
{
    /**
     * @var Bot
     */
    private $bot = null;

    /**
     * {@inheritdoc}
     */
    public function getRunningBot(): ?Bot
    {
        return $this->bot;
    }

    /**
     * {@inheritdoc}
     */
    public function setRunningBot(?Bot $bot)
    {
        if (null !== $this->bot && null !== $bot) {
            throw new \LogicException('Cannot override running bot');
        }

        $this->bot = $bot;
    }
}
