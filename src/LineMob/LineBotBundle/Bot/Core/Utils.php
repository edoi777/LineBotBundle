<?php

declare(strict_types=1);

namespace LineMob\LineBotBundle\Bot\Core;

final class Utils
{
    /**
     * @param array $partitions
     * @param int $width
     * @param int $height
     * @param int $position
     * @return array
     */
    public static function getImageMapPosition(array $partitions, int $width, int $height, int $position)
    {
        if (2 !== count($partitions)) {
            throw new \InvalidArgumentException("Partitions must be array 2 items");
        }

        if ($position > array_product($partitions)) {
            throw new \InvalidArgumentException(sprintf('Position at %s cant more than %s', $position, array_product($partitions)));
        }

        $y = $position / $partitions[0];
        $y = (int) ((!is_float($y)) ? $y - 1 : $y);
        $x = $position - ($partitions[0] * $y) - 1;

        $areaWidth = $width / $partitions[0];
        $areaHeight = $height / $partitions[1];

        return [
            'x' => $x * $areaWidth,
            'y' => $y * $areaHeight,
            'areaWidth' => $areaWidth,
            'areaHeight' => $areaHeight
        ];
    }
}
