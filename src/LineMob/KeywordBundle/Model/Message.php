<?php

namespace LineMob\KeywordBundle\Model;

class Message implements MessageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var AbstractReplyMessage
     */
    protected $replyMessage;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var KeywordInterface
     */
    protected $keyword;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AbstractReplyMessage
     */
    public function getReplyMessage(): ?AbstractReplyMessage
    {
        return $this->replyMessage;
    }

    /**
     * @param AbstractReplyMessage $replyMessage
     */
    public function setReplyMessage(?AbstractReplyMessage $replyMessage)
    {
        $this->replyMessage = $replyMessage;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(?int $position)
    {
        $this->position = $position;
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
