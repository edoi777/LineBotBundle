<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Storage\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\UserBundle\Manager\LineUserContextManagerInterface;
use LineMob\UserBundle\Model\LineUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class StorageConnectMiddleware implements Middleware
{
    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var LineUserContextManagerInterface
     */
    private $userSecurityManager;

    public function __construct(RepositoryInterface $userRepository, FactoryInterface $userFactory, LineUserContextManagerInterface $userSecurityManager)
    {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userSecurityManager = $userSecurityManager;
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$user = $this->userRepository->findOneBy(['lineUserId' => $command->input->userId])) {
            /** @var LineUserInterface $user */
            $user = $this->userFactory->createNew();
        }

        $this->userSecurityManager->setContext($user);

        $command->storage = $user;
        $command->merge((array) $user->getLineCommandData());
        $user->setLineUserId($command->input->userId);

        return $next($command);
    }
}
