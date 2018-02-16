<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Register\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;
use LineMob\UserBundle\Bot\Register\Command\RegisterCommand;
use LineMob\UserBundle\Bot\Register\RegisterWorkflow;
use LineMob\UserBundle\Bot\Storage\Middleware\AbstractAwareStorageActiveCommand;
use LineMob\UserBundle\Manager\LineUserRegistrationable;
use LineMob\UserBundle\Model\LineUserInterface;


class RegisterMiddleware extends AbstractAwareStorageActiveCommand implements Middleware, TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @var RegisterWorkflow
     */
    private $workflow;

    /**
     * @var LineUserRegistrationable
     */
    private $userManager;

    public function __construct($ttl, LineUserRegistrationable $userManager, RegisterWorkflow $workflow)
    {
        $this->workflow = $workflow;
        $this->userManager = $userManager;
        parent::__construct($ttl);
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof RegisterCommand) {
            return $next($command);
        }

        /** @var LineUserInterface $lineUser */
        $lineUser = $command->storage;
        if ($this->userManager->getLoggedUser()) {
            $command->message = new TextTemplate();
            $command->message->text = $this->translator->trans('linemob_user.register.already_logged');
            $lineUser->clearLineActiveCmd();
            return $next($command);
        }

        if ($lineUser->getLineActiveCmd() !== $command->getCmd()) {
            /** @var LineUserInterface$subject */
            $lineUser->setAuthState(null);
        }

        $this->setActive($command);

        if (false === $this->workflow->apply($command)) {
            $command->message = new TextTemplate();
            $command->message->text = $this->translator->trans('linemob_user.register.cannot_register');
            $lineUser->clearLineActiveCmd();
        }

        return $next($command);
    }
}
