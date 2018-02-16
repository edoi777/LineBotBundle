<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;

class AdditionalKeyword implements AdditionalKeywordInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $word;

    /**
     * @var KeywordInterface
     */
    protected $keyword;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getWord(): ?string
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord(?string $word)
    {
        $this->word = $word;
    }

    /**
     * @return KeywordInterface
     */
    public function getKeyword(): ?KeywordInterface
    {
        return $this->keyword;
    }

    /**
     * @param KeywordInterface $keyword
     */
    public function setKeyword(?KeywordInterface $keyword)
    {
        $this->keyword = $keyword;
    }
}
