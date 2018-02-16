<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface KeywordInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @return string
     */
    public function getWord(): ?string;

    /**
     * @param string $word
     */
    public function setWord(?string $word);

    /**
     * @return AdditionalKeywordInterface
     */
    public function getAdditionalKeywords();

    /**
     * @param AdditionalKeywordInterface $additionalKeyword
     */
    public function hasAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword);

    /**
     * @param AdditionalKeywordInterface $additionalKeyword
     */
    public function addAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword);

    /**
     * @param AdditionalKeywordInterface $additionalKeyword
     */
    public function removeAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword);

    /**
     * @return MessageInterface
     */
    public function getMessages();

    /**
     * @param MessageInterface $message
     */
    public function hasMessage(MessageInterface $message);

    /**
     * @param MessageInterface $message
     */
    public function addMessage(MessageInterface $message);

    /**
     * @param MessageInterface $message
     */
    public function removeMessage(MessageInterface $message);
}
