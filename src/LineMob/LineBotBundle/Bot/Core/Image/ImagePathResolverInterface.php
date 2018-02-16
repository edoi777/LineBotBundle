<?php

declare(strict_types = 1);

namespace LineMob\LineBotBundle\Bot\Core\Image;

interface ImagePathResolverInterface
{
    /**
     * @param $path
     * @param $filter
     *
     * @return string
     */
    public function getMediaPath($path, $filter) :string;

    /**
     * @param $path
     *
     * @return string
     */
    public function getAssetPath($path) :string;
}
