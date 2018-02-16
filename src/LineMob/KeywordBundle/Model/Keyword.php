<?php

declare(strict_types=1);

namespace LineMob\KeywordBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Keyword implements KeywordInterface
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
     * @var AdditionalKeywordInterface[]|Collection
     */
    protected $additionalKeywords;

    /**
     * @var Collection|AbstractReplyMessage[]
     */
    protected $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->additionalKeywords = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getWord(): ?string
    {
        return $this->word;
    }

    /**
     * {@inheritdoc}
     */
    public function setWord(?string $word)
    {
        $this->word = $word;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditionalKeywords()
    {
        return $this->additionalKeywords;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword)
    {
        return $this->additionalKeywords->contains($additionalKeyword);
    }

    /**
     * {@inheritdoc}
     */
    public function addAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword)
    {
        if (!$this->hasAdditionalKeyword($additionalKeyword)) {
            $this->additionalKeywords->add($additionalKeyword);
            $additionalKeyword->setKeyword($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdditionalKeyword(AdditionalKeywordInterface $additionalKeyword)
    {
        if ($this->hasAdditionalKeyword($additionalKeyword)) {
            $this->additionalKeywords->removeElement($additionalKeyword);
            $additionalKeyword->setKeyword(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMessage(MessageInterface $message)
    {
        return $this->messages->contains($message);
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage(MessageInterface $message)
    {
        if (!$this->hasMessage($message)) {
            $this->messages->add($message);
            $message->setKeyword($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeMessage(MessageInterface $message)
    {
        if ($this->hasMessage($message)) {
            $this->messages->removeElement($message);
            $message->setKeyword(null);
        }
    }
}
