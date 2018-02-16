<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Storage\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\UserBundle\Audit\LineInputAuditorInterface;

class AuditInputMiddleware implements Middleware
{
    /**
     * @var LineInputAuditorInterface
     */
    private $auditor;

    public function __construct(LineInputAuditorInterface $auditor)
    {
        $this->auditor = $auditor;
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        $this->auditor->audit($command->input->text, 'text', $command->storage);
        return $next($command);
    }
}
