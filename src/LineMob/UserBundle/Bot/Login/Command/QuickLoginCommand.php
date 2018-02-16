<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Login\Command;

use LineMob\Core\Command\AbstractCommand;

class QuickLoginCommand extends AbstractCommand
{
    const PREFIX_CMD = '(>)';
    const CMD = '/^'.self::PREFIX_CMD.' (?<username>.+) (?<password>\w+)$/';

    public function supported($cmd) {
        return preg_match(self::CMD, $cmd);
    }
}
