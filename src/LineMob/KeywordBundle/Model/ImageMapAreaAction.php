<?php

namespace LineMob\KeywordBundle\Model;

class ImageMapAreaAction implements ImageMapAreaActionInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $imageMapLink;

    /**
     * @var string
     */
    public $imageMapText;
    
    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var ReplyImageMapMessage
     */
    public $replyImageMapMessage;

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
    public function getImageMapLink(): ?string
    {
        return $this->imageMapLink;
    }

    /**
     * @param string $imageMapLink
     */
    public function setImageMapLink(?string $imageMapLink)
    {
        $this->imageMapLink = $imageMapLink;
    }

    /**
     * @return string
     */
    public function getImageMapText(): ?string
    {
        return $this->imageMapText;
    }

    /**
     * @param string $imageMapText
     */
    public function setImageMapText(?string $imageMapText)
    {
        $this->imageMapText = $imageMapText;
    }

    /**
     * @return int
     */
    public function getX(): ?int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(?int $x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(?int $y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(?int $width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(?int $height)
    {
        $this->height = $height;
    }

    /**
     * @return ReplyImageMapMessage
     */
    public function getReplyImageMapMessage(): ?ReplyImageMapMessage
    {
        return $this->replyImageMapMessage;
    }

    /**
     * @param ReplyImageMapMessage $replyImageMapMessage
     */
    public function setReplyImageMapMessage(?ReplyImageMapMessage $replyImageMapMessage)
    {
        $this->replyImageMapMessage = $replyImageMapMessage;
    }
}
