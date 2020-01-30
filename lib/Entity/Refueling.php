<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Refueling
 *
 * @property string $datetime
 * @property float $amount
 * @property float $volume
 */
class Refueling extends Item
{
    /**
     * @return float
     */
    public function price(): float
    {
        return $this->amount / $this->volume;
    }
}