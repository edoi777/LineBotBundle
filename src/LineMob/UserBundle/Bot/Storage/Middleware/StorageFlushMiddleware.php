<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Storage\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class StorageFlushMiddleware implements Middleware
{
    /**
     * @var RepositoryInterface
     */
    private $userManager;

    public function __construct(EntityManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command->storage) {
            return $next($command);
        }

        $this->userManager->persist($command->storage);
        $this->userManager->flush();

        return $next($command);
    }
}
