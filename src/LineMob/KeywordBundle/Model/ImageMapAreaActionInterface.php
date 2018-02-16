<?php

namespace LineMob\KeywordBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ImageMapAreaActionInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getImageMapLink(): ?string;

    /**
     * @param string $imageMapLink
     */
    public function setImageMapLink(?string $imageMapLink);

    /**
     * @return string
     */
    public function getImageMapText(): ?string;

    /**
     * @param string $imageMapText
     */
    public function setImageMapText(string $imageMapText);

    /**
     * @return int
     */
    public function getX(): ?int;

    /**
     * @param int $x
     */
    public function setX(?int $x);

    /**
     * @return int
     */
    public function getY(): ?int;

    /**
     * @param int $y
     */
    public function setY(?int $y);

    /**
     * @return int
     */
    public function getWidth(): ?int;

    /**
     * @param int $width
     */
    public function setWidth(?int $width);

    /**
     * @return int
     */
    public function getHeight(): ?int;

    /**
     * @param int $height
     */
    public function setHeight(?int $height);

    /**
     * @return ReplyImageMapMessage
     */
    public function getReplyImageMapMessage(): ?ReplyImageMapMessage;

    /**
     * @param ReplyImageMapMessage $replyImageMapMessage
     */
    public function setReplyImageMapMessage(?ReplyImageMapMessage $replyImageMapMessage);
}
