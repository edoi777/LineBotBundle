<?php

namespace LineMob\KeywordBundle\Form\Type;

use PhpMob\MediaBundle\Form\Type\ImageType;

class ReplyImageMessagePictureType extends ImageType
{
    public function getFilterSection()
    {
        return 'reply_image_picture';
    }
}
