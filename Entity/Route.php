<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Route
 *
 * @property Distance $distance
 * @property Duration $duration
 */
class Route extends Item
{
    /**
     * @return Distance
     */
    public function distance(): Distance
    {
        $value = 0;
        return Distance::create(['value' => $value]);
    }

    /**
     * @return Duration
     */
    public function duration(): Duration
    {
        $value = 0;
        return Duration::create(['value' => $value]);
    }
}