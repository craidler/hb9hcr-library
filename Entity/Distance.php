<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Distance
 *
 * @property mixed $km
 * @property int $m
 * @property int $value
 */
class Distance extends Item
{
    /**
     * @param string $round
     * @param array $params
     * @return int
     */
    public function km(string $round = 'ceil', array $params = []): int
    {
        return call_user_func_array($round, [$this->value * 0.001]);
    }

    /**
     * @return int
     */
    public function m(): int
    {
        return $this->value;
    }
}