<?php

namespace LineMob\KeywordBundle\Model;

use PhpMob\MediaBundle\Model\FileAwareInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class ReplyImageMessage extends AbstractReplyMessage implements FileAwareInterface
{
    use TimestampableTrait;

    /**
     * @var ReplyImageMessagePicture
     */
    protected $picture;

    /**
     * {@inheritdoc}
     */
    public function getFileBasePath()
    {
        return '/private/reply-images';
    }

    /**
     * {@inheritdoc}
     */
    public function getPicture(): ?ReplyImageMessagePicture
    {
        return $this->picture;
    }

    /**
     * {@inheritdoc}
     */
    public function setPicture(?ReplyImageMessagePicture $picture): void
    {
        $this->picture = $picture ? ($picture->getFile() ? $picture : null) : null;

        if ($this->picture) {
            $picture->setOwner($this);
        }
    }
}
