<?php

namespace LineMob\KeywordBundle\Model;

abstract class AbstractReplyMessage implements ReplyMessageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
}
