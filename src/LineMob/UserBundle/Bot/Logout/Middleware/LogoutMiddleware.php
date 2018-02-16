<?php

declare(strict_types=1);

namespace LineMob\UserBundle\Bot\Logout\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;
use LineMob\UserBundle\Bot\Logout\Command\LogoutCommand;
use LineMob\UserBundle\Manager\LineUserAuthenticationable;


class LogoutMiddleware implements Middleware, TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @var LineUserAuthenticationable
     */
    private $userManager;

    /**
     * @var bool
     */
    private $allowLogout;

    public function __construct(LineUserAuthenticationable $userManager, $allowLogout)
    {
        $this->userManager = $userManager;
        $this->allowLogout = $allowLogout;
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof LogoutCommand) {
            return $next($command);
        }

        $command->message = new TextTemplate();

        if (!$this->userManager->getLoggedUser()) {
            $command->message->text = $this->translator->trans('linemob_user.logout.empty_session');
            return $next($command);
        }

        if (!$this->allowLogout) {
            $command->message->text = $this->translator->trans('linemob_user.logout.cannot_logout');
            return $next($command);
        }

        $this->userManager->logout();
        $command->message->text = $this->translator->trans('linemob_user.logout.success');

        return $next($command);
    }
}
