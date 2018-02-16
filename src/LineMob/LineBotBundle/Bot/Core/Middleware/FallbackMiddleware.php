<?php

declare(strict_types = 1);

namespace LineMob\LineBotBundle\Bot\Core\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Command\FallbackCommand;
use LineMob\Core\Template\TextTemplate;
use LineMob\LineBotBundle\Translation\TranslatorAwareInterface;
use LineMob\LineBotBundle\Translation\TranslatorAwareTrait;

class FallbackMiddleware implements Middleware, TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof FallbackCommand) {
            return $next($command);
        }
        
        if ($command->message) {
            return $next($command);
        }

        $command->message = new TextTemplate();
        $command->message->text = $this->translator->trans('linemob_bot.fallback.default');

        if ($command->storage) {
            $command->storage->clearLineActiveCmd();
            $command->storage->setLineCommandData([]);
        }

        return $next($command);
    }
}
