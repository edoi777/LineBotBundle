<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AdditionalKeywordInterface extends ResourceInterface, TimestampableInterface
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
     * @return KeywordInterface
     */
    public function getKeyword(): ?KeywordInterface;

    /**
     * @param KeywordInterface $keyword
     */
    public function setKeyword(?KeywordInterface $keyword);
}
