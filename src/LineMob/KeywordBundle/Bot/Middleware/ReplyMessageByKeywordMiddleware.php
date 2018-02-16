<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Bot\Middleware;

use League\Tactician\Middleware;
use LineMob\Core\Command\AbstractCommand;
use LineMob\Core\Template\ImageTemplate;
use LineMob\Core\Template\MultiTemplate;
use LineMob\Core\Template\StickerTemplate;
use LineMob\Core\Template\TextTemplate;
use LineMob\KeywordBundle\Model\AdditionalKeywordInterface;
use LineMob\KeywordBundle\Model\KeywordInterface;
use LineMob\KeywordBundle\Model\MessageInterface;
use LineMob\LineBotBundle\Bot\Core\Image\ImagePathResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;


class ReplyMessageByKeywordMiddleware implements Middleware
{
    /**
     * @var RepositoryInterface
     */
    protected $keywordRepository;

    /**
     * @var RepositoryInterface
     */
    protected $additionalKeywordRepository;

    /**
     * @var ImagePathResolverInterface
     */
    private $imagePathResolver;

    public function __construct(
        RepositoryInterface $keywordRepository,
        RepositoryInterface $additionalKeywordRepository,
        ImagePathResolverInterface $imagePathResolver
    )
    {
        $this->keywordRepository = $keywordRepository;
        $this->additionalKeywordRepository = $additionalKeywordRepository;
        $this->imagePathResolver = $imagePathResolver;
    }

    /**
     * @param AbstractCommand $command
     *
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if (!$command->input) {
            return $next($command);
        }

        if (!$value = $command->input->text) {
            return $next($command);
        }

        /** @var KeywordInterface $keyword */

        $additionalKeyword = null;
        if (!$keyword = $this->keywordRepository->findOneBy(['word' => $value])) {
            /** @var AdditionalKeywordInterface $additionalKeyword */
            $additionalKeyword = $this->additionalKeywordRepository->findOneBy(['word' => $value]);
        }

        if (!$keyword && !$additionalKeyword) {
            return $next($command);
        }

        $command->message = new MultiTemplate();

        $replyMessages = [];
        $messages = $keyword ? $keyword->getMessages() : $additionalKeyword->getKeyword()->getMessages();
        /** @var MessageInterface $message */
        foreach ($messages as $message) {
            $type = $message->getType();

            switch ($type) {
                case 'text':
                    $template = new TextTemplate();
                    $template->text = $message->getReplyMessage()->text;
                    $replyMessages[] = $template;
                    break;
                case 'sticker':
                    $template = new StickerTemplate();
                    $template->packageId = $message->getReplyMessage()->packageId;
                    $template->stickerId = $message->getReplyMessage()->packageCode;
                    $replyMessages[] = $template;
                    break;
                case 'image':
                    $imagePath = $message->getReplyMessage()->getPicture()->getPath();
                    $template = new ImageTemplate();
                    $template->previewUrl = $this->imagePathResolver->getMediaPath($imagePath, 'reply_message_image_preview');
                    $template->url = $this->imagePathResolver->getMediaPath($imagePath, 'line_1024x1024');

                    $replyMessages[] = $template;
                    break;
            }
        }

        $command->message->multiMessage = $replyMessages;

        return $next($command);
    }
}
