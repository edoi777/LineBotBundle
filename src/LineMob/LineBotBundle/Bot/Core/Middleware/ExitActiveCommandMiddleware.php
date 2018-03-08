<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Bot\Core\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\TemplateInterface;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Bot\Core\Command\ExitActiveCommand;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;

class ExitActiveCommandMiddleware implements Middleware, TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof ExitActiveCommand || !$command->storage) {
            return $next($command);
        }

        $command->storage->clearLineActiveCmd();
        $command->storage->setLineCommandData([]);

        if ($command->message instanceof TemplateInterface) {
            return $next($command);
        }

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_bot.exit.success');

        return $next($command);
    }
}
