<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Bot\Core\Command;

use LineMob\Core\Command\AbstractCommand;

class ExitActiveCommand extends AbstractCommand
{
    public $cmd = ':exit';
}
