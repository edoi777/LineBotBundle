<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Bot\Core\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Input;
use LineMob\Core\RegistryInterface;
use LineMob\LineBotBundle\Context\LineBotContextInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class ActiveSwitcherMiddleware implements Middleware, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var LineBotContextInterface
     */
    private $context;

    public function __construct(LineBotContextInterface $context)
    {
        $this->context = $context;
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

        if (!$activeCmd = $command->storage->getLineActiveCmd()) {
            return $next($command);
        }

        if (new \Datetime > $command->storage->getLineActiveCmdExpiredAt()) {
            return $next($command);
        }

        $fakeInput = new Input([
            'text' => $activeCmd,
            'userId' => $command->input->userId,
            'replyToken' => $command->input->replyToken,
        ]);

        /** @var RegistryInterface $registry */
        $registry = $this->container->get(sprintf('linemob.%s.registry', $this->context->getRunningBot()->getName()));

        $newCommand = $command->switchTo($registry->findCommand($fakeInput));
        $newCommand->merge($command->getData());

        return $next($newCommand);
    }
}
