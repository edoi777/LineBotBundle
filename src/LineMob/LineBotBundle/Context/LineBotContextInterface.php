<?php

namespace LineMob\LineBotBundle\Context;

use LineMob\LineBotBundle\Bot\Bot;

interface LineBotContextInterface
{
    /**
     * @return Bot|null
     */
    public function getRunningBot(): ?Bot;

    /**
     * @param Bot|null $bot
     */
    public function setRunningBot(?Bot $bot);
}
