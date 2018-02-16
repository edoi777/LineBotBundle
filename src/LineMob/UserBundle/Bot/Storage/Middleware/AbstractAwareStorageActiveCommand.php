<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Storage\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;

abstract class AbstractAwareStorageActiveCommand implements Middleware
{
    /**
     * @var string
     */
    private $ttl;

    public function __construct(string $ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @param AbstractCommand $command
     */
    public function setActive(AbstractCommand $command)
    {
        $command->storage->setLineActiveCmd($command->getCmd());
        $command->storage->setLineActiveCmdExpiredAt((new \DateTime())->modify($this->ttl));
    }
}
