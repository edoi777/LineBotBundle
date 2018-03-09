<?php

declare(strict_types = 1);

namespace LineMob\LineBotBundle\Tests\Bot\Core;

use LineMob\LineBotBundle\Bot\Core\Utils;

final class UtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_has_key()
    {
        $position = Utils::getImageMapPosition([1, 1], 1000, 1000, 1);
        $this->assertArrayHasKey('x', $position);
        $this->assertArrayHasKey('y', $position);
        $this->assertArrayHasKey('areaWidth', $position);
        $this->assertArrayHasKey('areaHeight', $position);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function it_should_throw_when_position_more_than_dimension()
    {
        Utils::getImageMapPosition([1, 1], 1000, 1000, 2);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function it_should_throw_when_partitions_not_2_items_array()
    {
        Utils::getImageMapPosition([1, 1, 3], 1000, 1000, 2);
    }

    /**
     * @test
     */
    public function it_should_return_equal_area_1x1()
    {
        $positions = Utils::getImageMapPosition([1, 1], 1000, 1000, 1);

        $this->assertPosition($positions, 0, 0, 1000, 1000);
    }

    /**
     * @test
     */
    public function it_should_return_equal_area_2x2()
    {
        $positions = Utils::getImageMapPosition([2, 2], 1000, 1000, 1);
        $this->assertPosition($positions, 0, 0, 500, 500);

        $positions = Utils::getImageMapPosition([2, 2], 1000, 1000, 2);
        $this->assertPosition($positions, 500, 0, 500, 500);

        $positions = Utils::getImageMapPosition([2, 2], 1000, 1000, 3);
        $this->assertPosition($positions, 0, 500, 500, 500);

        $positions = Utils::getImageMapPosition([2, 2], 1000, 1000, 4);
        $this->assertPosition($positions, 500, 500, 500, 500);
    }

    /**
     * @test
     */
    public function it_should_return_equal_area_1x3()
    {
        $positions = Utils::getImageMapPosition([1, 3], 1200, 1200, 1);
        $this->assertPosition($positions, 0, 0, 1200, 400);

        $positions = Utils::getImageMapPosition([1, 3], 1200, 1200, 2);
        $this->assertPosition($positions, 0, 400, 1200, 400);

        $positions = Utils::getImageMapPosition([1, 3], 1200, 1200, 3);
        $this->assertPosition($positions, 0, 800, 1200, 400);
    }

    /**
     * @test
     */
    public function it_should_return_equal_area_2x4()
    {
        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 1);
        $this->assertPosition($positions, 0, 0, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 2);
        $this->assertPosition($positions, 500, 0, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 3);
        $this->assertPosition($positions, 0, 500, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 4);
        $this->assertPosition($positions, 500, 500, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 5);
        $this->assertPosition($positions, 0, 1000, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 6);
        $this->assertPosition($positions, 500, 1000, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 7);
        $this->assertPosition($positions, 0, 1500, 500, 500);

        $positions = Utils::getImageMapPosition([2, 4], 1000, 2000, 8);
        $this->assertPosition($positions, 500, 1500, 500, 500);
    }

    /**
     * @param array $positions
     * @param $x
     * @param $y
     * @param $areaWidth
     * @param $areaHeight
     */
    private function assertPosition(array $positions, $x, $y, $areaWidth, $areaHeight)
    {
        $this->assertEquals($positions['x'], $x);
        $this->assertEquals($positions['y'], $y);
        $this->assertEquals($positions['areaWidth'], $areaWidth);
        $this->assertEquals($positions['areaHeight'], $areaHeight);
    }
}
