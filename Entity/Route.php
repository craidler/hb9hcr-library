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
        return Distance::create($this->offsetGet('routes')[0]['legs'][0]['distance']);
    }

    /**
     * @return Duration
     */
    public function duration(): Duration
    {
        return Duration::create($this->offsetGet('routes')[0]['legs'][0]['duration']);
    }
}