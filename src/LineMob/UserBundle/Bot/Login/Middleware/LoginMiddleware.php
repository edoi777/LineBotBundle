<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Login\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;
use LineMob\UserBundle\Bot\Login\Command\LoginCommand;
use LineMob\UserBundle\Bot\Login\Command\QuickLoginCommand;
use LineMob\UserBundle\Bot\Storage\Middleware\AbstractAwareStorageActiveCommand;
use LineMob\UserBundle\Manager\LineUserAuthenticationable;


class LoginMiddleware extends AbstractAwareStorageActiveCommand implements Middleware, TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @var LineUserAuthenticationable
     */
    private $userManager;

    public function __construct($ttl, LineUserAuthenticationable $userManager)
    {
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
        if (!$command instanceof LoginCommand && !$command instanceof QuickLoginCommand) {
            return $next($command);
        }

        $command->message = new TextTemplate();

        if ($this->userManager->getLoggedUser()) {
            $command->message->text = $this->translator->trans('linemob_user.login.already_logged');
            $command->storage->clearLineActiveCmd();
            return $next($command);
        }

        $this->setActive($command);

        if ($command instanceof QuickLoginCommand) {
            $matches = [];
            preg_match(QuickLoginCommand::CMD, $command->input->text, $matches);
            $username = $matches['username'];
            $password = $matches['password'];
        } else {
            @list($username, $password) = explode(' ', $command->input->text, 2);
        }

        $command->message->text = $this->translator->trans('linemob_user.login.active_cmd_help');

        if (!empty($username) && !empty($password)) {
            $command->message->text = $this->translator->trans('linemob_user.login.failure');
            if ($this->userManager->login($username, $password)) {
                $command->message->text = $this->translator->trans('linemob_user.login.success');
                $command->storage->clearLineActiveCmd();
            }
        }

        return $next($command);
    }
}
