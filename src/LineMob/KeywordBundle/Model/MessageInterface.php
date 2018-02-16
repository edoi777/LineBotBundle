<?php

namespace LineMob\KeywordBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface MessageInterface extends ResourceInterface
{
    /**
     * @return AbstractReplyMessage
     */
    public function getReplyMessage(): ?AbstractReplyMessage;

    /**
     * @param AbstractReplyMessage $replyMessage
     */
    public function setReplyMessage(?AbstractReplyMessage $replyMessage);

    /**
     * @return string
     */
    public function getType(): ?string;

    /**
     * @param string $type
     */
    public function setType(?string $type);

    /**
     * @return int
     */
    public function getPosition(): ?int;

    /**
     * @param int $position
     */
    public function setPosition(?int $position);

    /**
     * @return KeywordInterface
     */
    public function getKeyword(): ?KeywordInterface;

    /**
     * @param KeywordInterface $keyword
     */
    public function setKeyword(?KeywordInterface $keyword);
}
